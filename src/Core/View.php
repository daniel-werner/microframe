<?php

namespace Microframe\Core;

class View implements ViewInterface
{
    protected $templates = [];
    protected $data = '';

    public function __construct($templates, $data = null)
    {
        $this->templates = $templates;
        $this->data = $data;
    }

    public function render()
    {
        if (!empty($this->data)) {
            extract($this->data);
        }

        ob_start();

        $template = array_shift($this->templates);

        $filePath = APP_DIR.'views/'.$template.'.php';
        if (file_exists($filePath)) {
            include $filePath;
        }

        ob_end_flush();
    }
}
