## Cloning

```bash
cd ${S2S_PATH}
git clone https://github.com/stock2shop/queue-orchestration.git
```


## Docker

Run temporal and s2s-worker in docker
```bash
cd ${S2S_PATH}/queue-orchestration

# Stop and remove containers and images
./docker-reset.sh

# Copy sample files
cp .env.sample .env # Set APP_DIR for path to your clone
cp ~/.ssh/id_rsa.pub ${S2S_PATH}/queue-orchestration/id_rsa.pub

# Build and start containers in detached mode
docker-compose up -d --build

docker ps
```

Configure and run RoadRunner inside s2s-worker
```bash
ssh -p 2223 root@localhost
/mnt/app/docker-rr.sh
```


## Tests

```bash
ssh -p 2223 root@localhost
cd /mnt/app
./vendor/bin/phpunit # TODO
```

