<?php

$config = require("config.php");
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$note = $db->query("SELECT * FROM notes WHERE id = ?", [$_GET['id']])->fetch();

$heading = $note['body'];
$currentUser_id = 1;

if(!$note) {
    abort(Response::NOT_FOUND);
}

if($note['user_id'] !== $currentUser_id) {
    abort(Response::FORBIDDEN);
}

require "views/note.view.php";