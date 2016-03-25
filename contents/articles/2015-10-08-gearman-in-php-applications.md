{
    "title":"Microservices in PHP using Gearman",
    "metaTitle":"Microservices in PHP using Gearman",
    "description": "Tutorial on how to use Gearman to implement and control PHP microservices.",
    "date" : "2015-10-08",
    "slug" : "mircroservices-in-php-using-gearman",
    "author" : "Simon",
    "categories":"WebDev"
}

::METAEND::

As microservices seem to become more and more popular right now I decided to write an article about Gearman. Why?
Because in my opinion Gearman is a great tool to implement the microservices pattern into PHP applications.
Additionally I'm using Gearman in my projects for quite a while now. But let's start from the beginning.
<!--more-->

## What is Gearman?

<img src="/images/blog/gearman-logo.png" alt="gearman logo" title="Gearman" style="float: right; width: 130px" />

Gearman is a framework which allows us to separate single tasks from our application and run them separately - loosly
coupled from the rest of the code. In the microservices pattern such a task would be called a service. Gearman calls
it a worker.

On the other side we have a client. The client creates jobs which are then handled by our workers. When implementing
the microservices pattern the client would probably live in the "gateway" or "application gateway".

Between client and workers we have a job server. The job server basically handles the communication between client
and worker. Every worker is registered at the job server. When the client passes a new job to the job server - it will
find a suitable worker and hand over the job. The worker will then try to finish the job and eventually pass back data
to the client via the job server.

