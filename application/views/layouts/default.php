<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="/public/css/styles.min.css">

</head>
<body>
	<header class="header">
		<ul class="header-menu">
			<!-- <li><img class="logo" src="" alt="logo"></li> -->
			<li><a href="/"><i class="fas fa-home"></i> Home</a></li>
			<?php if (!empty($user) && !empty($user['login'])): ?>
			<li><a href="/webcam"><i class="fas fa-camera-retro"></i> WebCam</a></li>
			<li class="header-menu-rightside">
				<div class="header-menu-login"><?php echo($user['login']) ?><i class="fas fa-sort-down"></i></div>
				<ul>
					<li><a href="/profile/<?php echo $user['id']; ?>"><i class="fas fa-user"></i> Profile</a></li>
					<li><a href="/settings"><i class="fas fa-sliders-h"></i> Settings</a></li>
					<li><a href="/account/logout"><i class="fas fa-sign-out-alt"></i> Exit</a></li>
				</ul>
			</li>
			<li class="header-menu-rightside-img"><img src="<?php echo($user['photo']) ?>" alt=""></li>
			<?php elseif (empty($user)): ?>
				<li class="header-menu-rightside"><a href="/account/register">Register</a></li>
				<li class="header-menu-rightside"><a href="/account/login">Login</a></li>
			<?php else: ?>
				<li class="header-menu-rightside"><a href="/account/logout">Exit</a></li>
			<?php endif; ?>
		</ul>
	</header>

	<?php
	if (!empty($errors))
		echo '<div class="content-error">'.array_shift($errors).'</div>';
	else if (!empty($success))
		echo '<div class="content-success">'.array_shift($success).'</div>';
	?>

	<div class="content col-sm-12 d-flex justify-content-center">
		<?php echo $content; ?>
	</div>

	<footer class="d-flex justify-content-center">
		<span>vyunak © Camagru 2019</span>
	</footer>

	<script src="/public/js/scripts.min.js"></script>
	<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
</body>
</html>