<?php

function dd($variable) {
    echo("<pre>");
    var_dump($variable);
    echo("</pre>");
    die();
}

// dd($_SERVER);

function urlIs($value) {
    return $_SERVER['REQUEST_URI'] === $value;
}