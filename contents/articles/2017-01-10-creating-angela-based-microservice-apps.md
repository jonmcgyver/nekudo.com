{
    "title": "Building microservice based PHP applications using Angela",
    "metaTitle": "Building PHP microservices using Angela worker framework",
    "description": "This tutorial explains how to easily build a PHP mircroservice application using the Angela worker framework and job-server.",
    "date": "2017-01-10",
    "slug": "creating-php-microservices-using-angela-framework",
    "author": "Simon",
    "categories": "WebDev,News"
}

::METAEND::

In this article I will explain how you can use the Angela worker-framework to build a microservice application in just a few simple steps.
<!--more-->

## What is Angela?

In short: Angela is a PHP framework to build and run worker processes. It provides a job server to manage your workers
as well as a client to send jobs from your application to those workers. You can read more about the framework itself
on the [Angela Github page](https://github.com/nekudo/Angela).

##Why would I need microservices?

Microservices are a great way to separate big monolithic application into multiple independent smaller services. This
approach has various benefits like e.g. maintainability, reliability, parallelization and much more. If you are interested in the general
concept of microservices there are [lots of great articles](https://www.google.de/search?q=php%20microservices&rct=j) out there.

But it is not necessary to refactor your whole application. You can also use worker-processes for simple tasks like
sending out e-mails, processing images and so on. Task you farm out to a worker can run asynchronously and therefore
massively speed up you project.

## Requirements

Before you can use the Angela framework you'll need a few things. Most important is a webserver which you can access
using SSH as the job-server and worker-processes need to be run in CLI mode.

Besides that you'll need PHP 7 with the following extensions installed:

* [ZMQ PHP extension](http://php.net/manual/en/zmq.requirements.php)
* [ev PHP extension](http://php.net/manual/en/ev.installation.php) (optional but recommended for better performance)

## Installing Angela

The recommended way to install Angela is using composer:

```
composer require nekudo/angela
```

## Setting up your application

After installing Angela you will find a folder _"vendor"_ in you project root which contains all libraries required to use
the Angela framework. Angela comes with a few example files which can be found in _"vendor/nekudo/angela/example"_. To
start a new application I recommend to copy this folder into your project root and rename it to _"app"_. This folder will be the
base of you new application.

### Adjust the configuration

The first step when developing a new Angela based application is to adjust the configuration. To do this open the file
called _"config.php"_ in your app folder. All the configuration values are explained inside the file. In most cases the
default configuration should work. The only part you need to adjust is the part defining your worker-pool. It looks like
this:

```
'pool' => [        
    'pool_a' => [            
        'worker_file' => __DIR__ . '/worker/worker_a.php',            
        'cp_start' => 3,
    ],
],
```

In this case there is one pool defined named _"pool\_a"_. The worker for this pool is named _"worker\_a.php"_ and will
be started 3 times when you fire up the server. This means once you start the job-server there will be 3 workers of type
_worker\_a_ and each of this workers can do one task at the same time. So if you have lots of tasks which can be
handled by _worker\_a_ you can just start more workers and jobs can be processed faster.

## Writing your first worker

After you configured the worker-processes you want to use you'll need to actually implement those workers.

For each worker you defined in the configuration there needs to be a corresponding file containing the code. In this
example the worker can be found in your _app/worker_ folder and is called _worker\_a.php_. The content of a very basic
worker looks like this:

```php
<?php
namespace Nekudo\Angela\Example;
use Nekudo\Angela\Worker;
require_once __DIR__ . '/../../vendor/autoload.php';

class WorkerA extends Worker
{
    public function taskA(string $payload) : string
    {
        // Do some work:
        usleep((rand(2, 5) * 100000));
        
        // Return a response (needs to be string!):
        return $payload . '_completed_by_' . $this->workerId;
    }
}
// Create new worker and register jobs:
$worker = new WorkerA;
$worker->registerJob('taskA', [$worker, 'taskA']);
$worker->run();
```

Each worker needs to extend the Angela _Worker_ class and should contain at least one job-type it can handle.
 
In this example the worker can handle one type of job called _taskA_ which does nothing more than sleep for a short
period of time and than return the payload you sent to the worker and its own id. In a real world application these
files would contain the code to do the actual work you want to farm out of you main application - like e.g. resizing 
images, sending emails, crawling feeds and so on.

## Starting the job server

After you implemented you first worker you can now fire up the job-server using the following command inside your
_app_ folder:
```
php control.php start
```

If everything went okay your server and the worker processes are now running and waiting to complete jobs.
You can check the status of your server by typing:
```
php control.php status
```

This should output something like:
```
------------------------------ SERVER STATUS ------------------------------
Angela Version: 2.0.0
Start time:     2017-01-10 20:24:41
Uptime:         0d 00h 03m 02s
------------------------- WORKER/JOB INFORMATION --------------------------
Job requests total:   0
Current queue length: 0

Active worker per pool:
 + pool_a: 3 

Jobs completed per worker:
 + s1_6760: 0
 + s1_6761: 0
 + s1_6762: 0
```

## Passing jobs to a worker

When your server is up and running you can now pass jobs from you application to the job-server by using the Angela
Client:

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$client = new \Nekudo\Angela\Client;
$client->addServer('tcp://127.0.0.1:5551');
$result = $client->doNormal('taskA', 'some workload');
$client->close();
```

This sort example inits the Angela client, connects to the job-server and sends one job-request of type "taskA"
to the server. It than waits for the result of the job. As soon as a worker has processed this job, the worker
sends back the result of the job to the job-server which than forwards this result back to the client.

This example of course would block your application until the result of the job is received. But in many cases you
don't need an actual result - e.g. if you just want to resize an uploaded image to some predefined sizes. In this case
you can use the _doBackground_ method:

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$client = new \Nekudo\Angela\Client;
$client->addServer('tcp://127.0.0.1:5551');
$jobId = $client->doBackground('taskA', 'some workload');
$client->close();
```

This code will trigger the same job-request as the previous code but it will not wait until the job is completed.
You will just receive a job-id and your application can continue to process code right away. This is extremely useful
if you have some operations running relatively long and you don't want your users to wait for the page to load.
  
##Conclusion

I hope this short example could explain that you can farm out work from you main application into other (background)
processes in a few easy steps. Of course it's always hard to adopt this pattern into an existing application but
you should keep it in mind for you next relaunch ;)