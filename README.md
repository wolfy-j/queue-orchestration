<p align="center">
<img src="https://user-images.githubusercontent.com/2461257/112313394-d926c580-8cb8-11eb-84ea-717df4e4d167.png" width="400" alt="Spiral Framework">
</p>

# Queue Orchestration POC

This repository demonstrates queue balancing and orchestration using RoadRunner queue API and Temporal server as
orchestration platform. Demo is not intended for production usage.

Server Requirements
--------
Make sure that your server is configured with following PHP version and extensions:

* PHP 8.1+, 64bit
* *mb-string* extension
* PDO Extension with desired database drivers
* MySQL database (or any alternative)
* Temporal Server

Pre-Installations
--------

- Make sure you have a running instance of MySQL, Postgres or etc (this demo can not work with SQLite)
- Install Temporal Server (see an official docker - https://github.com/temporalio/docker-compose)

Installation
--------
Clone the repository.

```
$ git clone git@github.com:wolfy-j/queue-orchestration.git
```

Install dependencies:

```
$ composer install
$ vendor/bin/rr get
```

Configure your env:

```
$ cp .env.sample .env
```

> Make sure to update .env with your database credentials!

Configure application and database.

```
$ php app.php migrate
$ php app.php configure

```

> Application server will be downloaded automatically (`php-curl` and `php-zip` required).

Once the application is installed you can ensure that it was configured properly by executing:

```
$ php ./app.php configure
```

> Additionally, you can tweak configuration options in `.rr.yaml`.


Starting Application
-------
To start application server execute:

```
$ ./rr serve
```

On Windows:

```
$ rr.exe serve
```

To view realtime statistic about workers and queue:

```
$ ./rr workers -i
```

> We recommend keeping this in separate tab for observability.

Running demo
--------
First you have to start the temporal orchestration workflow:

```
$ php app.php start
```

To push data into named queue group:

```
$ php app.php push {group} {payload} -s {count} 
```

For example (push 1000 messages to bob group):

```
$ php app.php push bob "hello world" -s 1000 
```

> Make sure to push to different groups to observe the behavior.

To flush counts in case of any error:

```
$ php app.php flush
```

How it Works
-------
Demo is fully build Spiral framework, it contains two main parts.

### Queue API
RoadRunner application server exposes low-level API to manipulate with Queue brokers, 
such as `create`, `destroy`, `consume` and `pause`. This application demo uses in-memory queue
provider, but it's capable running on RabbitMQ, Amazon SQS, Beanstalk, Kafka, NATS and etc. 

> You can find API calls in [here](app/src/Activity/RouteActivity.php).

### Workflow
In order to manage the queue application implements [RouteWorkflow](app/src/Workflow/RouteWorkflow.php).

This workflow performs a simple logic of checking the optimal routing configuration every second and then
reconfiguring system according to given configuration.

Current logic pushes all message groups with count > 100 to dedicated queue with lower priority (or prefetch),
the rest processes in the `default` queue.

Feel free to implement your own balancing logic.

License:
--------
MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained
by Wolfy-J.
