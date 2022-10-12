<?php

use Microframe\Routing\Routes;

Routes::get('/', 'WelcomeController@welcome');
Routes::get('/contact', 'WelcomeController@contact');
Routes::get('/about', 'WelcomeController@about');
Routes::get('/json', 'WelcomeController@json');
