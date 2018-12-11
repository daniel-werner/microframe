<?php

namespace Core;

class View implements ViewInterface
{
    protected $template = '';
    protected $data = '';

    public function __construct($template, $data = null){
        $this->template = $template;
        $this->data = $data;
    }

    public function render(){
        if( !empty($this->data) ){
            extract($this->data);
        }

        $filePath = __DIR__ . '/../views/' . $this->template . '.php';

        if( file_exists($filePath) ){
            include_once $filePath;
        }

    }
}