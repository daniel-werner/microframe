<?php

namespace Controllers;

use Microframe\Controllers\Controller;
use Microframe\Core\View;

class TestController extends Controller
{
    public function add($params)
    {
        
        $this->render(['test/add'], ['data' => 'test']);
    }

    public function store($params)
    {
        $this->render('test/add', ['data' => 'test']);
    }
}
