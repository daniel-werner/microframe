<?php

namespace Microframe\Core;

interface ViewInterface
{
    public function __construct($template, $data = null);
    public function render();
}