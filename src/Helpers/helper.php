<?php

function u($string = '')
{
    return urlencode($string);
}

function raw_u($string = '')
{
    return rawurlencode($string);
}

function h($string = '')
{
    return htmlspecialchars($string);
}

function e($string = '')
{
    echo h($string);
}

function e_raw($string = '')
{
    echo $string;
}

function year()
{
    echo date('Y');
}

function active($currect_page)
{
    $url_array = parse_url($_SERVER['REQUEST_URI']);
    if ($currect_page == $url_array['path']) {
        echo 'active';
    }
}

function dd($data = null)
{
    var_dump($data);
    exit();
}
