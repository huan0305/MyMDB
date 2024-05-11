<?php /* This page is used to add movies to the database.   */ ?>

<!-- Header -->
<?php require_once './header.php'; ?>
<script src="../scripts/addscript.js" defer></script>
<Title>Assignment 2 - MyMDB - Add movie</Title>

<?php require_once './navbar.php'; ?>

<?php require_once './functions.php'; ?>


<?php
	loggedin_only();

    $db = connect_db();

    // Used to output genres for select drop down input
    $sql2 = "SELECT genre, id FROM genres";
    $result_set = mysqli_query($db,$sql2);

	// Turn a mysqli result set into options for the genre list
    function returnTable($result_set) {
        $result = '';
        while ($row = mysqli_fetch_assoc($result_set)) {
            $id = $row['id'];
            $genre = $row['genre'];
            $result .= "<option value='{$id}'>$genre</option>";
        }
        return $result;
    }
?>

    <!-- Form used to add a new movie to the database.  -->
    <div id="addMovieForm">
        <!-- Uses addmovieprocess.php to handle the form submission and validates input using JavaScript. -->
        <form action='addmovieprocess.php' method="POST" enctype="multipart/form-data" onsubmit="return validate()">
            <h3>Add a New Movie</h3>
            
            <div>
                <label for="movieName">Movie Name</label>
                <input type="text" id="movieName" name="movieName" placeholder="Enter movie name">
            </div>   
            <div>
                <label for="searchGenre">Genre</label>
                <select id="searchGenre" name="genre_id">
                    <?php echo returnTable($result_set); ?>
                </select> 
            </div> 
            <div> 
                <label for="releaseDate">Release Date</label>
                <input type="date" id="releaseDate" name="releaseDate" placeholder="YYYY-MM-DD">
            </div>
            <div>
                <label for="desc">Description</label>
                <input type="textarea" id="desc" name="description" placeholder="Brief synopsis of movie.">
            </div>
            <div>
                <label for="movieImage">Upload Movie Image</label>
                <input type="file" name="movieImage" id="movieImage" required>
            </div>
            <div>
                <label for="shrtDesc">Description of image</label>
                <input type="text" name="text" id="shrtDesc" value="" placeholder="Short description">
            </div>
            <div>
                <label for="alttext">Long description of image</label>
                <input type="text" name="alttext" id="alttext" value="" placeholder="Long description">
            </div>

            <button type="submit">Add Movie</button>

        </form>
    </div>

<?php include_once './footer.php'; ?>
