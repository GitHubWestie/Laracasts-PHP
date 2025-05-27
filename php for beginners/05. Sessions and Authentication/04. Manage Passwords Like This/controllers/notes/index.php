<?php
use Core\App;
use Core\Database;

$heading = "My Notes";

$db = App::resolve(Database::class);

$notes = $db->query("SELECT * FROM notes WHERE user_id = 1")->findAll();

view("notes/index.view.php", [
    'heading' => $heading,
    'notes' => $notes,
]);