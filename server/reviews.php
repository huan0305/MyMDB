<?php /* Returns reviews to GET requests and handles reviews POST requests */

	require_once './functions.php';
 
	switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		// Confirm its a proper request
		if (!isset($_GET["from_time"]) || !isset($_GET["movie_id"])) {
			exit('Missing parameters for get request.');
		}

		$db = connect_db();

		// Query all the reviews since the times requested
		$sql = 'SELECT U.displayname,  UNIX_TIMESTAMP(R.created) AS `timestamp`, R.starrating AS `stars`,'
		. ' R.review AS `reviewtext`, R.subject AS `subject`'
		. ' FROM reviews R JOIN users U ON R.user_id = U.id'
		. ' WHERE movie_id = ? AND  UNIX_TIMESTAMP(created) > ?'
		. ' ORDER BY created ASC';

		$stmt = mysqli_prepare($db, $sql);
		if (!$stmt) {
			mysqli_close($db);
			exit('Error preparing SQL statement.');
		}

		// Bind the movie id and from_time
		mysqli_stmt_bind_param($stmt, 'ii', $_GET["movie_id"], $_GET["from_time"]);

		// Execute the statement
		mysqli_stmt_execute($stmt);

		// Get the results
		$result_set = mysqli_stmt_get_result ($stmt);

		if (!$result_set) {
			mysqli_stmt_close($stmt);
			mysqli_close($db);
			exit('Error getting SQL results.');
		}

		// Initialize an empty array to store fetched reviews
		$rows = array();

		// Fetch all rows from the result set
		while ($row = mysqli_fetch_assoc($result_set)) {
			// Append each row to the $rows array
			$rows[] = $row;
		}

		// Respond with reviews as JSON
		echo json_encode($rows);

		// Cleanup
		mysqli_free_result($result_set);
		mysqli_stmt_close($stmt);
		mysqli_close($db);
		break;
	case 'POST':

		session_start();

		// Must be logged in to post reviews.
		if (!(isset($_SESSION['loggedin']) && isset($_SESSION['userid']))) {
			exit("Not logged in!");
		}

		// Validate review
		if (!(isset($_POST['movie_id'])
			  && isset($_POST['subject'])
			  && isset($_POST['reviewtext'])
			  && isset($_POST['starrating']))) {
			exit("Missing or incomplete form data!");
		}
		
		// Validate movie_id and starrating
		if (!is_numeric($_POST['movie_id'])) {
			exit("movie_id must be an integer!");
		}
		$movie_id = intval($_POST['movie_id']);
		if (!is_numeric($_POST['starrating'])) {
			exit("starrating must be an integer!");
		}
		$star_rating = intval($_POST['starrating']);
		if (empty(trim($_POST['subject']))
			|| empty(trim($_POST['reviewtext']))) {
			exit("Subject and review text must not be empty");
		}
		// Sanitize input
		$subject = htmlspecialchars($_POST['subject']);
		$review = htmlspecialchars($_POST['reviewtext']);
		
		$db = connect_db();

		$sql = 'INSERT INTO reviews (user_id, movie_id, subject, review, starrating) VALUES (?,?,?,?,?);';

		$stmt = mysqli_prepare($db, $sql);
		if (!$stmt) {
			mysqli_close($db);
			exit('Error preparing SQL statement.');
		}

		// Bind the parameters
		mysqli_stmt_bind_param($stmt, 'iissi', $_SESSION['userid'], $movie_id, $subject, $review, $star_rating);

		// Execute the statement
		if (!mysqli_stmt_execute($stmt)) {
			exit('Error inserting new review');
		}
	
		// Cleanup
		mysqli_stmt_close($stmt);
		mysqli_close($db);
		break;
	default:
		exit('unknown request method');
		break;
	}
?>