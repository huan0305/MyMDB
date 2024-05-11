<?php /* Process delete requests from the admin page.  */ ?>

<!-- Header -->
<?php require_once './header.php'; ?>
<Title>Assignment 2 - MyMDB - Movie Details</Title>

<?php require_once './navbar.php'; ?>

<?php require_once './functions.php'; ?>

<?php
	if (!isset($_GET['id'])) {
		// if not output error
		echo '<p>Error: Missing movie id.</p>';
		exit;
	}

	$db = connect_db();

	admin_only($db);

	$movie_id = $_GET['id'];

	$result_set2 = mysqli_query($db, "DELETE FROM reviews WHERE movie_id = $movie_id");
	$result_set2 = mysqli_query($db, "DELETE FROM images WHERE movie_id = $movie_id");
	$result_set = mysqli_query($db, "DELETE FROM movies WHERE id = $movie_id");

	mysqli_close($db);
	go_back();
 ?>