<form class="col-sm-3 form-center" method="POST">
	<ul>
		<li><h1>Reset password</h1></li>
		<input type="hidden" name="token" value="<?php echo($FToken) ?>">
		<li><input name="email" type="text" placeholder="E-mail" required=""></li>
		<li><button type="submit" name="submit" value="reset">Reset password</button></li>
	</ul>
</form>