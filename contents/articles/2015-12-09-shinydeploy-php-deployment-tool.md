{
    "title": "ShinyDeploy - A deployment tool for web applications",
    "metaTitle": "ShinyDeploy - A deployment tool for web applications",
    "description": "ShinyDeploy is a simple, free, open-source deployment tool written in PHP and JavaScript.",
    "date": "2015-12-09",
    "slug": "shinydeploy-deployment-tool-for-web-applications",
    "author": "Simon",
    "categories": "WebDev,News"
}

::METAEND::

In this article I want to introduce "ShinyDeploy" - a project I have been working on during the last month. As many
features are already implemented and working I decided that it's time to introduce this application to the community
and hopefully find some beta-testers.

<!--more-->

## Introducing ShinyDeploy

ShinyDeploy is a deployment tool for web applications written in PHP and JavaScript. It's supposed to provide an
easy way to deploy files from your GIT repository like GitHub or Bitbucket to your webserver.

You can find the installation instructions at the [projects GitHub page](https://github.com/nekudo/shiny_deploy).

Here is a list of the features and some explanatory texts:

### Basic and straightforward GUI

The application uses a clean and simple user-interface enabling everybody in your team to quickly deploy changes
to your servers.

<div>
    <a href="/images/blog/shinydeploy01.jpg">
        <img src="/images/blog/shinydeploy01_thumb.jpg" alt="Add repository view" style="margin-right:6px;" />
    </a>
    <a href="/images/blog/shinydeploy02.jpg">
        <img src="/images/blog/shinydeploy02_thumb.jpg" alt="Add server view" style="margin-right:6px;" />
    </a>
    <a href="/images/blog/shinydeploy03.jpg">
        <img src="/images/blog/shinydeploy03_thumb.jpg" alt="Create deployment view" style="margin-right:6px;" />
    </a>
    <a href="/images/blog/shinydeploy04.jpg">
        <img src="/images/blog/shinydeploy04_thumb.jpg" alt="Deployments list view" style="margin-right:6px;" />
    </a>
    <a href="/images/blog/shinydeploy05.jpg">
        <img src="/images/blog/shinydeploy05_thumb.jpg" alt="List of changed files" />
    </a>
</div>

### List changes before deploying

If you're not sure about all the changes since the last deploy or you just want to double-check stuff before hitting
the deploy button - you can list all modified files and even view a diff before starting the actual deployment.

### Execute tasks before/after deployment

For every deployment you can define commands that are executed before or after you deploy changes to the server. This
can be stuff like putting your application into maintenance mode, compiling SCSS files after upload, restating software
and so on.

### Realtime feedback using websockets

Using websockets you get direct feedback of what's happens during a deployment. This is especially helpful in situations
when something is not working as expected ;)

### Webhook support

If you like automation you can use webhooks (like provided by GitHub or Bitbucket) to automatically trigger deployments.
Using webhooks whenever you push changes to you repository the will automatically get uploaded to your server. This is
an extremely useful feature to keep your development server up to date.

### Open source and self-hosted

Using a deployment tool automatically means you have to provide sensitive information (like login data to your servers)
to this tool. I personally don't like to provide such information to external services having no insights in how they
handle these data. Using open source self-hosted software means you maintain full control of your data.

### Secure data storage

All sensitive data like usernames, passwords e.g. are encrypted before storing them into database. This provides an
additional layer of security. If an attacker manages to get a copy of your database the data is still encrypted and
can't be used.

### Not too hard to setup

I tried to keep the requirements and installation procedure as simple as possible. Sure it's not like installing
Wordpress but with a little experience in server administration it should be possible.

## Disclaimer

Please be aware that at moment the project is in an early beta stage. It has not been tested by a wide range of
users and most probably still contains some bugs. I use the application at daily basis but for now I don't recommend
to use it in production. Just test it on your development or staging server.

## Contribute!

If you're interested in testing and improving this application you're very welcome. Just start testing and provide some
feedback. Open issues at GitHub or write me a mail. I'm excited to get some feedback.