<?php

namespace Controllers;

use Microframe\Controllers\Controller;
use Microframe\Core\View;

class TestController extends Controller
{
    public function add($params){
        return new View('test/add', ['data' => 'test']);
    }

    public function store($params){
        return new View('test/add', ['data' => 'test']);
    }
}