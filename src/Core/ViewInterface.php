<?php

namespace Microframe\Core;

interface ViewInterface
{
    public function __construct($templates, $data = null);

    public function render();
}
