<?php if (empty($_GET['page'])) $_GET['page'] = 1; ?>

<?php if (empty($user) || !empty($user['login'])): ?>

	<div class="gallery col-sm-12 d-flex flex-wrap">
	<?php if (!empty($gallery[0])): ?>
	<?php foreach ($gallery[0] as $key => $value): ?>
	
		<div class="gallery-cell col-sm-3">
			<ul>
				<li class="gallery-cell-photo"><a href="/photo/<?php echo($value['id']) ?>"><img src="<?php echo($value['photo']) ?>" alt=""></a></li>
				<li class="gallery-cell-author">
					<a href="/profile/<?php echo($value['author']['id']) ?>">

						<span class="align-middle">
							<img src="<?php echo($value['author']['photo']) ?>" width="24px" alt="">
							<?php echo $value['author']['login']; ?>
						</span>

					</a>

					<a href="/photo/<?php echo($value['id']) ?>">
						<span class="align-middle">
							<i class="fas fa-heart"></i>
							<?php echo $value['likes']; ?> <i class="fas fa-comments"></i><?php echo $value["comments_count"]; ?>
						</span>
					</a>
				</li>
				<li class="gallery-cell-info text-right">
					<span><i class="fas fa-calendar-alt"></i> <?php echo (new DateTime($value['create_at']))->format('H:i d.m'); ?></span>
				</li>
			</ul>
		</div>

	<?php endforeach;?>
	<?php elseif (!empty($user)): ?>
		<div class="col-sm-12 m-a text-center">
			Gallery is empty, <a href="/webcam">take a photo?</a>
		</div>
	<?php else: ?>
		<div class="col-sm-12 m-a text-center">
			Gallery is empty, <a href="/account/login">login?</a>
		</div>
	<?php endif; ?>

	<?php if ($gallery[1]["post_count"] > 12): ?>

	<div class="page-controller col-sm-12">
		
		<?php if ($_GET['page'] - 1 > 0): ?>
			<a href="/?page=<?php echo($_GET['page'] - 1) ?>"><i class="fas fa-chevron-left"></i></a>
		<?php else: ?>
			<button disabled><i class="fas fa-chevron-left"></i></button>
		<?php endif; ?>

		<button disabled=""><?php echo ((!empty($_GET['page']) ? $_GET['page'] : '')); ?></button>
	
		<?php if (ceil($gallery[1]["post_count"] / 12) > $_GET['page']): ?>
			<a href="/?page=<?php echo($_GET['page'] + 1) ?>"><i class="fas fa-chevron-right"></i></a>
		<?php else: ?>
			<button disabled><i class="fas fa-chevron-right"></i></button>
		<?php endif; ?>

	</div>

	<?php endif; ?>

	</div>

<?php elseif (!empty($user) && empty($user['login'])): ?>
	<form class="another-form col-sm-4" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="token" value="<?php echo($FToken) ?>">
		<ul>
			<li><div class="input"><h4>First setup:</h4></div></li>
			<li><input name="nickname" type="text" placeholder="Nickname" required=""></li>
			<li><input name="name" type="text" placeholder="Name" required=""></li>
			<li><input name="surname" type="text" placeholder="Surname" required=""></li>
			<li><div class="input">Photo:</div></li>
			<li>
				<style>.standartPhoto {
					width: 64px;
				}</style>
				<input type="radio" name="standartPhoto" id="photo1" value="0" checked="checked"/>
				<label for="photo1"><img class="standartPhoto" src="/public/images/standertPhoto/0.png" /></label>

				<input type="radio" name="standartPhoto" id="photo2" value="1"/>
				<label for="photo2"><img class="standartPhoto" src="/public/images/standertPhoto/1.png" /></label>

				<input type="radio" name="standartPhoto" id="photo3" value="2"/>
				<label for="photo3"><img class="standartPhoto" src="/public/images/standertPhoto/2.png" /></label>

				<input type="radio" name="standartPhoto" id="photo4" value="3"/>
				<label for="photo4"><img class="standartPhoto" src="/public/images/standertPhoto/3.png" /></label>

				<input type="radio" name="standartPhoto" id="photo5" value="4"/>
				<label for="photo5"><img class="standartPhoto" src="/public/images/standertPhoto/4.png" /></label>
			</li>
			<li><div class="input">Or upload your file:</div></li>
			<li><input name="userPhoto" type="file"></li>
			<li><div class="input">*width must be 512x512</div></li>
			<li><button type="submit" name="submit" value="setupProfile">Next</button></li>
		</ul>
	</form>
<?php endif; ?>