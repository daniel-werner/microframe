<?php
use PHPUnit\Framework\TestCase;
use Microframe\Routing\Router;
use Microframe\Routing\Routes;

class HelpersTest extends TestCase
{

    public function testisActive()
    {
        $_SERVER['REQUEST_URI'] = '/test/param/1';
        $isActive = isActive('/test/param/1');

        $this->assertTrue($isActive);
    }
}