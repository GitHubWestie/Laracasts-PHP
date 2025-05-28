# Extract an Authentication Class
Riding the refactor wave this carries on refactoring with authentication. Again in the same controller but the code under scrutiny this time is this:

**Http/Controllers/sessions/store.php**

```php
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
```

Again, to condense this it will need to be extracted into its own class.

**Core/Authentication.php**
```php
<?php

namespace Core;

class Authenticator {
    public function attempt($email, $password)
    {
        // Match user using email provided
        $user = App::resolve(Database::class)->query('SELECT * FROM users WHERE email = :email', [
            'email' => $email,
        ])->find();

        // login user if credentials match
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $this->login([
                    'email' => $email,
                ]);

                return true;
            };
        }

        return false;
    }

    public function login($user)
    {
        $_SESSION['user'] = [
            'email' => $user['email'],
        ];

        session_regenerate_id(true);
    }
}
```

Then the class can be used to refactor the orginal controller code like so:

```php
$auth = new Authenticator();

if ($auth->attempt($email, $password)) {
    redirect('/'); // Another mini refactor moving the header() and exit() into a single redirect() function.
} else {
    return view('/sessions/create.view.php', [
        'errors' => [
            'email' => 'Unable to authenticate user',
        ]
    ]);
};
```

## But wait, there's more!
In the event of a falsey value from either the form or authenticator class the user is redirected to a page with an errors array. In these cases the code looks very similar but not quite the same. But *if* these could be refactored to be the same then they could be merged into one, which would be nice.

The first step is to add a method to the form class that can accept the data we want to give it.

**LoginForm.php**
```php
public function error($field, $message)
{
    return $this->errors[$field] = $message;
}
```

When this method is called and given the required data it will append an error message to the errors array. This means that the same redirect code can be used for both form and authentication validation, like this:

**Controllers/sessions.store.php**
```php
$auth = new Authenticator();

if ($auth->attempt($email, $password)) {
    redirect('/'); // Another mini refactor moving the header() and exit() into a single redirect() function.
} else {

    $form->error('email', 'Unable to authenticate user');

    return view('/sessions/create.view.php', [
        'errors' => $form->errors(),
    ]);
};
```
*Cute*

This means that all of the refactoring in this file can be even further condensed into just this:

**Controllers/sessions.store.php**
```php
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
```

The Authenticator class instance has also been inlined here as it is only referenced once. This is done be instantiating it inside parentheses instead of on a variable:

```php
(new Authenticator);
```