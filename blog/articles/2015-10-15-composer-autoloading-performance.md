{
    "title": "Autoloading performance while using Composer",
    "description": "A short analysis of composers autoloading performance.",
    "date": "2015-10-15",
    "slug": "composer-autoloading-performance",
    "author": "Simon",
    "tag": "php,composer",
    "category": "Webdev"
}

Almost every PHP project these days uses composer to manage its dependencies. One of the advantages is that it manages
the class loading for you. You just need to use the correct namespaces in your sourcecode and the classes will be
automatically loaded. But what about performance?

## Default composer class loading

To understand the "problem" with performance when using composer we need to understand what autoloading means.
I won't get to much into the details cause everybody who is interested can just have a look into the composer source.
It not really hard to understand. In short: Composer analyzes the namespace of a class and then checks if the class
exists at some places. If the class is found it is included.

That looks like this:

<pre><code class="language-php">
private function findFileWithExtension($class, $ext)
    {
        // PSR-4 lookup
        $logicalPathPsr4 = strtr($class, '\\', DIRECTORY_SEPARATOR) . $ext;

        $first = $class[0];
        if (isset($this->prefixLengthsPsr4[$first])) {
            foreach ($this->prefixLengthsPsr4[$first] as $prefix => $length) {
                if (0 === strpos($class, $prefix)) {
                    foreach ($this->prefixDirsPsr4[$prefix] as $dir) {
                        if (file_exists($file = $dir . DIRECTORY_SEPARATOR . substr($logicalPathPsr4, $length))) {
                            return $file;
                        }
                    }
                }
            }
        }
</code></pre>

And here you can see a little problem. As it is not always unambiguously defined where to find a file - the file
needs to be "searched". At this point we waste some time cause it is of cause possible to cleary define where to
find each file.

And fortunately composer offers a solution for this!

## Using optimzed autoloading

After you installed or update something using composer just type:

<pre><code class="language-bash">composer dump-autoload -o</code></pre>

You can find the detailed explanation of this command in the [composer docs](https://getcomposer.org/doc/03-cli.md#dump-autoload).

In short: It creates a class-map defining the path on you server for every possible namespace. This looks like this:

<pre><code class="language-php">
return array(
    'Guzzle\\Batch\\AbstractBatchDecorator' => $vendorDir . '/guzzle/guzzle/src/Guzzle/Batch/AbstractBatchDecorator.php',
    'Guzzle\\Batch\\Batch' => $vendorDir . '/guzzle/guzzle/src/Guzzle/Batch/Batch.php',
    ...
</code></pre>

Using this method we don't need to search for files any longer and should improve performance.

## Performance analysis

Of course I wanted to know if it really makes a difference to use the class-map so I tested this using the
ShinyGeoip project as an example.

<img src="/images/blog/composer_profile_01.jpg" alt="composer profile 1" title="Composer Autoloading Profile" class="centered" />

The first image shows a profiling of an API request to the ShinyGeoip API whith composer in normal mode. The method
_findFileWithExtension_ is called 41 times and consumes 19 percent of the time during this request. As the whole project
is relatively small 19% is quite a lot and it would be nice if we could save some time here.

<img src="/images/blog/composer_profile_02.jpg" alt="composer profile 2" title="Composer Autoloading Profile" class="centered" />

The second image shows the same request but with composer using the classmap-file. The _findFileWithExtension_ method
is not called and at the same point we're already a few steps further processing the code.

### Some numbers

Additionally I crunched some numbers to see if there is any measurable difference when calling the API. So I called
the API 20.000 times in blocks of 1000 requests an measured the times. Here are the results:

```
6.9331228733063 sec
6.0901930332184 sec
6.2068719863892 sec
5.9258079528809 sec
7.7793209552765 sec
7.2444310188293 sec
7.3575570583344 sec
7.2506470680237 sec
7.5955770015717 sec
6.7635560035706 sec
7.3395359516144 sec
6.5393271446228 sec
6.1258180141449 sec
8.2368841171265 sec
5.5511469841003 sec
4.5876572132111 sec
7.8379991054535 sec
6.3960349559784 sec
6.5366761684418 sec
6.5052518844604 sec
Avg. time 6.7401708245277 sec
```

1000 requests with composer in normal mode took about 6.7 seconds in average on my local machine.

```
7.1470701694489 sec
5.9239280223846 sec
6.5201210975647 sec
6.8260641098022 sec
5.9228749275208 sec
5.9340260028839 sec
6.3986730575562 sec
6.1228239536285 sec
6.1717998981476 sec
5.8733088970184 sec
5.7348520755768 sec
6.3911769390106 sec
6.1153709888458 sec
6.3705530166626 sec
5.092787027359 sec
6.2643351554871 sec
6.2573671340942 sec
6.007838010788 sec
6.7526230812073 sec
6.3487119674683 sec
Avg. time 6.2088152766228 sec
```

With composer using the classmap-file the same 1000 request took about 6.2 seconds in average. So there is a
measurable difference using this improvement.

## Final thoughts

As always when doing such a performance analysis: This is micro-optimization and you should only invest time in such
improvements if you can afford it. But my primary goal is to raise the awareness of these little things. Don't just use
tools as they are - try to understand and improve them. And of course: It's always good to have a look into the documentation ;)