# TalentNet - Catalog

## Requirements
* Composer
* MySQL

## Installs the project dependencies
`> composer install`

## Create database
`> php bin/console doctrine:schema:create`
    
## Populate database
`> php bin/console app:populate data/seeds/electronic-catalog.json`

## Run server
`> php bin/console server:run`

## ApiDoc
[http://localhost:8000/api/doc](http://localhost:8000/api/doc)

## Run tests
`> ./vendor/phpunit/phpunit/phpunit`
Warning: Empty the database!
