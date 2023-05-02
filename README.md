# Article API

This application represent and incorporate **Article domain** of the system

## Installation
````shell
git clone https://webstik@bitbucket.org/scalewest/article.git
# or if you already clone the repo
git pull
# or
git fetch origin
git reset --hard origin/develop

# if you install the application for the first time skip this step
# start maintenance mode
php artisan down

# install the vendor modules
composer install --optimize-autoloader

# if you install the application for the first time skip this step
# clear cache
php artisan cache:clear

# if you install the application for the first time skip this step
# clear config cache
php artisan config:clear

# run database migrations
php artisan migrate --force --step

# generate api docs
php artisan l5-swagger:generate

# if you install the application for the first time skip this step
# stop maintenance mode
php artisan up
````

If this is the first time to install the application you will need to create a copy of `.env.example` to `.env` and make 
needed environment changes

## Used packages
````shell
https://github.com/DarkaOnLine/L5-Swagger
https://github.com/dingo/api
https://spatie.be/docs/laravel-query-builder/v3/introduction
https://github.com/overtrue/laravel-versionable


https://www.php.net/manual/en/class.ziparchive.php
````
