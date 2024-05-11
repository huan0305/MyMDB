<?php
/* User settings page to change display name and password */

	include_once './functions.php';

	loggedin_only();
	$db = connect_db();
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (!isset($_POST['displayName'], $_POST['password'])
			|| empty($_POST['displayName'])) {
			exit('Missing data for user update.');
		}

		$user_id = $_SESSION['userid'];

		// Verify password validity
		if (strlen($_POST['password']) > 30 || strlen($_POST['password']) < 8) {
			exit('Password must have a length between 8 and 30 characters long.');
		}

		// Prepare update statement
		$stmt = mysqli_prepare($db, 'UPDATE users SET displayname = ?, password = ? WHERE id = ?');
		if (!$stmt) {
			mysqli_close($db);
			exit('Error preparing SQL statement.');
		}

		$displayName = htmlspecialchars($_POST['displayName']);

		// hash password to protect user from password leaks
		$hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
		mysqli_stmt_bind_param($stmt, 'ssi', $displayName, $hashed, $user_id);
		if (mysqli_stmt_execute($stmt)) {
			//echo 'You have successfully updated your display name and password!';
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['userid'] = $user_id;
		} else {
			mysqli_stmt_close($stmt);
			mysqli_close($db);
			exit('Error updating user');
		}
		mysqli_stmt_close($stmt);
	}

	require_once './header.php';

	get_user_data($db, $name, $userid);
?>

<Title>Assignment 2 - MyMDB - Change user settings</Title>

<?php require_once './navbar.php'; ?>

<!-- Form to modify display name and password. -->
<form action="settings.php" method="POST">
	<label for="chngdisplayName"><i class="fa fa-user"></i></label>
	<input type="text" id="chngdisplayName" name="displayName" value="<?php echo $name; ?>">
	<label for="password"><i class="fa fa-lock"></i></label>
	<input type="password" id="changepass" name="password" value="" placeholder="Password">
	<label for="repeat-pwd"><i class="fa fa-lock"></i></label>
	<input type="password" id="repeat-pwd" name="repeat-pwd" value="" placeholder="Retype password">
	<button type="submit">Update</button>
</form>

<?php
	mysqli_close($db);
	require_once './footer.php';
?>