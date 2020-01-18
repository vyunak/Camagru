<form method="POST" class="another-form col-sm-4" enctype="multipart/form-data">
	<ul>
		<input type="hidden" name="token" value="<?php echo($FToken) ?>">
		<li><div class="input">Profile Info:</div></li>
		<li><input name="nickname" type="text" value="<?php echo($user['login']) ?>" placeholder="Nickname"></li>
		<li><input name="name" type="text" value="<?php echo($user['name']) ?>" placeholder="Name"></li>
		<li><input name="surname" type="text" value="<?php echo($user['surname']) ?>" placeholder="Surname"></li>
		<!-- <br> -->
		<li><div class="input">Photo:</div></li>
		<li><img src="<?php echo($user['photo']) ?>" alt="Your photo!" width="128px" height="128px"></li>
		<li><div class="input">*width must be 512x512</div></li>
		<li><input name="userPhoto" type="file"></li>
		<!-- <br> -->
		<li><div class="input">Email notification:</div></li>
		<li><input <?php echo (($user['email_noty'] == 1) ? 'checked="checked"' : ''); ?> type="radio" name="email_noty" id="emailSennder" value="1" checked="checked"/><label for="emailSennder">On</label></li>
		<li><input <?php echo (($user['email_noty'] == 0) ? 'checked="checked"' : ''); ?> type="radio" name="email_noty" id="emailSennderOff" value="0"/><label for="emailSennderOff">Off</label></li>
		<li><button type="submit" name="settings" value="saveSettings">Save</button></li>
		<br>
		<li><div class="input">Reset Password:</div></li>
		<li><button type="submit" name="settings" value="changePassword">Reset password</button></li>
	</ul>
</form>