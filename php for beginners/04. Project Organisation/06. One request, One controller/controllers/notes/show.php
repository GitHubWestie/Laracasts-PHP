<?php

use Core\Database;

$currentUser_id = 1;

$config = require(base_path("config.php"));
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$note = $db->query('select * from notes where id = :id', [
    'id' => $_GET['id']
])->findOrFail();

authorise($note['user_id'] === $currentUser_id);

view("notes/show.view.php", [
    'heading' => 'Note',
    'note' => $note
]);
