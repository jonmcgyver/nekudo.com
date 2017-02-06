{
    "title": "Using LetsEncrypt in a multi-server environment",
    "metaTitle": "Using LetsEncrypt in a multi-server environment",
    "description": "A short article on how to use SSL certificates from Let's Encrypt in a multi-server environment with multiple DNS A-records.",
    "date": "2017-02-05",
    "slug": "letsencrypt-in-a-multiserver-environment",
    "author": "Simon",
    "categories": "WebDev"
}

::METAEND::

There are plenty of tutorials out there covering the default LetsEncrypt setup for only a few domains
running on a single server - but when it comes to more special setups including multiple servers there is not
much information. This is my solution for using LetsEncrypt within a multi-server setup.

<!--more-->

## The problem

Most probably my setup is relatively common: I host multiple projects on multiple servers using multiple domains.
One _kind of special_ case is a [round-robin DNS](https://en.wikipedia.org/wiki/Round-robin_DNS) setup where the
traffic is distributed to the servers using multiple A-records in the domains DNS record.

When using one of the [LetsEncrypt ACME Clients](https://letsencrypt.org/docs/client-options/) you will probably face
some problems - at least I did ;).

###Problem 1: Dependencies

Depending on which ACME client implementation you chose it has various dependencies like e.g. Python, Node.js, ...
If you use multiple server you most probably don't want to install those dependencies on each of your machines.

###Problem 2: ACME domain validation

When generating an SSL certificates using the ACME protocol you have to verify your domain(s). This is in most cases
done using a challenge-response mechanism during which a file containing a token is placed on your webserver which is than
requested by LetsEncrypt via a HTTP request. E.g. if you want to create an SSL certificate for the domain _example.com_
LetsEncrypt will do an http-request to: ```http://example.com/.well-known/acme-challenge/some-random-file```

The problem with that method is that most clients only place the _authorization file_ on the server they currently run
on - which means you will get in trouble as soon as you use multiple servers.

An additional problem occurs in some load-balancing cases - like e.g. my round-robin DNS setup. In this case you will
never know which server will handle the request.

## My solution

<small>Just a heads up: This solution works for me. It does not mean is perfect or will work for you.</small>
 
I decided to use only one central server which handles the certificate generation for all domains and servers. This
way I only need to maintain an ACME client on one machine. Additionally I only need to install dependencies (if any)
on this one machine.

###Redirecting domain validation requests

Using only one server to create SSL certificates means this server needs to handle all the domain-validation requests.
Luckily the ACME protocol seems to accept 302 redirects. So I simply created a subdomain (e.g. acme.yourdomain.com)
pointing to the _SSL handling server_ and redirected all validation-requests from the various projects to this
subdomain using a simple nginx rule on each webserver:

```
rewrite ^/.well-known/acme-challenge/(.*)$ http://acme.yourdomain.com/$1 redirect;
```

After this is was done I could generate SSL certificates for all my domains and servers from one machine and than
distribute the certificates to their destination machines using simple SCP. Of course you could even write a simple
script automating the distribution process - which will be my next step.

Hopefully this short article can prevent some of you from slamming your head onto the keyboard ;)


