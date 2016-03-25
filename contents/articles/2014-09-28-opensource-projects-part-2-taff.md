{
	"title":"Opensource Projects - Part 2: Taff",
	"date" : "2014-09-28",
	"slug" : "opensource-projects-part2-taff",
	"author" : "Simon",	
	"categories":"News"
}

::METAEND::

Taff is a shortcut for _Twitter automatic friend finder_.

The sourcecode can be found in this **[Github repository](https://github.com/nekudo/taff/)**
<!--more-->

At first: This is a really old project - it is at least 4 years old so it most probably won't work out of the box. Nevertheless I wanted to mention it in this series as such script are still in use.

The purpose of this little project was to automatically generate twitter follower for one or more accounts. This mainly happens by following people due to the fact the lots of them will follow you back. Another part of this project was to put some content into the managed twitter accounts. This happens by "recommanding" links.

## Technical details

### The follower creator <small>[cron.scan_twitter.php](https://github.com/nekudo/taff/blob/master/crons/cron.scan_twitter.php)</small>

This script is responsible for the creation of new followers. I should be periodically called by a cronjob.

Follower creation is done by scanning the twitter public timeline for tweets containig given keywords. If the tweet also is in a matching language the user will be followed. Lots of users will than follow you back given that your account has some interesting tweets in it. If the user does not follow you back within 24 hours he is unfollowed again and will be blacklisted. This ensures the script wont send any new "follow requests" to this user.

### The content generator <small>[cron.feed_twitter.php](https://github.com/nekudo/taff/blob/master/crons/cron.feed_twitter.php)</small>

This script should also be executed periodically using a cronjob. I is responsible for creating tweets in your managed twitter accounts.

To generate tweets it uses a given list of RSS feeds and keywords. This feeds are periodically scanned for new articles containig the given keywords. If an article conatains one of the keywords the links is tweeted and "recommanded" to your followers. The keywords used to find articles are the same as the ones which are used to follow users. This ensures the content you recommend is matching the interests of you followers.

## Conclusion

As mentioned above - this script is really old and relatively basic. But it gives you a basic idea of how automatic follower creation on twitter works.

Now you know why you got so many new followers the last time you tweeted "iphone" ;)

Got questions? Feel free to [create a new issue](https://github.com/nekudo/taff/issues/new) in the Github repository.

_Happy hacking..._
