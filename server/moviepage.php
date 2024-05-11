<?php /* This page is used to diplay the movie details */?>

<!-- Header -->

<?php require_once './header.php'; ?>
<Title>Assignment 2 - MyMDB - Movie Details</Title>

<?php require_once './navbar.php'; ?>

<?php require_once './functions.php'; ?>

<?php
	if (!isset($_GET['movie_id'])) {
		// if not output error
		echo '<p>Error: Missing movie id.</p>';
		exit;
	}

	$db = connect_db();

	$movie_id = $_GET['movie_id'];

	// Getting all the information for this movie.
	$result_set = mysqli_query($db, "SELECT * FROM movies WHERE id = $movie_id;");

	// Checking to see if movie is in database. 
	if (!($movie = mysqli_fetch_assoc($result_set))) {
		echo "Couldn't find movie in database";
		exit;
	}

	echo '<h3 id="movieTitle">'. $movie['name'] .'</h3>';

	// Getting all images for this movie. 
	$sql = 'SELECT * FROM images WHERE movie_id = ' . $movie['id'] . ' ORDER BY id ASC';
	$images = mysqli_query($db, $sql);
    ?>
         <div class="carousel">
<!-- Slideshow for movies with multiple images.  -->
<?php
	$numSlides = mysqli_num_rows($images);
	$curSlide = 1;
	while ($image = mysqli_fetch_assoc($images)) {
			echo '<div class="slides fade">';
			echo '<div class="numbertext">'."Image {$curSlide} of {$numSlides}".'</div>';
            echo '    <img src="'.$image['filename'] .'" width="250" heigth="500" alt="'.$image['alttext'].'">';
			echo '<div class="slideText">'.$image['alttext'].'</div>';
            echo '</div>';
			$curSlide += 1;
	}
	echo 	'<a class="prev" onclick="plusSlides(-1)">&#10094;</a>'
			.'<a class="next" onclick="plusSlides(1)">&#10095;</a>'
			.'</div>';
	echo '<div class="center-text">';
	for ($i = 1; $i <= $numSlides; $i++) {
		echo '<span class="dot" onclick="currentSlide('.$i.')"></span>';
	}
	echo '</div>';

	// Release date and movie description display.
	echo '<div id="mainText">'; // mainText div for styling
	echo '<p>Released: ' . $movie['releaseDate'] .'</p>';
	echo '<p>' . $movie['description'] .'</p>';

    if (is_logged_in()) {
?>

<!-- Adds image(s) for this movie.  -->
<form method="post" action="imageupload.php" enctype="multipart/form-data">
	<h3>Add an Image</h3>
	<label for="shrtDesc">Description of image</label>
	<input type="text" name="text" id="shrtDesc" value="" placeholder="Short description">
	<label for="alttext">Long description of image</label>
	<input type="text" name="alttext" id="alttext" value="" placeholder="Long description">
	<input name="movie_id" value="<?php echo $movie_id?>" style="display:none">
	<label for="imageFile"><i class="fa fa-file"></i></label>
	<input type="file" name="movieImage" id="imageFile" required>
	<button type="submit">Upload</button>
</form>

<?php
    }

	echo '<h4 id="userReviews">User Reviews</h4>';

	// Only allow logged in users to submit reviews
	if (isset($_SESSION['loggedin'])) {

		$userid = $_SESSION['userid'];
		$sql = "SELECT * FROM reviews WHERE movie_id = {$movie_id} AND user_id = {$userid};";
		$result_set = mysqli_query($db,$sql);
	
		// if user has already added a review, don't show form
		if (mysqli_num_rows($result_set) != 0) {
			echo "<p>You can only have one review per movie.</p>";
		} else {
			echo '<form action="reviews.php" method="POST" onsubmit="submitReview(event)">
					<h3>Add your review</h3>
					<label for="subject">Subject:</label>
					<input type="text" id="subject" name="subject">
					<label for="movie_review">Review</label>
					<textarea id="movie_review" name="movie_review" rows="4" cols="50"></textarea>
					<label for="star_rating">How many stars:</label>
					<select id="star_rating">
						<option value="1">✭</option>
						<option value="2">✭✭</option>
						<option value="3">✭✭✭</option>
						<option value="4">✭✭✭✭</option>
						<option value="5" selected>✭✭✭✭✭</option>
					</select>
					<button type="submit">Submit</button>
				</form>';
		}
	}
	echo '</div>'; // end tag for mainText div
	mysqli_close($db);
?>

<?php include_once './footer.php'; ?>