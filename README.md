# Supermetrics assignment

Assignment was develop without any 3-rd party library usage, in real life some solutions definitely should be picked up from existing libraries.

## How to run:
All actions run from project directory
### With docker-composer:
`docker-compose build`

`docker-compose up -d`

wait until vendor folder will be initialised then

`docker-compose run php-cli bin/statistic`
### Locally
`composer install`

`php bin/statistic`

## How to run unit tests:
`docker-compose run php-cli vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests`
