<?php

use Core\App;
use Core\Database;

$currentUser_id = 1;

$db = App::resolve(Database::class);

$note = $db->query('select * from notes where id = :id', [
    'id' => $_GET['id']
])->findOrFail();

authorise($note['user_id'] === $currentUser_id);

$heading = "Edit a Note";

view("notes/edit.view.php", [
    'heading' => $heading,
    'errors' => [],
    'note' => $note,
]);