<?php

use Core\App;
use Core\Database;
use Http\Forms\LoginForm;

// Get the provided user data from the $_POST superglobal
$email = $_POST['email'];
$password = $_POST['password'];

$form = new LoginForm();

// If validation fails, return the view with errors
if (!$form->validate($email, $password)) {
    return view('/sessions/create.view.php', [
        'errors' => $form->errors(),
    ]);
};

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


