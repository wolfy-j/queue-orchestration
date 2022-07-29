#!/usr/bin/env bash
set -eu                   # exit on error or undefined variable
bash -c 'set -o pipefail' # return code of first cmd to fail in a pipeline

docker-compose down

echo "Remove docker containers"
docker rm temporal-elasticsearch || :
docker rm temporal-mysql || :
docker rm temporal || :
docker rm temporal-admin-tools || :
docker rm temporal-web || :
docker rm s2s-worker || :

echo "Remove docker images"
docker image rm temporal-elasticsearch || :
docker image rm temporal-mysql || :
docker image rm temporal || :
docker image rm temporal-admin-tools || :
docker image rm temporal-web || :
docker image rm s2s-worker-image || :
