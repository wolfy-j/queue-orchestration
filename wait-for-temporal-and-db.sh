#!/bin/sh
# wait-for-temporal.sh

set -e

shift
cmd="$@"

until tctl --namespace default namespace describe; do
  >&2 echo "Temporal namespace is unavailable - sleeping"
  sleep 10
done

# todo move somewhere else
>&2 echo "Temporal is up - app db setup"
mycli -h temporal-mysql -u root -p asdf -e "create database if not exists s2sworker;"

# migrate!
php /app/app.php migrate

>&2 echo "Temporal is up - executing command"
exec $cmd
