## Cloning

```bash
cd ${S2S_PATH}
git clone https://github.com/stock2shop/dynamic-queue-routing-on-temporal.git
```

## Docker

```bash
cd ${S2S_PATH}/dynamic-queue-routing-on-temporal
cp .env.sample .env # Set APP_DIR for path to your clone
cp ~/.ssh/id_rsa.pub ${S2S_PATH}/dynamic-queue-routing-on-temporal/id_rsa.pub
docker-compose up
```

## Install

SSH into `s2s-worker` container
```bash
ssh -p 2223 root@localhost
cd /mnt/app
```

Then run the following commands
**TODO** Add commands below to `Dockerfile`

[Install composer](https://getcomposer.org/download/)
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Configure
```bash
# composer create-project spiral/app # app is already included in the repo
# php app.php encrypt:key -m .env # Is this required?
apt install zip unzip php-zip
php composer.phar install
# php app.php configure -vv
php app.php configure
./vendor/bin/rr get-binary
chmod u+x ./rr
```

Run application
```bash
# ./rr serve -v -d
# ./rr serve -d
./rr serve
```

**TODO** Configuration?
```
# ./rr serve
2022-07-25T16:03:01.174Z	ERROR	server      	process wait	{"error": "signal: killed"}
handle_serve_command: Serve error:
	endure_start:
	endure_serve_internal: Function call error:
	endure_call_serve_fn: got initial serve error from the Vertex roadrunner_temporal.Plugin, stopping execution, error: temporal_plugin_serve: failed reaching server: last connection error: connection error: desc = "transport: Error while dialing dial tcp 127.0.0.1:7233: connect: connection refused"
```

Tests
```bash
./vendor/bin/phpunit
```

