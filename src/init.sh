#!/bin/bash

echo "Update dependencies..."

./composer.phar install

echo "Update permissions..."

chmod 777 -R storage/logs storage/framework

echo "Starting service"
php-fpm
