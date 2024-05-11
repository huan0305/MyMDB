<?php
/* Php file that generates search request response html */

	if ($_SERVER['REQUEST_METHOD'] != 'GET') {
		exit('Unexpected request');
	}

	if (!(isset($_GET['genre']) || isset($_GET['movie_search']))) {
		exit('Missing data');
	}

	require_once './functions.php';

	$db = connect_db();

	// Search for movie functionality.
	// Check if movie name is provided
	if (isset($_GET['movie_search']) && !empty($_GET['movie_search'])) {
		$movieName = $_GET['movie_search'];
		// Perform the search query using the $movieName variable
		$sql = "SELECT *, M.id AS movie_id FROM movies M JOIN genres G ON M.genre_id = G.id";
		$sql .= " WHERE name LIKE '%$movieName%'";
		// Execute the query and display the search results
		$result = mysqli_query($db, $sql);
	 } else if (isset($_GET['genre'])) {
		$genre_id = $_GET['genre'];
		// Query for search form to filter search by genre.
		$sql = "SELECT *, M.id AS movie_id FROM movies M JOIN genres G ON M.genre_id = G.id ";
		$sql .= " WHERE genre_id = $genre_id";
	 } else {
		 exit('Missing data');
	 }
	 $results = mysqli_query($db, $sql);
	?>
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
		while($result = mysqli_fetch_assoc($results)) {
			echo '<tr class="clickable" onclick="window.location.href=\'moviepage.php?movie_id='. $result['movie_id'] .'\'">';
			echo '<td>' . $result['name'] .'</td>';
			echo '<td>' . $result['description'] . '</td>';
			echo '<td>' . $result['releaseDate'] . '</td>';
			echo '<td>' . $result['genre'] . '</td>';
			echo '</tr>';
   } ?>
</table>