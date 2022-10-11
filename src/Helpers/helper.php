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

//Validation helpers
function isBlank($value)
{
    return !isset($value) || trim($value) === '';
}

function hasPresence($value)
{
    return !isBlank($value);
}

function hasLengthGreaterThan($value, $min)
{
    $length = strlen($value);

    return $length > $min;
}

function hasLengthLessThan($value, $max)
{
    $length = strlen($value);

    return $length < $max;
}

function hasLengthExactly($value, $exact)
{
    $length = strlen($value);

    return $length == $exact;
}

function hasLength($value, $options)
{
    if (isset($options['min']) && !hasLengthGreaterThan($value, $options['min'] - 1)) {

      return false;
    } elseif (isset($options['max']) && !hasLengthLessThan($value, $options['max'] + 1)) {

      return false;
    } elseif (isset($options['exact']) && !hasLengthExactly($value, $options['exact'])) {

      return false;
    } else {

      return true;
    }
}

function hasInclusionOf($value, $set)
{
    return in_array($value, $set);
}

function hasExclusionOf($value, $set)
{
    return !in_array($value, $set);
}

function hasString($value, $required_string)
{
    return strpos($value, $required_string) !== false;
}

function hasValidEmailFormat($value)
{
    $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';

    return preg_match($email_regex, $value) === 1;
}
