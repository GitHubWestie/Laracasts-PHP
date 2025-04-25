# Register a New User
As the app doesnt have a registeration page yet we need to make one. Make sure that a session is started in public/index.php using `session_start();`.

### Add a Route
**routes.php**
```php
$router->get('/register', 'controllers/registration/create.php');
```

### Create the Controller
**controllers/registration/create.php**
```php
view('registration/create.view.php');
```

### Create the View
```php
<?php require(base_path("views/partials/head.php")) ?>
<?php require(base_path("views/partials/nav.php")) ?>

<main>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" action="/register" method="POST">
        // rest of form
</main>

<?php require(base_path("views/partials/footer.php")) ?>
```
*Note: For displaying validation errors copy the code from notes/create.view.php or write it again*

## Store the New User
The form needs a route to `POST` to:
**routes.php**
```php
$router->post('/register', 'controllers/registration/store.php');
```

And a controller to handle the logic. Run a quick `dd('Register the user!')` to verify the controller is being reached.

As this form will need to validate an email, the method for that needs to be added to the Validator class.

**Core/Validator.php**
```php
class Validator {
    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
```
The `filter_var()` method and `FILTER_VALIDATE_EMAIL` filter are provided by PHP.

**controllers/registration/store.php**
```php
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

// Check if account already exists:
$user = $db->query("SELECT * FROM users WHERE email = :email", [
    'email' => $email,
])->find();

/*
 * If the account already exists redirect to index.
 * Else add the new user to the database, add user to the session and redirect to index
 */ 
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
```

