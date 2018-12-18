<?php
use PHPUnit\Framework\TestCase;
use Microframe\Core\Dispatcher;
use Microframe\Core\View;
use Microframe\Routing\Routes;

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

        $view = Dispatcher::dispatch();

        $this->assertInstanceOf( View::class, $view );

    }

    public function testDispatchWithParams()
    {
        $_SERVER['REQUEST_URI'] = '/test/param/1';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $view = Dispatcher::dispatch();

        $this->assertInstanceOf( View::class, $view );

    }
}