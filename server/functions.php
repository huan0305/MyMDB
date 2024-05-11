<?php /* Utility functions and private functions.  */



function connect_db() {
	// Connect to the database
	$db = mysqli_connect("localhost", "root", "", "mymdb");
	if (mysqli_connect_errno()) {
		// If there is an error, stop processing and display the error.
		exit('Failed to connect to MySQL: ' . mysqli_connect_error());
	}
	return $db;
}

function disconnect_db($db) {
    if (isset($db)) {
		mysqli_close($db);
    }
}

function go_back() {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
}

function log_out() {
	// Load the session so that we can delete it
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	// Now delete it
	session_destroy();
	// Send the user back to the index
	header('Location: index.php');
}

// Checking whether a user is logged in and redirect to index
// if not
function loggedin_only() {
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	// If not logged in redirect the user
	if (!isset($_SESSION['loggedin'])) {
		header('Location: index.php');
		exit;
	}
}

// Return whether the user is logged in
function is_logged_in() {
	return isset($_SESSION['loggedin']);
}

// Return the user's display name and whether they're an admin
function get_user_data($db, &$displayName, &$is_admin) {
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	$is_admin = 0;
	if (!isset($_SESSION['loggedin'])) {
		$displayName = "Guest";
		return;
	}

	// Check the user database to see if they're an admin
	$sql = 'SELECT displayname, is_admin FROM users WHERE id = '. $_SESSION['userid'];
	$result_set = mysqli_query($db, $sql);
	$result = mysqli_fetch_assoc($result_set);
	if ($result) {
		$displayName = $result['displayname'];
		$is_admin = $result['is_admin'];
		return;
	}
	$displayName = 'unknown user';
}

// Make the page admin only, redirecting if they're not
function admin_only($db) {
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	// If not logged in redirect to index
	if (!isset($_SESSION['loggedin'])) {
		header('Location: index.php');
		exit;
	}
	$sql = 'SELECT is_admin FROM users WHERE id = '. $_SESSION['userid'];

	$result_set = mysqli_query($db, $sql);
	$results = mysqli_fetch_assoc($result_set);
	if ($results && isset($results['is_admin']) && $results['is_admin'] == 1) {
		return;
	}
	// if not admin, redirect
	header('Location: index.php');
	exit;
}

// Handle a sign in request
function sign_in() {
	session_start();
	
	// Verify that the login information is received
	if (!(isset($_POST['username']) && isset($_POST['password']))) {
		// Could not get the data that should have been sent.
		exit('Error incomplete or missing login data.');
	}

	$db = connect_db();
	
	// Use a prepared statement since we're using user input data for sql
	$stmt = mysqli_prepare($db, 'SELECT id, password FROM users WHERE username = ?');
	if (!$stmt) {
		mysqli_close($db);
		exit('Error preparing SQL statement.');
	}

	// Bind the username string parameter
	mysqli_stmt_bind_param($stmt, 's', $_POST['username']);
	// Execute the statement
	mysqli_stmt_execute($stmt);
	// Load the results in an internal buffer
	mysqli_stmt_store_result($stmt);

	// If no such account exists
	if (mysqli_stmt_num_rows($stmt) == 0) {
		mysqli_stmt_close($stmt);
		mysqli_close($db);
		// Set the login confirmation header to false
		header('yougotin: 0');
		exit;
	}

	// The username exists, verify the password

	// Store the result in $id and $password
	mysqli_stmt_bind_result($stmt, $id, $password);
	mysqli_stmt_fetch($stmt);

	// We store passwords using password_hash, use password_verify to check the password.
	if (password_verify($_POST['password'], $password)) {
		// Provided username and password match our records
		// Create a new server session to track this logged in user
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['userid'] = $id;
		header('yougotin: 1');
	} else {
		// Password does not match
		header('yougotin: 0');
	}
	// Cleanup
	mysqli_stmt_close($stmt);
	mysqli_close($db);
}
?>

<?php
// Handle user registration
function register_user() {

	// Verify that the registration form information is received
	if (!(isset($_POST['username'], $_POST['password'], $_POST['email']))
		|| empty($_POST['username']) || empty($_POST['username'])
		|| empty($_POST['email'])) {
		// Could not get the data that should have been sent.
		header('yougotin: 0');
		echo 'Error incomplete or missing login data.';
		exit;
	}

	$db = connect_db();

	// Verify username validity
	if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
		header('yougotin: 0');
		echo 'Invalid username.';
		exit;
	}
	// Verify email address validity
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		header('yougotin: 0');
		echo 'Invalid email.';
		exit;
	}
	// Verify password validity
	if (strlen($_POST['password']) > 30 || strlen($_POST['password']) < 8) {
		header('yougotin: 0');
		echo 'Password must have a length between 8 and 30 characters long.';
		exit;
	}

	// Use a prepared statement since we're using user input data for sql
	// First check if the username already exists
	$stmt = mysqli_prepare($db, 'SELECT id FROM users WHERE username = ?');
	if (!$stmt) {
		header('yougotin: 0');
		mysqli_close($db);
		echo 'Error preparing SQL statement.';
		exit;
	}

	// Bind the username string parameter
	mysqli_stmt_bind_param($stmt, 's', $_POST['username']);
	// Execute the statement
	mysqli_stmt_execute($stmt);
	// Load the results in an internal buffer
	mysqli_stmt_store_result($stmt);

	// If an account already exists
	if (mysqli_stmt_num_rows($stmt) > 0) {
		mysqli_stmt_close($stmt);
		mysqli_close($db);
		// Set the login confirmation header to false
		header('yougotin: 0');
		echo 'Username already exists.';
		exit;
	}

	mysqli_stmt_close($stmt);

	// Username is not used, create a new account
	// Prepare insert statement
	$stmt = mysqli_prepare($db, 'INSERT INTO users (username, password, email, is_admin, displayname) VALUES (?, ?, ?, 0, ?)');
	if (!$stmt) {
		header('yougotin: 0');
		mysqli_close($db);
		echo 'Error preparing SQL statement.';
		exit;
	}

    $displayName = "User";
    if (isset($_POST['displayName']) && !empty($_POST['displayName'])) {
        $displayName = $_POST['displayName'];
    }
	// hash password to protect user from password leaks
	$hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
	mysqli_stmt_bind_param($stmt, 'ssss', $_POST['username'], $hashed, $_POST['email'], $displayName);
	if (mysqli_stmt_execute($stmt)) {
		echo 'You have successfully registered! You can now login!';
		session_start();
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['userid'] = mysqli_insert_id($db);
		header('yougotin: 1');
	} else {
		header('yougotin: 0');
		mysqli_stmt_close($stmt);
		mysqli_close($db);
		echo 'Error inserting new user';
		exit;
	}
	mysqli_stmt_close($stmt);
	mysqli_close($db);
}
?>