<?php /* This is the index/homepage of MyMDB  */ ?>

<!-- Header -->
<?php require_once './header.php'; ?>
<Title>Assignment 2 - MyMDB</Title>
<script src="../scripts/searchscript.js" defer></script>
<?php require_once './navbar.php'; ?>

<?php require_once './functions.php'; ?>

<?php
	// Connecting database.
	$db = connect_db();

	echo '<h2>Recently Added:</h2>';
	echo '<div class="movieContainer">';

	// Display 4 most recent additions to the database. 
	$result_set = mysqli_query($db, "SELECT * FROM movies ORDER BY id DESC LIMIT 4;");

	// Displays each movie's main poster and its name. Clicking it takes you to the movie page. 
	while($movie = mysqli_fetch_assoc($result_set)) {
		echo '<div class="movie" onclick="window.location.href=\'moviepage.php?movie_id='. $movie['id'] .'\';">';

		echo '<h3>'. $movie['name'] .'</h3>';
		$sql = 'SELECT * FROM images WHERE movie_id = ' . $movie['id'] . ' ORDER BY id ASC LIMIT 1;';
		$images_results = mysqli_query($db, $sql);
		if ($image = mysqli_fetch_assoc($images_results)) {
			echo '<img src="'. $image['filename'] .'" width=250 heigth=500>';
		}
		echo '</div>';
	}
	echo '</div>';

	// Query for initial search results
	$sqlGenre = "SELECT *, M.id FROM movies M JOIN genres G ON M.genre_id = G.id ";
	$resultGenre = mysqli_query($db, $sqlGenre);
?>

<!-- Form to filter movie searches.-->
<div id="browseForm">
	<form action='search.php' method="get" onsubmit="return movieSearch(event)">
	<div>
		<label for="searchMovie">Search by Movie Name:</label>
		<input type="text" id="searchMovie" name="movie_search" placeholder="Enter movie name">
	</div>
	<p>OR</p>
	

		<!-- Searches movies in database by genre. -->
		<label for="searchGenre">Search by Genre:</label>
		<select id="searchGenre" name="genre" onchange="clearSearchMovie()"> 
			<option>Select Genre</option>
			<?php
				// Populate the genre options
				$sqlGenres = "SELECT * FROM genres";
				$resultGenres = mysqli_query($db, $sqlGenres);
				while ($genre = mysqli_fetch_assoc($resultGenres)) {
					echo "<option value='{$genre['id']}'>{$genre['genre']}</option>";
				}	
			?>
		</select>
		<button type="submit">Search</button>
	</form>
</div>

<!-- Table for results of movie search.-->
<table id="searchResults" class="list">
  	<tr>
		<th>Movie</th>
		<th>Description</th>
		<th>Release Date</th>
		<th>Genre</th> 
		<th>&nbsp</th>
		<th>&nbsp</th>
  	</tr>

    <?php
		while($results = mysqli_fetch_assoc($resultGenre)) {
			echo '<tr class="clickable" onclick="window.location.href=\'moviepage.php?movie_id='. $results['id'] .'\'">';
			echo '<td>' . $results['name'] .'</td>';
			echo '<td>' . $results['description'] . '</td>';
			echo '<td>' . $results['releaseDate'] . '</td>';
			echo '<td>' . $results['genre'] . '</td>';
			echo '</tr>';
   		} 
	?>
</table>

<?php include_once './footer.php'; ?>