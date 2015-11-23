# REST Scheduler API

## Installing the library

You will need [Composer](https://getcomposer.org) to install this API library.

use Composer to get things going

```bash
composer install
```

After creating the project, copy the .env.example file to make a .env file and fill in the appropriate configuration fields

```bash
cp .env.example .env
```

Create the database schema by running the following doctrine command

```bash
vendor/bin/doctrine orm:schema-tool:create
```

After the schema has been created in the database, you can seed it with some fake data using the provided seeder

```bash
php fixtures/seed_data.php
```

## Running tests

After getting your project set up, you can run the Codeception tests as follows

```bash
vendor/bin/codecept run
```