<?php

namespace Config;


class Routes
{
    public static $get = [
        '/test/new' => 'TestController@add',
    ];

    public static $post = [
        '/test/new' => 'TestController@store',
    ];
}