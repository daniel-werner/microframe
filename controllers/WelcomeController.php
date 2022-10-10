<?php

namespace Controllers;

use Microframe\Controllers\Controller;

class WelcomeController extends Controller
{
    public function welcome($params)
    {
        $this->render(['welcome/welcome'], ['data' => 'Welcome']);
    }

    public function about($params)
    {
        $this->render('welcome/about', ['data' => 'About']);
    }

    public function contact($params)
    {
        $this->render('welcome/contact', ['data' => 'Contact']);
    }

    public function json($params)
    {
        $this->jsonResponse(['hello' => 'Word']);
    }
}
