#!/usr/bin/env bash

until cd /var/www/symfony/docker/ && ./setup-docker.sh
do
    echo "Retrying install"
done

php-fpm -F
