#!/bin/sh

# Create s2s-worker db if it doesn't exist
mycli -h temporal-mysql -u root -p asdf \
    -e "create database if not exists s2sworker;"
mycli -h temporal-mysql -u root -p asdf \
    -e "show databases;"

# Dependencies
cd /mnt/app || :
# /usr/local/bin/composer install
/usr/local/bin/composer update
php app.php configure
if [ ! -f ./vendor/bin/rr ]; then
    ./vendor/bin/rr get-binary -n
fi
chmod u+x ./rr

# Run RR in tmux session
SERVICE="rr"
CMD="./rr serve"
tmux new-session -d -s "$SERVICE"
tmux send -t "$SERVICE" "$CMD" ENTER

# Attach to rmux session
tmux a -t rr