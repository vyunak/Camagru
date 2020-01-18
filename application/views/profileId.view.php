
<div class="profile container col-sm-12 col-md-8">
	
	<?php	$profile = $info[0][0];
			$photo = $info[1];
	?>
	<div class="user col-sm-12 d-flex flex-wrap">
		<div nickname="<?php echo $profile['login']; ?>" class="user-photo col-sm-3">
			<img src="<?php echo $profile['photo']; ?>" alt="">
		</div>
		<div class="user-info col-sm-9">
			<div class="user-nsl">
				<span><?php echo $profile['name']; ?></span>
				<span><?php echo $profile['surname']; ?></span>
			</div>
			<div class="user-post-date text-right">
				<span><i class="fas fa-images"></i> <?php echo $profile['post_count']; ?></span> 
				<span><i class="fas fa-calendar-day"></i> <?php echo (new DateTime($profile['register_at']))->format('d.m'); ?></span>
			</div>
		</div>
	</div>

	<div class="user-gallery container col-sm-12 d-flex flex-wrap">
		<?php foreach ($photo as $key => $value): ?>
			<div class="user-post col-sm-6">
				<a href="/photo/<?php echo($value['id']) ?>">
					<div class="user-post-photo">
						<img src="/<?php echo $value['photo']; ?>" alt="">
						<div class="user-post-info">
							<span class="col"><i class="far fa-heart"></i> <?php echo $value['likeCount']; ?></span>
							<span class="col"><i class="far fa-comments"></i> <?php echo $value['commentsCount']; ?></span>
							<span class="text-right col"><?php echo (new DateTime($value['create_at']))->format('H:i d.m'); ?></span>
						</div>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if ($profile['post_count'] > 8): ?>

	<div class="page-controller col-sm-12">
		
		<?php if ($_GET['page'] - 1 > 0): ?>
			<a href="?page=<?php echo($_GET['page'] - 1) ?>"><i class="fas fa-chevron-left"></i></a>
		<?php else: ?>
			<button disabled><i class="fas fa-chevron-left"></i></button>
		<?php endif; ?>

		<button disabled=""><?php echo ((!empty($_GET['page']) ? $_GET['page'] : '')); ?></button>
	
		<?php if (ceil($profile['post_count'] / 8) > $_GET['page']): ?>
			<a href="?page=<?php echo($_GET['page'] + 1) ?>"><i class="fas fa-chevron-right"></i></a>
		<?php else: ?>
			<button disabled><i class="fas fa-chevron-right"></i></button>
		<?php endif; ?>

	</div>

	<?php endif; ?>

</div>
