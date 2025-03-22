<?php

$config = require("config.php");
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$note = $db->query("SELECT * FROM notes WHERE id = ?", [$_GET['id']])->fetch();

$heading = $note['body'];

require "views/note.view.php";