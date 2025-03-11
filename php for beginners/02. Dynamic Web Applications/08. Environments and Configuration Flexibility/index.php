<?php

require("functions.php");
// require("router.php");
require("Database.php");

$config = require("config.php");

$db = new Database($config['database'], 'root', '<password>');

$posts = $db->query("SELECT * FROM posts");

foreach($posts as $post) {
    echo("<li>" . $post['title'] . "</li>");
}