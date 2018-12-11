<?php

use Microframe\Config\Routes;

Routes::get('/test/new', 'TestController@add');

Routes::post('/test/new', 'TestController@store');

?>