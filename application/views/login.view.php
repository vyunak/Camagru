<form class="col-sm-3 form-center" method="POST">
	<ul>
		<li><h1>Login</h1></li>
		<input type="hidden" name="token" value="<?php echo($FToken) ?>">
		<li><input name="email" type="text" placeholder="Login"></li>
		<li><input name="password" type="password" placeholder="Password"></li>
		<li><button type="submit" name="submit" value="login">Login</button></li>
		<li><a href="/account/register">Registration</a> <a href="/account/password">Forgot your password?</a></li>
	</ul>
</form>