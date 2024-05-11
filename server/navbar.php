<?php /* This file is meant to be included after the header for the
navigation bar */?>

</head>
<body>
<header>
	<?php
		require_once 'header.php';
		require_once 'functions.php';

		$db = connect_db();
		get_user_data($db, $name, $is_admin);
	?>
	<div class="navLogo clickable" onclick="window.location.href='index.php'">
		<img src="../images/FilmReelLogo.png" alt text="MyMDB Logo">
		<h1>MyMDB</h1>
	</div>
	<div class="w3-bar w3-black">
		<div id ="mobileNav">
			<a id="openNavOverlay"  href="#" class="w3-bar-item w3-button" onclick="openNavOverlay()">&#9776; Menu</a>
			<div id="navOverlay" class="overlay">
			  <a href="#" class="closebtn" onclick="closeNavOverlay();">&times;</a>
			  <div class="navOverlayContent">
				<?php
					// if the user is logged in show the profile and signout buttons
					if (is_logged_in()) {
						echo "<a href=\"#\"><i class=\"fa fa-user\"></i> $name</a>";
					} else {
						echo '<a href="#" onclick="openAuth();">Sign in</a>';
					}
				?>
				<a href="index.php">Browse</a>
				<?php
					// If logged in show logged in only pages
					if (is_logged_in()) {
						echo '<a href="addMovie.php">Add a Movie</a>';
						if ($is_admin) {
							echo '<a href="admin.php">Admin page</a>';
						}
						echo '<a href="settings.php">Settings</a>';
						echo '<a href="logout.php">Log out</a>';
					}

				?>
			  </div>
			</div>
		</div>
		<div id ="fullNav">			
			<a href="index.php" class="w3-bar-item w3-button"><i class="fa fa-home"></i>Browse</a>
			<?php
				// if the user is logged in show the profile and signout buttons
				if (is_logged_in()) {
					echo '<a href="addMovie.php" class="w3-bar-item w3-button">Add a Movie</a>';
					echo '<div class="w3-dropdown-hover w3-green w3-right">';
					echo	'<button class="w3-button">'. $name . ' <i class="fa fa-caret-down"></i></button>';
					echo	'<div class="w3-dropdown-content w3-bar-block w3-dark-grey">';
					if ($is_admin) {
						echo    '<a href="admin.php" class="w3-bar-item w3-button">Admin</a>';
					}
					echo    '<a href="settings.php" class="w3-bar-item w3-button">Settings</a>';
					echo '		<a href="logout.php" class="w3-bar-item w3-button">Log out</a>
							</div>
						</div>';
				} else {
					echo '<a href="#" class="w3-bar-item w3-button w3-right" onclick="openAuth();"><i class="fa fa-lock"></i> Sign in</a>';
				}
			?>
		</div>
	</div>
	<!-- Login + Registration form -->
	<div id="auth" class="auth-background starts-hidden">
		<div class="auth-content">
			<form action="" onsubmit="onLogin(event);">
				<label class="regField" for="displayName"><i class="fa fa-user"></i></label>
				<input class="regField" type="text" name="display_name" placeholder="Display Name" id="displayName">
				<label for="username"><i class="fa fa-user"></i></label>
				<input type="text" name="username" placeholder="Username" id="username">
				<label for="password"><i class="fa fa-lock"></i></label>
				<input type="password" name="password" placeholder="Password" id="password">
				<label class="regField" for="retypepass"><i class="fa fa-lock"></i></label>
				<input class="regField" type="password" name="" placeholder="Retype password" id="retypepass">
				<label class="regField" for="email"><i class="fa fa-envelope"></i></label>
				<input class="regField" type="email" name="email" placeholder="Email" id="email">
				<input type="submit" value="Login">
			</form>
			<a href="" onclick="swapAuth(event);">Register</a>
		</div>
	</div>
</header>
<main>