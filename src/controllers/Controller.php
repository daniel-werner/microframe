<?php

namespace Controllers;

abstract class Controller
{
    public function redirect($uri)
    {
        header(sprintf('Location: %s', $uri));
    }

    public function notFound()
    {
        header("HTTP/1.0 404 Not Found");
        echo 'Page not found!';
        exit;
    }
}