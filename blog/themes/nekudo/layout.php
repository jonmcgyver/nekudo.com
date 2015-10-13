<?php
$active = ($global['route'] === 'imprint') ? 'imprint' : 'blog';
$description = (!empty($global['description'])) ? $global['description'] : $global['site.description'];
$noindex = in_array($global['route'], ['tag', 'category', 'archives']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<?php $title = (isset($global['title'])) ? $global['title'] : $global['site.title']; ?>
	<title><?php echo $title .' | '. $global['site.name']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="<?php echo $global['author.name']; ?>">
	<meta name="description" content="<?php echo $description; ?>">
    <?php if ($noindex === true): ?>
    <meta name="robots" content="noindex,follow" />
    <?php endif; ?>
	<link rel="alternate" type="application/rss+xml" title="" href="<?php echo $global['assets.prefix'];?>/feed">
	<link href="<?php echo $global['assets.prefix'];?>/themes/nekudo/css/nekudo_blog.css?v=20151004" rel="stylesheet">
	<link href="<?php echo $global['assets.prefix'];?>/themes/nekudo/css/prism.css" rel="stylesheet">
</head>
<body>
<nav class="nekudo-nav">
	<ul>
		<li class="first"><a href="/">nekudo.com</a></li>
		<li<?php if($active === 'blog'): ?> class="active"<?php endif; ?>><a href="/blog/">Blog</a></li>
        <li<?php if($active === 'imprint'): ?> class="active"<?php endif; ?>><a href="/blog/imprint">Imprint</a></li>
		<li><a href="https://github.com/nekudo">Github</a></li>
        <li class="last"><a href="https://twitter.com/lemmingzshadow">Twitter</a></li>
	</ul>
</nav>

<div class="container">
	<div class="nekudo-content">
		<?php echo $content; ?>
	</div>
</div>

<footer>
	<p>nekudo.com - keep it simple :)</p>
</footer>

<script src="<?php echo $global['assets.prefix'];?>/themes/nekudo/js/prism.js"></script>
</body>
</html>