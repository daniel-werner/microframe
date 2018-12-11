<?php
use PHPUnit\Framework\TestCase;
use Microframe\Core\Router;

class RouterTest extends TestCase
{
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
}