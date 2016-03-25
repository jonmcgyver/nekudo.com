{
	"title":"New Project: ShinyGeoip",
	"metaTitle":"New Project: ShinyGeoip",
    "description": "Announcing geoip.nekudo.com - a free geolocation API.",
	"date" : "2014-12-20",
	"slug" : "new-project-shiny-geoip",
	"author" : "Simon",	
	"categories":"News"
}

::METAEND::

A few days ago I released a new project called __ShinyGeoip__.

It is a HTTP API for IP geolocation lookups. You can simply call the API with any valid IPv4 or IPv6 IP address
and will get the location information as JSON encoded string. The data is provided by the
[Maxmind GeoLite2 database](http://dev.maxmind.com/geoip/geoip2/geolite2/).
<!--more-->

Here is an example:
```
http://geoip.nekudo.com/api/87.79.99.25
```

The API at [http://geoip.nekudo.com](http://geoip.nekudo.com) can be used free of charge but it is also possible to
setup your own copy of this API. The sourcecode and install instructions are available on Github under an MIT license.

Feel free to use the API, contribute to the code on github or fork your own project.
