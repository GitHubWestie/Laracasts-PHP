<?php

$router->get('/', 'controllers/index.php');
$router->get('/about', 'controllers/about.php');
$router->get('/contact', 'controllers/contact.php');

$router->get('/notes', 'controllers/notes/index.php')->only('auth');
$router->get('/note', 'controllers/notes/show.php');
$router->get('/note/edit', 'controllers/notes/edit.php');
$router->delete('/note', 'controllers/notes/destroy.php');

$router->get('/note/create', 'controllers/notes/create.php');
$router->post('/note', 'controllers/notes/store.php');

$router->patch('/note', 'controllers/notes/update.php');

$router->get('/register', 'controllers/registration/create.php')->only('guest');
$router->post('/register', 'controllers/registration/store.php');