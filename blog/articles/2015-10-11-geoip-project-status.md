{
    "title":"ShinyGeoip project status",
    "description": "A 10 month after launch review of geoip.nekudo.com",
    "date" : "2015-10-11",
    "slug" : "shinygeoip-project-status",
    "author" : "Simon",
    "tag" : "geoip",
    "category":"Projects"
}

About 10 month ago I released [ShinyGeoip](https://github.com/nekudo/shiny_geoip) and started the free geolocation API
at [geoip.nekudo.com](http://geoip.nekudo.com) so I guess it's about time for a quick status update.

The code seems to be quite stable as I had to fix only minor bugs during the last 10 month. For performance reasons I
decided to kick the Basscss toolkit and replace with some own lines of CSS to reduce the overhead when loading the
homepage.

## API statistics

<img src="/images/blog/geoip-stats-2015-10.png" alt="geoip.nekudo.com API stats" title="geip.nekudo.com API stats" class="centered" />

In the graph you can see the Nginx requests per second from the last month. The API currently handles about
5-10 requests per second in average. That's about 600.000 requests per day. There's still room for more ;)

During peaks there are 250-500 requests per second. But I recognized that these peeks mostly request the same IP
over and over again. This most probably happens when a site using the API get's brute-forced by a bot. To handle this
the nginx configuration only allows 30req/sec from one IP.

So all in all the project runs very well. Let's see where it is going.