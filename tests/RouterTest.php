<?php

use Microframe\Routing\Router;
use Microframe\Routing\Routes;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        Routes::get('/test/new', 'TestController@add');
        Routes::post('/test/new', 'TestController@store');
        Routes::get('/test/param/{id}', 'TestController@add');
    }

    public function testGetParams()
    {
        $_SERVER['REQUEST_URI'] = '/test/new';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        list($controller, $action) = Router::getParams();

        $this->assertSame($controller, 'TestController');
        $this->assertSame($action, 'add');

        $_SERVER['REQUEST_URI'] = '/test/new';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        list($controller, $action) = Router::getParams();

        $this->assertSame($controller, 'TestController');
        $this->assertSame($action, 'store');
    }

    public function testRouterParams()
    {
        $_SERVER['REQUEST_URI'] = '/test/param/1';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        list($controller, $action, $routeParams) = Router::getParams();

        $this->assertArraySubset(['id' => 1], $routeParams);

        $this->assertSame($controller, 'TestController');
        $this->assertSame($action, 'add');
    }

    public function testRouterActive()
    {
        $_SERVER['REQUEST_URI'] = '/test/param/1';
        $isActive = Router::isActive('/test/param/1');

        $this->assertTrue($isActive);
    }
}
