<?php if(count($articles) < 1 ): ?>
	<h3>No articles found!</h3>
<?php else: ?>
	<?php foreach($articles as $article): ?>
		<article>
			<header>
				<h1><a href="<?php echo $article->getUrl(); ?>"><?php echo $article->getTitle(); ?></a></h1>
				<div class="postmeta">
					<span class="date"><?php echo date($global['date.format'],strtotime($article->getDate())); ?></span> /
					<span class="author-by"> by </span>
					<span class="author"><?php  echo ($author = $article->getAuthor()) ? $author : $global['author.name']; ?></span>
				</div>
			</header>

			<section class="content">
				<?php echo $article->getSummary(300); ?>&hellip;
			</section>
			<div class="more">
				<a href="<?php echo $article->getUrl(); ?>" class="btn">Read on &raquo;</a>
			</div>
		</article>
	<?php endforeach; ?>
<?php endif;?>