{
    "title": "ShinyBlog - A markdown based Blog/CMS",
    "metaTitle": "ShinyBlog - A markdown based Blog/CMS application",
    "description": "ShinyBlog is a lightweight, simple, markdown based Blog/CMS application written in PHP. It's free and open source.",
    "date": "2016-03-27",
    "slug": "shinyblog-markdown-based-blog-cms",
    "author": "Simon",
    "categories": "WebDev,News"
}

::METAEND::

A few days ago I completed my work on a new project and it is now available for
[download at GitHub](https://github.com/nekudo/shiny_blog). __ShinyBlog__ is a lightweight Blog/CMS application entirely based
on markdown files. Of course the software is free and open-source.
<!--more-->

## Motivation

Until recently this website was based on [TextPress](http://textpress.shameerc.com/) which also is a markdown based blog
engine. Unfortunately the software does not seem to be maintained any longer and was missing some features so I decided
to build an alternative. Of course there is lots of other Blog/CMS applications out there like. e.g Wordpress or Grav.
But most of these applications are much to bloated for a simple blog like this one.

This is why ShinyBlog is super lightweight. It was developed with simplicity in mind. There is no composer, no install-script
and very few vendor-libraries were used. This way the code stays extremely simple and clean which makes the software
extremely low maintenance and secure. It (hopefully) simply works. If not: Create an issue at GitHub ;)

## Features

Here are some of the features I implemented:

### Markdown based

The whole system is based on markdown flatfiles. So adding a new artcile to your blog is nothing more than uploading
a new textfile to your server. You can even use your existing deployment system if you like.

### Theme support

The application includes a very basic theme but you can easily build your own. Just copy the existing "kiss" theme
to a new folder and adjust the CSS and/or HTML. Themes can be swiched using a simple config file.

### RSS Feeds

All articles can be displayed within an RSS feed. Additionally there are RSS feeds for each article category. This
way visitors can filter articles of a special category in there RSS reader.

### SEO support

There are some basic SEO settings available. You can define title and meta-descriptions for each article/page. Additionally
you can define noindex/follow stuff via config file.

### Misc

* __Pagination:__ Set how many articles you want to show per page.
* __XML sitemap:__ Sitemap of all articles and pages is automatically generated.
* __Excerpts:__ Using a _read-more_ tag in your articles you can define the excerpt to appear on the blog-page.
* __Clean Code:__ Well documented, PSR-0 and PSR-2 compatible PHP code.

So if you are interested in an extremely simple blog engine like you see it right now on this website you should check out
[ShinyBlog](https://github.com/nekudo/shiny_blog). Have Fun.
