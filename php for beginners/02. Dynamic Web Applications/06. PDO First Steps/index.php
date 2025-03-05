<?php

require("functions.php");
// require("router.php");

// Connect to the mySql database
$DSN = "mysql:host=localhost;port=3306;user=root;dbname=beginner_php;charset=utf8mb4";

$pdo = new PDO($DSN);

$statement = $pdo->prepare("select * from posts");

$statement->execute();

$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach($posts as $post) {
    echo("<li>" . $post['title'] . "</li>");
}