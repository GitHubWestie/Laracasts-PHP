<?php
use Core\Response;
function dd($variable) {
    echo("<pre>");
    var_dump($variable);
    echo("</pre>");
    die();
}

function urlIs($value) {
    return $_SERVER['REQUEST_URI'] === $value;
}

function abort($code = 404) {
    http_response_code($code);

    require(base_path("views/{$code}.php"));
    
    die();
}

function authorise($condition, $status = Response::FORBIDDEN) {
    if(!$condition) {
        abort($status);
    }
}

function base_path($path) {
    return BASE_PATH . $path;
}

function view($path, $attributes = []) {
    extract($attributes);

    require base_path('views/' . $path);
}

function redirect($path) {
    header("location: {$path}");
    exit();
}

function logout() {
    $_SESSION = [];
    session_destroy();

    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}