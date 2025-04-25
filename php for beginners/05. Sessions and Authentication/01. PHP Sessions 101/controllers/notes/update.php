<?php

use Core\App;
use Core\Database;
use Core\Validator;

$currentUser_id = 1;
$heading = 'Edit a Note';

// find the note
$db = App::resolve(Database::class);

$note = $db->query('select * from notes where id = :id', [
    'id' => $_POST['_id']
])->findOrFail();

// authorise that the current user can edit the note
authorise($note['user_id'] === $currentUser_id);

// validate the note
$errors = [];

if(! Validator::string($_POST['body'], 1, 1000)) {
    $errors['body'] = 'A body of no more than 1000 characters is required';
}

// if no validation errors, update the note in the database
if (count($errors)) {
    return view('notes/edit.view.php', [
        'heading' => $heading,
        'errors' => $errors,
        'note' => $note,
    ]);
}

$db->query('UPDATE notes SET body = :body where id = :id', [
    'id' => $_POST['_id'],
    'body' => $_POST['body'],
]);

// redirect user
header('location: /notes');
die();