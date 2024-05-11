<?php /* Process User submitted Image uploads   */?>

<?php require_once './functions.php'; ?>

<?php

	// Check if this is being accessed as a post request
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		// if not output error
		echo '<p>Error: No form submitted</p>';
		exit;
	}

	// Check whether the user is logged in
	loggedin_only();

	// Verify that we've received the image file
	if (!isset($_FILES['movieImage'])) {
		// if not output error
		echo '<p>Error: No image file submitted</p>';
		exit;
	}

	// Verify that we've received the movie name
	if (!isset($_POST['movie_id'])) {
		// if not output error
		echo '<p>Error: Missing movie id.</p>';
		exit;
	}

	if (!isset($_POST['text'], $_POST['alttext'])) {
		exit("missing descriptions");
	}

	// Check the file size
	if ($_FILES['movieImage']['size'] > 1_500_000) {
		// if not output error
		echo '<p>Error: Image file too large</p>';
		exit;
	}

	$imageTypeMap = array('image/png' => '.png', 'image/jpeg'=>'.jpeg');

	// Check the file type
	if (!array_key_exists($_FILES['movieImage']['type'], $imageTypeMap)) {
		echo '<p>Error: Invalid image file type</p>';
		exit;
	}

	$db = connect_db();

	/* create a prepared statement */
	$stmt = mysqli_prepare($db, 'SELECT id FROM movies WHERE id=?');

	/* bind parameters for movie name */
	mysqli_stmt_bind_param($stmt, "i", $_POST['movie_id']);

	/* execute query */
	mysqli_stmt_execute($stmt);

	/* bind result variable */
	mysqli_stmt_bind_result($stmt, $movieId);

	/* fetch value */
	mysqli_stmt_fetch($stmt);

	mysqli_stmt_close($stmt);

	// Check that the movie exists
	if (is_null($movieId)) {
		echo '<p>Movie id not found.</p>';
		mysqli_close($db);
		exit;
	}

	// Create a new file name
	$imageMd5 = md5_file($_FILES['movieImage']['tmp_name']);
	$destFilename = uniqid('movieImages/'.$imageMd5) . $imageTypeMap[$_FILES['movieImage']['type']];
	while (file_exists($destFilename)) {
		usleep(1);
		$destFilename = uniqid(imageMd5);
	}

	$result = move_uploaded_file($_FILES['movieImage']['tmp_name'], $destFilename);

	if (!$result) {
		echo '<p>Error: Failed to move image into storage.</p>';
		mysqli_close($db);
		exit;
	}

	/* create a prepared statement */
	$stmt = mysqli_prepare($db, 'INSERT INTO images (movie_id, filename, text, alttext) VALUES (?, ?, ?, ?)');

	$text= htmlspecialchars($_POST['text']);
	$alttext = htmlspecialchars($_POST['alttext']);
	/* bind parameters for movie_id and filename */
	mysqli_stmt_bind_param($stmt, "isss", $movieId, $destFilename, $text, $alttext);

	/* execute query */
	if (!mysqli_stmt_execute($stmt)) {
		echo '<p>Error: Failed to insert image into database.</p>';
		mysqli_stmt_close($stmt);
		mysqli_close($db);
		exit;
	}

	mysqli_stmt_close($stmt);
	mysqli_close($db);
	header("Location: moviepage.php?movie_id={$movieId}");
?>