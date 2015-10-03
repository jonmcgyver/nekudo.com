<article class="post">
	<header>
		<h1><?php echo $article->getTitle(); ?></h1>
		<div class="postmeta">
			<span class="date"><?php echo $article->getDate($global['date.format']); ?></span> /
			<span class="author-by"> by </span>
			<span class="author"><?php echo $article->getAuthor() ? $article->getAuthor() : $global['author.name']; ?></span>
		</div>
	</header>

	<section class="content">
		<?php echo $article->getContent(); ?>		
		<div class="tags">
			<div class="hl-sm">Tags</div>
			<?php $tags = []; ?>
			<?php foreach($article->getTags() as $slug => $tag): ?>
				<?php $tags[] = '<span class="tag"><a class="btn" href="/blog/tag/'.$slug.'">'.$tag->name.'</a></span>'; ?>
			<?php endforeach; ?>
			<?php if(!empty($tags)): ?>
				<?php echo implode(' ', $tags); ?>
			<?php endif; ?>
		</div>
	</section>

	<?php if($global['disqus.username']): ?>
		<section class="comments">
			<div id="disqus_thread"></div>
			<script type="text/javascript" src="http://disqus.com/forums/<?php echo $global['disqus.username']; ?>/embed.js"> </script>
			<noscript><a href="http://<?php echo $global['disqus.username']; ?>.disqus.com/?url=ref">View the discussion thread.</a></noscript>
			<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
		</section>
	<?php endif; ?>
</article>