If you need more information on how this works please have a look onto the [Gearman homepage](http://gearman.org/)
which explains the framework very well.

## Why should we use Gearman?

When we start to implement the microservices pattern in our application we need to find solutions to a few tricky
problems. For example: We need a way our application can communicate with out services. This is often done using
a simple HTTP REST API. But what if we want multiple instances of our services working in parallel? What if we want
to distribute the services accross multiple servers? Or even implement a service in another language because it seems
more suitable for the given task? The Gearman Job Server already provides all this features.

Using Gearman we can start as many worker accross as many servers as we like. This makes our application extremely
scalable. If our application generates lots of task for a special service - we just start more workers handling
this task. If our workers consume lots of resources - we just add a new server and fire up some more workers.

Gearman actually offers interfaces for a lot of languages like PHP, C, Go, .NET, Java, NodeJS, ... So we are able to
implement the various services of our application in different languages.

## The basics

Starting to use Gearman in a PHP application is extremely easy. After installing all required dependencies (typically
this would be the Gearman job server and the Gearman PHP extension) we can start to implement our first worker/service:

<pre><code class="language-php">
class WorkerExample
{
    private $gearmanWorker;

    public function  __construct()
    {
        $this->gearmanWorker = new GearmanWorker;
        $this->gearmanWorker->addServer('127.0.0.1', 4730);
        $this->startup();
    }

    private function startup()
    {
        $this->gearmanWorker->addFunction('foobar', [$this, 'foobar']);
        while($this->gearmanWorker->work());
    }

    public function foobar($Job)
    {
        $payload = json_decode($Job->workload());

        // Do some actual work here...
        var_dump($payload);
    }
}

$Worker = new WorkerExample;
</code></pre>

No magic here. We just create a new instance of the GearmanWorker class - which is provided by the PHP extension. Then
we register a new function called "foobar" and wait for new jobs. In this dummy the only thing this function would do
is echoing out the parameters we passed.

This is how we can create a new job to be processed by this worker:

<pre><code class="language-php">
$client = new GearmanClient;
$client->addServer('127.0.0.1', 4730);
$payload['foo'] = 'Hello Service!';
$payload = json_encode($payload);
$client->doBackground('foobar', $payload);
echo "done."
</code></pre>

We create a new instance of the Gearman client and register it at the job server. Than we pass some dummy date to a
worker and start it asynchronously - which means: We won't wait for the job to finish. The string "done." would be
displayed directly after the job was started.

For more code examples and a list of all the different Gearman features please have a look into the [official
documentation at php.net](http://php.net/manual/en/book.gearman.php).

## Pitfalls and possible solutions

As every software Gearman has some pitfalls or things you have to get used to. I'll try to mention an few problems
I ran into during my work with Gearman and explain how I solved them. I hope in this part of the article I can even
address some users who already have some experience with Gearman.

### Addressing special workers

One of the first things we will notice when starting multiple instances of a worker is that there is (by default) no
way to address a special worker. Let's assume we have a worker responsible for creating thumbs in various formats
from a given image. If we fire up 3 workers of this type there is (by design) no way to address one of these three
workers explicitly. As soon as we create a new "resize job" there is no way to control which of the 3 workers is
going to do the job. This behaviour is by design and if there is not good reason we should no change it!

But in some cases it is useful to address one explicit worker (for example to check if the process is still responding)
and here is how I achieve this:

<pre><code class="language-php">
class NamedWorkerExample
{
    private $gearmanWorker;
    private $workerName;

    public function  __construct($workerName)
    {
        $this->workerName = $workerName;
        $this->gearmanWorker = new GearmanWorker;
        $this->gearmanWorker->addServer('127.0.0.1', 4730);
        $this->startup();
    }

    private function startup()
    {
        $this->gearmanWorker->addFunction('foobar', [$this, 'foobar']);

        // This does the trick:
        $this->GearmanWorker->addFunction('ping_' . $this->workerName, [$this, 'ping']);

        while($this->gearmanWorker->work());
    }

    public function foobar($Job)
    {
        $payload = json_decode($Job->workload());

        // Do some actual work here...
        var_dump($payload);
    }

    public function ping($Job)
    {
        $Job->sendData('pong');
    }

    $Worker = new NamedWorkerExample('w1');
}
</code></pre>

I used the same example code like above and added a new method "ping". Additionally a worker name or id is passed into
the worker when creating a new instance. This name is appended to the function name when we register the ping method
as callback to the Gearman server - this is what does the trick. If we now fire up multiple instances of this worker -
but every one with a different name - we are able to address every worker instance separately. Here is an example:

<pre><code class="language-php">
$client = new GearmanClient;
$client->addServer('127.0.0.1', 4730);
$start = microtime(true);
$pong = $client->doHigh('ping_w1', 'ping');
$pingtime = microtime(true) - $start;
</code></pre>

This will explicitly "call" the ping method in the worker with the name "w1".


### Monitoring workers

<img src="/images/blog/worker-monitoring.png" alt="worker monitoring sketch" title="Worker Monitoring" style="width: 300px; float: right;" />

One of the trickiest parts when working with Gearman is monitoring the workers. When implementing long running
scripts in PHP we can be almost sure **they will die at some point**. First thing that comes to mind most probably
is: Why don't we just ping the workers periodically and kill/restart the processes that do not respond?

With the possibility to address special workers like mentioned above this seems like a good idea - but unfortunately
it won't work. Here is why: Every worker can only execute one job at a time. Let's assume our workers main job is
the resizing of images. Additionally we implement a ping method. Every 5 minutes we ping each of our workers to see if
it is still responding - if not we kill and restart the process. But what if the worker is just doing a regular job?
It would reply to the ping request - but not before it is done resizing the images. So if we would set our ping timeout
to e.g. 5 seconds but the resizing of an image would take 10 seconds our script would kill the worker process
although it was totally okay. So this is not a good solution.

Here is my solution for monitoring worker processes:

1. I always start more workers than actually needed. So if one process dies there are still some other workers
which can process the jobs and the application remains functional.

2. Every worker creates a PID file when started. This is just a file which can explicitly be assigned to
the process by e.g. using the worker name as filename.

3. Worker processes periodically write the current timestamp to this pid file.

4. I use a cronjob to regularly check the pid files. If a timestamp is greater then a defined timeout the corresponding
process is killed and restarted. It is important that the timeout is greater then the jobs usually take so we can make
sure not to kill processes that are busy but still responding.

*Hint: There will be code samples implementing this solution in the next part of this article.*


### Managing worker processes

As soon as our projects grows and requires more than 1-2 workers we need to think about a way to manage the worker
processes. We don't want to start, restart or stop them one by one on the console.

Unfortunately I can't provide PHP code which will work out out the box with every project but nevertheless I will at
least present and explain my current solution. It consists of two parts:

* A simple cli script to start/stop worker processes. ([Sourcecode at GitHub](https://github.com/nekudo/shiny_deploy/blob/master/cli/cli.worker.php))
* A WorkerManager class with contain the actual code to mange the processes. ([Sourcecode at GitHub](https://github.com/nekudo/shiny_deploy/blob/master/src/ShinyDeploy/Core/WorkerManager.php))

In a config file I define how many workers of each type/service I want to start:

```
'workerScripts' => [
    'deployer' => [
        'filename' => 'worker.deployer.php',
        'instances' => 3,
    ],
    'repoactions' => [
        'filename' => 'worker.repo_actions.php',
        'instances' => 3,
    ],
]
```

For example this configuration would start 3 "deployer" and 3 "repo_action" workers.

In the manager I mainly implemented 3 methods which can start, stop and "keepalive" the worker processes.
Start and stop should be self-explanatory. The keepalive method is periodically triggered by a cronjob. It checks if
the workers are alive and restarts them if necessary using the monitoring methods described above.

## Conclusion

In my opinion Gearman is a powerful tool to implement the mircoservies pattern into a PHP application. It allows
us to pass jobs from our application to the single services or workers. Additionally provides an easy solution
when it comes to scaling. We can just fire up new services across multiple servers without changing a single line of
code.

I definitely recommend to give it a try! Happy hacking.
