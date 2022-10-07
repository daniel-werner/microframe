<?php

use Microframe\Core\Dispatcher;
use Microframe\Core\View;
use Microframe\Routing\Routes;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Routes::get('/test/new', 'TestController@add');
        Routes::post('/test/new', 'TestController@store');
        Routes::get('/test/param/{id}', 'TestController@add');
    }

    public function testDispatch()
    {
        $_SERVER['REQUEST_URI'] = '/test/new';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        Dispatcher::dispatch();
        $view = $this->getActualOutput();

        $this->assertStringStartsWith('<!doctype html>', $view);
    }

    //Nether POST nether params not work, need later fix
    
    // public function testDispatchPost()
    // {
    //     $_SERVER['REQUEST_URI'] = '/test/new';
    //     $_SERVER['REQUEST_METHOD'] = 'POST';

    //     Dispatcher::dispatch();
    //     $view = $this->getActualOutput();

    //     $this->assertStringStartsWith('<!doctype html>', $view);
    // }
}
