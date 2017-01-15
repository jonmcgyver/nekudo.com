{
    "title": "Moving from Nginx to aerys - an experiment",
    "metaTitle": "Building PHP microservices using Angela worker framework",
    "description": "An article about my experiences trying to use the aerys application server instead of nginx for my geolocation API at geoip.nekudo.com.",
    "date": "2017-01-15",
    "slug": "moving-shinygeoip-from-nginx-to-aerys",
    "author": "Simon",
    "categories": "WebDev,News"
}

::METAEND::

In this short article I want to share my experiences with the aerys application framework. I experimentally used the
aerys server at my ShinyGeoip instance over at geoip.nekudo.com.
<!--more-->

## Introduction

The free geolocation API at [geoip.nekudo.com](http://geoip.nekudo.com) currently runs on two servers. Traffic and load
is distributed using simple round-robin DNS. Each server handles between 100 and 150 request per second which you can
see in the two graphs below.

<img src="/images/blog/nginx_requests_server1_2017-01.png" alt="ShinyGeoip Nginx req/s on server 1" title="ShinyGeoip Nginx req/s on server 1" class="centered" />
<br>
<img src="/images/blog/nginx_requests_server2_2017-01.png" alt="ShinyGeoip Nginx req/s on server 2" title="ShinyGeoip Nginx req/s on server 2" class="centered" />

As this API is totally free of charge I of course try to reduce load on the severs as much as possible to keep the
costs low. Because the nginx and php-fpm configuration as well as the PHP code is more or less optimized as much as
possible I decided to try something new.

##PHP application frameworks

In various blog articles (and PHP events) I read about PHP application frameworks which can replace classic webservers
like apache or nginx. These frameworks include there own webserver and are completely written in PHP. Most of them
are asynchronous, multithreaded and include a wide range of features like e.g.: a webserver, websocket-server, ssl
and http2 support and so on. A few of the most interesting frameworks seem to be:

* [aerys](https://github.com/amphp/aerys)
* [Icicle](https://github.com/icicleio/icicle)
* [appserver.io](https://github.com/appserver-io/appserver)
* [Workerman](https://github.com/walkor/Workerman)

There are may more similar projects. If you are interested in this topic I recommend [Asynchronous PHP](https://github.com/elazar/asynchronous-php)
which is a collection of interesting projects related to asynchronous programming in PHP. 

##Switching ShinyGeoip to aerys

I decided to give aerys a shot - simply cause it seems to be actively maintained and is relatively easy to start with.

Converting a small project like ShinyGeoip to the application server was not that hard and you can see the result at
[this Github repository](https://github.com/nekudo/shiny_geoip_aerys) if you are interested.

The most important thing when switching from a classic webserver to a PHP based application server is to realize that
your application does not get initialized with every request but only once when the server is started. After that it only
needs to handle the incoming requests. This of course can save lots of resources.

Unfortunately in my experiment I could not improve the performance of the ShinyGeoip API using aerys. I manged to
let the application handle the same amount of requests than the nginx server while not increasing the CPU load too
much - but the aerys sever consumed much more memory. This resulted in an overall higher load on the server handling
the same amount of requests.

## Conclusion

Of course this was only my first experiment using a PHP based application server and I can imagine multiple reasons
why I did not perform as good as my classical nginx/php-fpm setup:

* Maybe there is a memory leak in my application.
* Maybe there is a memory leak in the aerys application.
* Maybe the use-case is to "special"  to benefit from the advantages of a PHP based application server. After all the
ShinyGeoip application is relatively small but handles a lot of single requests. I could imagine a much bigger application
is more suitable for this kind of server as the overhead of initializing a large application with every request can be
reduced much more efficiently in this case.

But all in all I must admit that this kind of application is really interesting and I was surprised how powerful
and stable those application server already are. I will definitely give it another try with another kind of
application.