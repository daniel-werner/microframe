<?php
use PHPUnit\Framework\TestCase;
use Core\Dispatcher;
use Core\View;

class DispatcherTest extends TestCase
{
    public function testDispatch()
    {
        $_SERVER['REQUEST_URI'] = '/test/new';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $view = Dispatcher::dispatch();

        $this->assertInstanceOf( View::class, $view );

    }
}