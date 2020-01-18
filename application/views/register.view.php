<form class="col-sm-3 form-center" method="POST">
	<ul>
		<li><h1>Register</h1></li>
		<input type="hidden" name="token" value="<?php echo($FToken) ?>">
		<li><input name="email" type="text" placeholder="E-mail"></li>
		<li><input name="password" type="password" placeholder="Password"></li>
		<li><input name="confirm_password" type="password" placeholder="Confirm Password"></li>
		<li><button type="submit" name="submit" value="register">Register</button></li>
	</ul>
</form>