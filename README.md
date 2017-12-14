# Goo.gl Mass URL Shortener

## Installation

``git clone https://github.com/lundtropic/url.git
mv .env.example .env
touch database/database.sqlite
composer install && npm install
php artisan key:generate
php artisan migrate --seed``

### .env

If you would like to use user credentials other than those defined in `config/seed.php`, define those in the environment file.

## Purpose

This application is meant to take a set of URLs and generate goo.gl shortened URLs connected to a defined Google account.


## Major Dependencies

 - PHP 7.0
 - SQLite
 - Laravel 5.5
 - VueJS 2
 - Bootstrap 3
