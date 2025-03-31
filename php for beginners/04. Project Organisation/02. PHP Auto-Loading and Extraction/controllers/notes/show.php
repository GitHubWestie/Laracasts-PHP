<?php

$config = require(base_path("config.php"));
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$currentUser_id = 1;

$note = $db->query("SELECT * FROM notes WHERE id = ?", [$_GET['id']])->findorfail();

authorise($note['user_id'] === $currentUser_id);

$heading = $note['body'];

view("notes/show.view.php", [
    'heading' => $heading,
    'currentUserId' => $currentUser_id,
    'note' => $note,
]);