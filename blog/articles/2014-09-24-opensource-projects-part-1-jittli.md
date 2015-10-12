{
 "title":"Opensource Projects - Part 1: jitt.li",
 "date" : "2014-09-24",
 "slug" : "opensource-projects-part1-jittli",
 "author" : "Simon",
 "tag" : "php, websockets, redis, twitter",
 "category":"Projects"
}

I'll make it short: Open source is great.

This is why I decided to publish the sourcecode of some projects I did in the past on github. Additonally there will be a blogpost like this one containing some background information regarding the project and the used technology. This way everyone who is interested may learn things, get new ideas or even reuse some of the code.

The first project in this series is: [jitt.li](http://jitt.li) ([Sourcecode at Github](https://github.com/nekudo/jitt.li/))

## What is jitt.li?

jitt.li is a twitterwall. It fetches tweets from the twitter streaming api and displays them in the browser. This happens in realtime using a websocket server.

The first version started as an experiment to test a [PHP based webserver](https://github.com/lemmingzshadow/php-websocket) I was working on. Later I switched to [Ratchet](http://socketo.me/) as websocket server.

## Technical details

### The frontend

The website is based on the [Kohana framework](http://kohanaframework.org/). It uses the default bootstrap theme. The interesting part is the [jitt.js javascript file](https://github.com/nekudo/jitt.li/blob/master/www/js/jitt.js). This file contains all the logic of connecting to the websocket server and updating the twitterwall as soon as new tweets arrive.

### The backend

The main application running on the server is separated into three parts: The websocket application, a tweet-pull script and a tweet-push script. Additionally there is a simple management-script to start/stop/restart the script on the server.

#### Websocket application

As mentioned earlier the Ratchet project is used for the websocket server. In the file ["jittli_server.php"](https://github.com/nekudo/jitt.li/blob/master/cli/jittli_server.php) all the necessary objcts are created and passed to the websocket server which than runs in and endless loop.

The [Twitterwall application](https://github.com/nekudo/jitt.li/blob/master/cli/Jittli/Twitterwall.php) uses Redis to store information like e.g. the currently active walls so this information can be accessed by the pull and push scripts.

#### Tweet pull script <small>([sourcecode](https://github.com/nekudo/jitt.li/blob/master/cli/pull_tweets.php))</small>

The tweet pull script connects to the twitter steaming api using the [phirehose library](https://github.com/fennb/phirehose). It sets the currently active walls (as requested on the website) as filers so only relevant tweets will be received. These tweets are than simply "queued" into a redis list. Every X seconds updates the filters to be set according to the walls currently requested on the website.

#### Tweet push script <small>([sourcecode](https://github.com/nekudo/jitt.li/blob/master/cli/push_tweets.php))</small>

The push script ready tweets from the redis queue prepares the data and pushes them into the websocket server using a ZeroMQ socket. The websocket server than triggers a method in the Twitterwall application so the tweets can be send to the browser. Here the are handled by the javascript mention in the frontend part.

## Summary

When putting it all together this project does the following:

*	On the jitt.li website visitors can "open" new twitterwalls (stored in redis).
*	The pull-script uses these walls as filters for the Twitter streaming api. Received tweets are stored into a redis queue.
*	The push-script reads tweets from the queue and pushes them into the websocket server. (ZeroMQ)
*	The websocket server triggers the Twitterwall application which sends the data to the browser (using the websocket connection).
*	The javascript loaded in the browser displays new tweets received from the websocket server.

Got questions? Feel free to [open an issue](https://github.com/nekudo/jitt.li/issues) at the Github repository.