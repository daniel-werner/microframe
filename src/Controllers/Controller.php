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
        $viewSecvences = $this->parseTemlate($template);

        $view = new View(
            [
                $viewSecvences[0],
                $viewSecvences[1],
            ],
            $data
        );

        return $view->render();
    }

    protected function parseTemlate($template)
    {
        if (is_array($template)) {
            if (count($template) > 1) {
                if (\strpos($template[0], 'layout') !== false) {
                    return [$template[0], $template[1]];
                }

                return ['layout/'.$template[0], $template[1]];
            }

            return [$this->layout, $template[0]];
        }

        return [$this->layout, $template];
    }
}
