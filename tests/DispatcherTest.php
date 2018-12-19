<?php

use Microframe\Core\Dispatcher;
use Microframe\Core\View;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    public function testDispatch()
    {
        $_SERVER['REQUEST_URI'] = '/test/new';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $view = Dispatcher::dispatch();

        $this->assertInstanceOf(View::class, $view);
    }
}
