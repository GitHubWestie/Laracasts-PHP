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

if (!Validator::string($password)) {
    $errors['password'] = 'Please provide a valid password.';
}

// If validation fails, return the view with errors
if (!empty($errors)) {
    return view('/sessions/create.view.php', [
        'errors' => $errors,
    ]);
}

// Make database connection
$db = App::resolve(Database::class);

// Match the user credentials and get $user
$user = $db->query('SELECT * FROM users WHERE email = :email', [
    'email' => $email,
])->find();

// login user if credentials match
if ($user) {
    if (password_verify($password, $user['password'])) {
        login([
            'email' => $email,
        ]);

        header('location: /');
        exit();
    };
}

return view('/sessions/create.view.php', [
    'errors' => [
        'email' => 'Unable to authenticate user',
    ]
]);


