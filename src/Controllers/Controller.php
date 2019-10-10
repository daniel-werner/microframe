<?php

namespace Microframe\Controllers;

use Microframe\Core\View;

abstract class Controller
{
    protected $layout = 'layout/main';

    public function redirect($uri)
    {
        header(sprintf('Location: %s', $uri));
    }

    public function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        echo 'Page not found!';
        exit;
    }

    public function render($template, $data = null)
    {
        $view = new View(
            [
            $this->layout,
            $template,
            ],
            $data
        );

        return $view->render();
    }
}
