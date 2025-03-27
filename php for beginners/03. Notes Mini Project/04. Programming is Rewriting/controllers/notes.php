<?php

$heading = "My Notes";

$config = require("config.php");
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$notes = $db->query("SELECT * FROM notes WHERE user_id = 1")->findAll();

require "views/notes.view.php";