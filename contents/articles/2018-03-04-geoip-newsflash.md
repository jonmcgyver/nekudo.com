{
    "title": "ShinyGeoip Newsflash: SSL, Performance, Servers and more",
    "metaTitle": "ShinyGeoip Newsflash: SSL, Performace, Servers and more",
    "description": "Short technical update on my Geolocation API. Including SSL performace, Server updates, Statistics and other project related infos.",
    "date": "2018-03-04",
    "slug": "shinygeoip-newsflash-ssl-performance-servers",
    "author": "Simon",
    "categories": "News"
}

::METAEND::

It's been a while since I published some technical information about my Geolocation API. So in this article I want to
provide some information about the current state of the projects and talk about some of the problems I had to deal
with during the last year (or so).

<!--more-->

## Server performance

### SSL

In February 2016 I first enabled SSL at geoip.nekudo.com. Since then SSL traffic has increased a lot - especially since
the rise of LetsEncrypt and the latest Google Chrome updates.

Unfortunately (for this project) an https request uses a lot more CPU time on the webserver than a regular http
request. Also the key-length of the certificates private key has a significant impact on server performance. Here
you can find a nice [article on SSL performance](https://blog.nytsoi.net/2015/11/02/nginx-https-performance) and
why it may be a better choice to go for a 2048 bit key if security is not your primary concern.

### Meltdown and Spectre

Then of course there where Meltdown and Spectre which [may or may not](https://twitter.com/chanian/status/949457156071288833)
have an impact on your servers CPU usage. It probably depends on your setup but it's not totally unlikely that there
is also some increase in CPU usage caused by this patches.

## Traffic report

The geoip.nekudo.com API currently runs on two servers with an identical setup. Here are the traffic graph showing
the overall traffic on the eth0 interface as well as the requests per second hitting the Nginx webserver during the
last 24 hours.

<img src="/images/blog/geoip_traffic_s1_2018-03-04.png" alt="Traffic on server 1" title="Traffic on server 1" class="centered" />
<br>
<img src="/images/blog/geoip_reqs_s1_2018-03-04.png" alt="Req/s on server 1" title="Req/s on server 1" class="centered" /> 

Since the traffic is distributed evenly between the two servers the graphs from the second server look more or less the
same.

As you can see the API currently handles a daily average of about 600 req/s per second. 

### Traffic peaks

Of course there will always be traffic peaks like when a new big websites experiments with the API or one of the
websites using the API experiences a DDoS attack. This is why I always try to have sufficient resources to handle
a least double the usual traffic.

## Server updates

With a general increase in traffic as well as an increase in SSL traffic in special it is not surprising that I needed
to update the severs a few times during the last year(s). 

Most recently I switched from 3 smaller severs - to two severs with more CPU power to be able to serve all requests 
as quickly as possible. I run the API on at least two servers in different datacenters at all times. So in case of a
problem I can keep downtimes to a minimum.

Of course all sever-updates are (generally ;)) applied without any downtime at all.

## Donations

Last but not least I added a "Donate" link to the ShinyGeoip project page. In case you want to help to pay for the
servers feel free to consider a small donation. Of course the usage of the API will remain free and without any
limitations either way.

If you have any additional questions feel free to [contact me](/contact).    