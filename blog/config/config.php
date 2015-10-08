<?php
return array(
	'site.name'  		=> 'nekudo.com',   // Site name (Global)
	'site.title' 		=> 'Project news and general blog',  // Site default title (Global)
	'site.description' 	=> 'nekudo.com project news and other webdev related articles.',  // Site default description (Global)
	'author.name'	 	=> 'Simon', // Global author name
	'article.path'		=> './articles',      // Path to articles
	'date.format' 		=> 'Y-m-d',   // Date format to be used in article page (not for routes)
	'themes.path' 		=> './themes',  // Path to templates
	'active.theme'  	=> 'nekudo',  // Current active template
	'layout.file' 		=> 'layout',    // Site layout file
	'file.extension' 	=> '.md',   // file extension of articles
	'disqus.username' 	=> '',   // Your disqus username or false (Global)
	'markdown'			=> true, //Enable of disable markdown parsing.
	'assets.prefix' 	=> '/blog', // prefix to be added with assets files
	'prefix' 			=> '',   // prefix to be added with all URLs (not to assets). eg : '/blog'
	'google.analytics' 	=> false, // Google analytics code. set false to disable
	'cache' => array(
		'enabled' 	=> false, // Enable/Disable cache
		'expiry' 	=> 24, // Cache expiry, in hours. -1 for no expiry
		'path' 		=> './cache'
	),
	// Define routes
	'routes' => array(
		// Site root
		'__root__'	=> array(
			'route' 	=> '/',
			'template'	=>'index',
			//'layout' 	=> 'layout_home'
		),
		'category' => array(
			'route' 	=> '/category/:category',
			'template' 	=> 'index'
		),
		'tag' 	=> array(
			'route' 	=> '/tag/:tag',
			'template' 	=> 'index'
		),
		'archives' => array(
			'route' 	=> '/archives(/:year(/:month(/:date)))',
			'template' 	=> 'archives',
			'conditions'=> array(
				'year' 		=> '(19|20)\d\d',
				'month'		=>'([1-9]|[01][0-2])'
			)
		),
		'about' => array(
			'route' 	=> '/imprint',
			'template' 	=> 'imprint'
		),
		'rss' 	=> array(
			'route' 	=> '/feed(.xml)',
			'template' 	=> 'rss',
			'layout' 	=> false,
		),
		'sitemap' => array(
			'route' 	=> '/sitemap.xml',
			'template' 	=> 'sitemap',
			'layout' 	=> false,
		),
		'article' => array(
			'route' 	=> '/:article',
			'template'	=>'article',
		),
	),
);
