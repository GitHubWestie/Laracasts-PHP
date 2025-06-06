<?php

require("Validator.php");

$config = require("config.php");
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$heading = "Create a Note";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors;

    $validator = new Validator();

    if(!$validator->string($_POST['body'], 1, 1000)) {
        $errors['body'] = 'A body of no more than 1000 characters is required';
    }

    if(empty($errors)) {
        $db->query("INSERT INTO notes(body, user_id) VALUES(:body, :user_id)", [
            'body' => $_POST['body'],
            'user_id' => 1,
        ]); 
    }
}

require("views/note-create.view.php");