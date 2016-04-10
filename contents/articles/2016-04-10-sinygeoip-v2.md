{
    "title": "ShinyGeoip Version 2.0 - A performance update",
    "metaTitle": "ShinyGeoip Version 2.0 - A performance update",
    "description": "The second version of ShinyGeoip brings massive performance improvements for the geolocation HTTP REST API.",
    "date": "2016-04-10",
    "slug": "shinygeoip-v2-performance-update",
    "author": "Simon",
    "categories": "WebDev,News"
}

::METAEND::

With increasing traffic on my free GeoIp API I decided to take a detailed look onto the application powering the
API to check if performance improvements were possible. In the end the application got the whole nekudo performance
overhauling. This is a detailed report about what i've done.
<!--more-->

## Analyzing the status quo

The first thing I do when analyzing performance of a web application is to check which are the most common request
hitting the server. In this case this was easy almost every request is requesting location data from the REST API like
e.g.:
```
http://geoip.nekudo.com/api/195.14.223.202
```

So I did one of those requests on my local machine and recorded a profile using Xdebug. I highly recommand to do
such a profiling as it is an easy way to find out what exactly is going in on your application. This is the result:

<img src="/images/blog/geoip_v1_profile.jpg" alt="ShinyGeoip V1 profile" title="ShinyGeoip V1 Profile" class="centered" />

The result was kind of shocking: Hundreds of method calls for an extremely simple task. And if you take a closer look
you will notice that most of the executed code is not even from the core application but from the Slim framework,
composer and some vendor libs.

## Making a plan

After analyzing the results I defined some goals for the version 2 sourcecode:

* Drop Slim (or any other) framework.
* Drop composer and autoloading.
* Drop as many vendor libs as possible.
* Reduce complexity of sourcecode to as little methods as possible while still keeping a clean structured codebase.
* __Do not change API or API responses.__

The last point was very important due to the fact that that there many websites using the API of http://geoip.nekudo.com
and I did not want to break their code.
 
## The result

I managed to adjust the sourcecode and achieve all the goals. I dropped Slim and Composer and cut the used vendor libs
down to one. This one luckily is available as PHP extension implemented in C so it's extremely fast. I also refactored
the sourcecode of the core application while sticking to the Action-Domain-Responder pattern.

Here is a profiling of the same request hitting the version 2.0 source:

<img src="/images/blog/geoip_v2_profile.jpg" alt="ShinyGeoip V2 profile" title="ShinyGeoip V2 Profile" class="centered" />

The direct comparison is quiet impressive. There is now only about 30 method calls before delivering the result to the
user. The overall time cost is only about 4% of the time the same request took in version 1.

Of course: This is only a few microseconds but on a "high traffic" website this can make a huge difference as I will
show in the next section.

## The deployment

My free geolocation API at http://geoip.nekudo.com currently runs on two servers. One runs PHP 5.6 the other one is
on PHP 7.0.5. I deployed version 2.0 of ShinyGeoip to both servers today - here are some insights:

<img src="/images/blog/zoidberg_cpu01.jpg" alt="CPU usage on server 1" title="CPU usage on server 1" class="centered"/>
<img src="/images/blog/zoidberg_requests01.jpg" alt="Nginx requests on server 1" title="Requests per second on server 1" class="centered"/>

The two graphs show the CPU usage and requests per second hitting the nginx server. You can clearly see how the CPU
usage goes down after deploying version 2.0 of the software while the request stay at the same rate or even increase.

This was the PHP 5 machine. Here are the same graphs for the PHP 7 machine:

<img src="/images/blog/harold_cpu01.jpg" alt="CPU usage on server 2" title="CPU usage on server 2" class="centered"/>
<img src="/images/blog/harold_requests01.jpg" alt="Nginx requests on server 2" title="Requests per second on server 2" class="centered"/>

It's basically the same result but it seems the effect is slightly higher on PHP 5 than on PHP 7.

## Conclusion

My lesson learned: Optimizing your source-code matters even more on "high traffic" websites. I definitely recommend to 
do a sourcecode-profiling of you application to reveal possibilities of performance improvements.

And (I can't say this enough): It's not always the best solution do develop an application by selecting a framework
and pull in some vendor libraries using composer. I know it's tempting and fast but be careful when it comes to
performance.

Happy hacking :)
