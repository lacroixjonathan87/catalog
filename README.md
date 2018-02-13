# TalentNet - Catalog

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

We consider the following tools are installed:
* Composer
* MySQL

### Installing

A step by step to get a development env running
* Installs the project dependencies
* Create database
* Populate database
* Run server

In the project directory, run the following commands.
```
> composer install
> php bin/console doctrine:schema:create
> php bin/console app:populate data/seeds/electronic-catalog.json
> php bin/console server:run
```

Api documentation will be available at [http://localhost:8000/api/doc](http://localhost:8000/api/doc)

## Running the tests
```
> ./vendor/phpunit/phpunit/phpunit
```

Warning: Running the tests will empty the database!
