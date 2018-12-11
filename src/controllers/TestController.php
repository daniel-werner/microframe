<?php

namespace Controllers;

use Core\View;
use Models\Quote;
use Models\QuoteItem;

class TestController extends Controller
{
    public function add($params){
        return new View('test/add', ['data' => 'test']);
    }

    public function store($params){
        return new View('test/add', ['data' => 'test']);
    }
}