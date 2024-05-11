<?php /*  End point for processing movie addition requests  */ ?>

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
		print_r($_FILES['movieImage']['type']);
		exit;
	}

	$db = connect_db();

	// For addMovie.php
	$name = htmlspecialchars($_POST['movieName']);
	$genre_id = htmlspecialchars($_POST['genre_id']);
	$releaseDate= htmlspecialchars($_POST['releaseDate']);
	$description = htmlspecialchars($_POST['description']);

	$sql = "INSERT INTO movies (name, releaseDate, description, genre_id) VALUES ('$name','$releaseDate','$description','$genre_id')";
	$result = mysqli_query($db, $sql);
	$movie_id = mysqli_insert_id($db);
	
	// Create a new file name
	$imageMd5 = md5_file($_FILES['movieImage']['tmp_name']);
	$destFilename = uniqid('movieImages/'.$imageMd5) . $imageTypeMap[$_FILES['movieImage']['type']];
	while (file_exists($destFilename)) {
		usleep(1);
		$destFilename = uniqid(imageMd5);
	}

	// Move to new filename
	$result = move_uploaded_file($_FILES['movieImage']['tmp_name'], $destFilename);

	if (!$result) {
		echo '<p>Error: Failed to move image into storage.</p>';
		mysqli_close($db);
		exit;
	}

	// Insert new image into database
	$text= htmlspecialchars($_POST['text']);
	$alttext = htmlspecialchars($_POST['alttext']);
	$sql = "INSERT INTO images (movie_id, filename, text, alttext) VALUES ($movie_id, '$destFilename', '$text', '$alttext')";
	$result = mysqli_query($db, $sql);

	mysqli_close($db);

	// Redirect to the movie page
    header("Location: moviepage.php?movie_id={$movie_id}");
?>