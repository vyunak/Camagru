<?php if (!empty($success)): ?>
<head>
	<?php if (empty($user)): ?>
		<meta http-equiv="refresh" content="3;URL=<?php echo '//'.$_SERVER['HTTP_HOST'].'/account/login'; ?>" />
	<?php else: ?>
		<meta http-equiv="refresh" content="3;URL=<?php echo '//'.$_SERVER['HTTP_HOST'].'/'; ?>" />
	<?php endif; ?>
</head>
<?php endif; ?>

<?php if (empty($visible)): ?>
	<form class="col-sm-3 form-center" method="POST">
	<ul>
		<input type="hidden" name="token" value="<?php echo($FToken) ?>">
		<li><input name="password" type="text" placeholder="Password" required=""></li>
		<li><input name="passwordConfirm" type="text" placeholder="Confirm password" required=""></li>
		<li><button type="submit" name="submit" value="reset">Reset password</button></li>
	</ul>
</form>
<?php else: ?>
<form class="col-sm-3 form-center" method="POST">
	<ul>
		<li><h1>Request not found...</h1></li>
		<?php
			if (empty($_SERVER['HTTP_REFERER']))
				$back = "//{$_SERVER['HTTP_HOST']}";
			else
				$back = $_SERVER['HTTP_REFERER'];
		?>
		<li><a href="<?php echo($back) ?>">Go back</a></li>
	</ul>
<?php endif; ?>