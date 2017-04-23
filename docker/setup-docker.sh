#!/bin/bash

cd /var/www/symfony/
npm install
./node_modules/webpack/bin/webpack.js -p
source conf/insert-configs-docker.sh
php7.1 ./composer.phar install
php7.1 app/console run-script build-parameters
php7.1 app/console doctrine:database:create
php7.1 app/console doctrine:schema:update --force
php7.1 app/console cache:clear --env=prod
rm -rf app/cache/*
