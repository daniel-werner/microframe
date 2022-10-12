<?php

use Microframe\Routing\Routes;

Routes::get('/test/new', 'TestController@add');

Routes::post('/test/new', 'TestController@store');
