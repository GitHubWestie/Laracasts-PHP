<?php

use Core\App;
use Core\Database;
use Core\Validator;

// Get the provided user data from the $_POST superglobal
$email = $_POST['email'];
$password = $_POST['password'];

// Validate the data provided
if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password, 7, 255)) {
    $errors['password'] = 'Please provide a valid password.';
}

// If validation fails, return the view with errors
if (!empty($errors)) {
    return view('/registration/create.view.php', [
        'errors' => $errors,
    ]);
}

// Make database connection
$db = App::resolve(Database::class);

/*
 * If the account already exists redirect to index.
 * Else add the new user to the database, add user to the session and redirect to index
 */ 
$user = $db->query("SELECT * FROM users WHERE email = :email", [
    'email' => $email,
])->find();

if ($user) {
    header('location: /');
    exit();
} else {
    $db->query("INSERT INTO users(email, password) VALUES(:email, :password)", [
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
    ]);

    $_SESSION['user'] = [
        'email' => $email
    ];

    header('location: /');
    exit();
}