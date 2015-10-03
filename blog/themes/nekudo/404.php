<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<?php $title = (isset($global['title'])) ? $global['title'] : $global['site.title']; ?>
	<title><?php echo $title .' | '. $global['site.name']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="<?php echo $global['author.name']; ?>">
	<meta name="description" content="<?php echo $global['site.description']; ?>">
	<link href="<?php echo $global['assets.prefix'];?>/themes/nekudo/css/nekudo_blog.css" rel="stylesheet">
</head>
<body>

<nav class="nekudo-nav">
	<ul>
		<li class="first"><a href="/">nekudo.com</a></li>
		<li class="active"><a href="/blog/">Blog</a></li>
		<li class="last"><a href="https://github.com/nekudo">Github</a></li>
	</ul>
</nav>

<div class="container">
	<div class="nekudo-content">
		<h1>:(</h1>
		<p>Page not found.</p>
	</div>
</div>

<footer>
	<p>nekudo.com - keep it simple :)</p>
</footer>

</body>
</html>