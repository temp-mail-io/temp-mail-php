./vendor/bin/phpunit ./tests

./vendor/bin/phpstan analyze src

PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix src
PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix tests