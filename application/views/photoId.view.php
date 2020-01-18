<?php if (empty($user)) header('Location: /account/login');?>
<div class="container d-flex flex-wrap photo-id">
	<div class="col-sm-8 image">
		<img class="w-100" src="/<?php echo($info['photo']) ?>" alt="">
	</div>
	<div class="col-sm-4 photo-id-rightside">
		<div class="author">
			<a href="/profile/<?php echo($info['owner']['id']) ?>">
				<img  src="<?php echo $info['owner']['photo'] ?>" alt="">
				<span><?php echo $info['owner']['login']; ?></span>
			</a>
		</div>
		<div class="stats">

			<!-- <span> -->
				<form class="stats-like" action="/post/like" method="POST">
					<input type="hidden" name="token" value="<?php echo($FToken) ?>">
					<button type="submit" name="like" value="<?php echo($info['id']) ?>"><i class="fas fa-heart"></i> <?php echo $info['likeCount']; ?></button>
				</form>
				
				<div class="stats-comments">
					<i class="fas fa-comments"></i> <?php echo $info['commentCount']; ?>
				</div>

				<?php if ($user['id'] == $info['owner']['id']): ?>
				<form action="/post/delete" class="pl-3 stats-delete" method="POST">
					<input type="hidden" name="token" value="<?php echo($FToken) ?>">
					<button type="submit" name="deletePhoto" value="<?php echo($info['id']) ?>"><i class="fas fa-times"></i></button>
				</form>
				<?php endif; ?>

			<!-- </span> -->

		</div>
		<div class="comments">
			<?php foreach ($info['allComments'] as $key => $value): ?>
				<div class="comment">

					<a href="/profile/<?php echo($value['id_author']['id']) ?>">
						<img src="<?php echo($value['id_author']['photo']) ?>" alt="">
						<span class="comment-author"><?php echo($value['id_author']['login']) ?></span>
					</a>

					<span class="comment-in"><?php echo($value['comment']) ?></span>
					<?php if ($user['id'] == $info['owner']['id'] || $user['id'] == $value['id_author']['id']): ?>
					<div class="comment-delete">
						<form action="/post/comment/delete" method="POST">
							<input type="hidden" name="token" value="<?php echo($FToken) ?>">
							<button type="submit" name="deleteComment" value="<?php echo($value['id']) ?>"><i class="fas fa-times"></i></button>
						</form>
					</div>
					<?php endif; ?>

				</div>
			<?php endforeach; ?>
		</div>
		<form action="/post/comment/new" class="comment-input" method="POST">
			<input type="hidden" name="token" value="<?php echo($FToken) ?>">
			<input type="text" name="comment" placeholder="comment..." required="">
			<button type="submit" name="id" value="<?php echo($info['id']) ?>"><i class="fas fa-angle-double-right"></i></button>
		</form>
	</div>
</div>
<!-- <?php debug($info) ?> -->