<h1>Archives</h1>
<div class="archives">
	<ul>
		<?php if(!empty($archives)): ?>
			<?php foreach($archives as $archive): ?>
				<li>
					<span class="archives-date"><?php echo $archive->getDate($global['date.format']); ?></span>
					<a href="<?php echo $archive->getUrl(); ?>"><?php echo $archive->getTitle(); ?></a>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</div>