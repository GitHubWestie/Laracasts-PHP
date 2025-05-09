<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password, 7, 255)) {
    $errors['password'] = 'Please provide a valid password.';
}

if (!empty($errors)) {
    return view('/registration/create.view.php', [
        'errors' => $errors,
    ]);
}

$db = App::resolve(Database::class);

$user = $db->query("SELECT * FROM users WHERE email = :email", [
    'email' => $email,
])->find();

if ($user) {
    header('location: /');
    exit();
} else {
    $db->query("INSERT INTO users(email, password) VALUES(:email, :password)", [
        'email' => $email,
        'password' => $password,
    ]);

    $_SESSION['user'] = [
        'email' => $email
    ];

    header('location: /');
    exit();
}