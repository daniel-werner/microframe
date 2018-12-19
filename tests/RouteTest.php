<?php

use Microframe\Routing\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
     * @covers \Microframe\Routing\Route::match
     */
    public function testMatch()
    {
        $uri = '/test/action/1';
        $route = new Route('GET', '/test/action/{id}', 'TestController@add');
        $this->assertTrue($route->match('GET', $uri));

        $uri = '/test/action/1/1';
        $this->assertFalse($route->match('GET', $uri));

        $uri = '/test/action/1/1';
        $route = new Route('GET', '/test/action/{id}/{other}', 'TestController@add');
        $this->assertTrue($route->match('GET', $uri));

        $uri = '/test/action/1/other';
        $route = new Route('GET', '/test/action/{id}/other', 'TestController@add');
        $this->assertTrue($route->match('GET', $uri));
    }
}
