<?php /* Page to perform administrative tasks.   */ ?>

<?php require_once './header.php'; ?>
<Title>Assignment 2 - MyMDB - Movie Details</Title>

<?php require_once './navbar.php'; ?>

<?php require_once './functions.php'; ?>

<?php
	$db = connect_db();
	admin_only($db);
?>

<!-- Outputting the table information for Genres -->
<h2>Genres</h2>
<table class="list">
	<tr>
		<th>Genre</th>
	</tr>

	<?php
		$sql = "SELECT * FROM genres";
		$sql .= " ORDER BY genre ASC";
		$result_set = mysqli_query($db,$sql);

		while($results = mysqli_fetch_assoc($result_set)) { ?>
		<tr>
			<td><?php echo $results['genre']; ?></td>
		</tr>
		<?php }
	?>
</table>

<!-- Outputting the table information for Movies -->
<h2>Movies</h2>
<table class="list">
	<tr>
		<th>Name</th>
		<th>Release Date</th>
		<th>Description</th>
		<th>Genre</th> 
		<th>&nbsp</th>
		<th>&nbsp</th>
	</tr>

	<?php

		$sql = "SELECT *, M.id AS movie_id FROM movies M";
		$sql .= " JOIN genres G ON M.genre_id = G.id"; // to display genre instead of genre id. 
		$sql .= " ORDER BY name ASC";
		$result_set = mysqli_query($db,$sql);

		while($results = mysqli_fetch_assoc($result_set)) { ?>
		<tr>
			<td><?php echo $results['name']; ?></td>
			<td><?php echo $results['releaseDate'] ; ?></td>
			<td><?php echo $results['description']; ?></td>
			<td><?php echo $results['genre']; ?></td>
			<td><a class="action" href="<?php echo "moviepage.php?movie_id=" . $results['movie_id']; ?>">View</a></td>
			<td><a class="action" href="<?php echo "delete.php?id=" . $results['movie_id']; ?>">delete</a></td>
		</tr>
		<?php }
	?>
</table>

<!-- Outputting the table information for Images -->
<h2>Images</h2>
<table class="list">
	<tr>
		<th>Movie</th>
		<th>Text</th>
		<th>Alt Text</th>
		<th>Image Link</th>
	</tr>

	<?php
		$sql = "SELECT * FROM images I";
		$sql .= " JOIN movies M ON I.movie_id = M.id"; // to display movie name
		$sql .= " ORDER BY M.name ASC";
		$result_set = mysqli_query($db,$sql);

		while($results = mysqli_fetch_assoc($result_set)) { ?>
		<tr>
			<td><?php echo $results['name']; ?></td>
			<td><?php echo $results['text'] ; ?></td>
			<td><?php echo $results['alttext']; ?></td>
			<td><?php echo '<a href="'.$results['filename'].'">View</a>'; ?></td>
		</tr>
		<?php }
	?>
</table>

<!-- Outputting the table information for Users -->
<h2>Users</h2>
<table class="list">
	<tr>
		<th>Username</th>
		<th>Display Name</th>
		<th>Email</th>
		<th>Is Admin?</th>
	</tr>

	<?php
		$sql = "SELECT * FROM users U";
		$sql .= " ORDER BY username ASC";
		$result_set = mysqli_query($db,$sql);

		while($results = mysqli_fetch_assoc($result_set)) { ?>
		<tr>
			<td><?php echo $results['username']; ?></td>
			<td><?php echo $results['displayname'] ; ?></td>
			<td><?php echo $results['email']; ?></td>
			<td><?php echo $results['is_admin']; ?></td>
		</tr>
		<?php }
	?>
</table>

<!-- Outputting the table information for Reviews -->
<h2>Reviews</h2>
<table class="list">
	<tr>
		<th>username</th>
		<th>movie</th>
		<th>Stars</th>
		<th>Date</th>
		<th>Subject</th>
		<th>Body</th>
	</tr>

	<?php
		$sql = "SELECT * FROM reviews R";
		$sql .= " JOIN users U ON R.user_id = U.id"; // to display username
		$sql .= " JOIN movies M ON R.movie_id = M.id"; // to display movie name
		$sql .= " ORDER BY M.name, username ASC";
		$result_set = mysqli_query($db,$sql);

		while($results = mysqli_fetch_assoc($result_set)) { ?>
		<tr>
			<td><?php echo $results['username']; ?></td>
			<td><?php echo $results['name'] ; ?></td>
			<td><?php echo $results['starrating']; ?></td>
			<td><?php echo $results['created']; ?></td>
			<td><?php echo $results['subject']; ?></td>
			<td><?php echo $results['review']; ?></td>
		</tr>
		<?php }
	?>
</table>

<?php require_once './footer.php'; ?>