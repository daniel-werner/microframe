# Microframe

[![Build Status](https://travis-ci.com/daniel-werner/microframe.svg?branch=master)](https://travis-ci.com/daniel-werner/microframe)
[![StyleCI](https://github.styleci.io/repos/161361417/shield?branch=master)](https://github.styleci.io/repos/161361417)
## Introduction 
Minimalistic framework for php.

## Installation
1. Make a copy the `config/env.default.php` as `config/env.php`, and set up the database parameters
2. Create the database and run the sql dump: `db/quoter.sql`
3. Set up the web server to route all requests through `index.php`

For example the Nginx config will look like below:
 ```
 location / {
         try_files $uri $uri/ /index.php?$query_string;
     }
 ```

4. Run `composer install` to install PHPUnit

## Core features
#### Routing and request handling
The routes can be set up in `config/Routes.php`, separately for get and post requests.
The Core/Router class is responsible for parsing the url, 
and the Core/Dispatcher class instantiates the controller and invokes the 
corresponding action, passing the get or post parameters as the first argument
to the action method.

#### Views
The Core/View class handles view data, and renders the templates.
The template for the main layout can be found in views/layout/main.php
 
#### Models
The Models/Model class represents the abstraction layer for the database.
Currently it only supports insert and select statements, using only simple equal
conditions in the where clause.

#### Autoload
The application uses the composer autoload function for loading classes 
from vendor files, and also for loading it's own classes.

#### Testing
Tests can be run manually by using PHPUnit: `vendor/bin/phpunit`, 
and Travis CI is used for running test automatically after each push.

## Missing features
#### Validation
The validation of form fields is missing completely... Only the "required" attribute have been
added to some of the inputs.
#### Model enhancements
Add support for update, delete queries, and for more sophisticated select queries.
#### Multiple database drivers
The database abstraction layer could be moved to database drivers, to support
multiple databases (PosgreSQL, MySQL, Oracle, etc...)
#### Error handling
Error handling could be improved in general.


## Requirements
1. PHP7
2. Mysql
3. Web server: Apache or Nginx