<?php

use Core\App;
use Core\Database;

$currentUser_id = 1;

$db = App::resolve(Database::class);

$note = $db->query('select * from notes where id = :id', [
    'id' => $_GET['id']
])->findOrFail();

authorise($note['user_id'] === $currentUser_id);

view("notes/show.view.php", [
    'heading' => 'Note',
    'note' => $note
]);
