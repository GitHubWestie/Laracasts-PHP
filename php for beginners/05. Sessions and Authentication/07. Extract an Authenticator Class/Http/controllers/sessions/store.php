<?php

use Core\Authenticator;
use Http\Forms\LoginForm;

// Get the provided user data from the $_POST superglobal
$email = $_POST['email'];
$password = $_POST['password'];

$form = new LoginForm();

// If validation fails, return the view with errors
if ($form->validate($email, $password)) {
    if ((new Authenticator)->attempt($email, $password)) {
        redirect('/');
    } else {
        $form->error('email', 'Unable to authenticate user');
    }
    
    return view('/sessions/create.view.php', [
        'errors' => $form->errors(),
    ]);
};
