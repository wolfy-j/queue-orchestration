## Cloning

```bash
cd ${S2S_PATH}
git clone https://github.com/stock2shop/queue-orchestration.git
```

## Docker

```bash
cd ${S2S_PATH}/queue-orchestration
cp .env.sample .env # Set APP_DIR for path to your clone
cp ~/.ssh/id_rsa.pub ${S2S_PATH}/queue-orchestration/id_rsa.pub
docker-compose up
```

## Install

SSH into `s2s-worker` container
```bash
ssh -p 2223 root@localhost
cd /mnt/app
```

Run application
```bash
# ./rr serve -v -d
# ./rr serve -d
./rr serve
```

**TODO** Errors
```
2022-07-27T17:29:41.789Z	INFO	server      	 [Cycle\Database\Exception\StatementException]
 SQLSTATE[HY000] [1049] Unknown database 'what_must_this_be'
in /mnt/app/vendor/cycle/database/src/Driver/MySQL/MySQLDriver.php:56

2022-07-27T17:29:41.801Z	ERROR	server      	process wait	{"error": "exit status 255"}
handle_serve_command: Serve error:
	endure_start:
	endure_serve_internal: Function call error:
	endure_call_serve_fn: got initial serve error from the Vertex roadrunner_temporal.Plugin, stopping execution, error: temporal_plugin_serve: failed reaching server: last connection error: connection error: desc = "transport: Error while dialing dial tcp 127.0.0.1:7233: connect: connection refused"
```

Tests
```bash
./vendor/bin/phpunit
```

