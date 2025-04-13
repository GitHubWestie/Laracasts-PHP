# One Request, One Controller

In the previous lesson the router was upgraded significantly and now allows for handling specific request types. This means that the application can now route to specific controllers for specific functions such as GET, POST, DELETE etc. The current controllers have some logic that could be separated now that the routes have this capability. For example the controller notes/show.php currently has a conditional to determine if the request type is post and if it is then to do some stuff and if not then do some other stuff etc. Because the router is routing according to the uri *AND* request method type that  is no longer required. That logic can be split into it's own respective controllers.

## Splitting the code
Before splitting the controller logic it looks like this:

**notes/show.php**
```php
<?php

use Core\Database;

$currentUser_id = 1;

$config = require(base_path("config.php"));
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $db->query('select * from notes where id = :id', [
        'id' => $_GET['id']
    ])->findOrFail();

    authorise($note['user_id'] === $currentUser_id);

    $db->query('delete from notes where id = :id', [
        'id' => $_GET['id']
    ]);

    header('location: /notes');
    exit();
} else {
    $note = $db->query('select * from notes where id = :id', [
        'id' => $_GET['id']
    ])->findOrFail();

    authorise($note['user_id'] === $currentUser_id);

    view("notes/show.view.php", [
        'heading' => 'Note',
        'note' => $note
    ]);
}
```
*Currently handling logic for both showing and deleting a note*

After the split it looks like this:
```php
<?php

use Core\Database;

$currentUser_id = 1;

$config = require(base_path("config.php"));
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$note = $db->query('select * from notes where id = :id', [
    'id' => $_GET['id']
])->findOrFail();

authorise($note['user_id'] === $currentUser_id);

view("notes/show.view.php", [
    'heading' => 'Note',
    'note' => $note
]);
```
*A much lighter controller*

And the remaining logic is moved into a new controller:

**notes/destroy.php**
```php
<?php

use Core\Database;

$currentUser_id = 1;

$config = require(base_path("config.php"));
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$note = $db->query('select * from notes where id = :id', [
    'id' => $_GET['id']
])->findOrFail();

authorise($note['user_id'] === $currentUser_id);

$db->query('delete from notes where id = :id', [
    'id' => $_POST['id']
]);

header('location: /notes');
exit();
```

Now the logic in each controller is cleaner, lighter, more specific and easier to read.

## Routing
Now that the router is routing based on uri *and* request method type a route must be added for each request type the app needs to respond to. RESTful conventions dictate that when a new note is created a POST request should be sent to the notes resource. This is done via the `action` attribute on the create a note form. 

**routes.php**
```php
<?php

$router->get('/', 'controllers/index.php');
$router->get('/about', 'controllers/about.php');
$router->get('/contact', 'controllers/contact.php');

$router->get('/notes', 'controllers/notes/index.php');
$router->get('/note', 'controllers/notes/show.php');
$router->delete('/note', 'controllers/notes/destroy.php'); // New route for the delete request type and destroy controller

$router->get('/note/create', 'controllers/notes/create.php');
$router->post('/notes', 'controllers/notes/store.php'); // New route submitting to the notes resource when the create note form is submitted
```

This means that the `create` controller can also be split now as it only needs handle showing the create page. The logic for saving a note to the database can be separated out into a `store` controller (`store` is another conventionally used term) and simplified in the same way as the show and delete logic was.

**Create Controller**
```php
<?php

$heading = "Create a Note";

view("notes/create.view.php", [
    'heading' => $heading,
    'errors' => [],
]);
```
*Now only responsible for displaying the form*

**Store Controller**
```php
<?php

use Core\Database;
use Core\Validator;

$config = require(base_path("config.php"));
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$heading = "Create a Note";
$errors = [];

$validator = new Validator();

if(!$validator->string($_POST['body'], 1, 1000)) {
    $errors['body'] = 'A body of no more than 1000 characters is required';
}

if(!empty($errors)) {
    return view("notes/create.view.php", [
        'heading' => $heading,
        'errors' => $errors,
    ]);
}

$db->query("INSERT INTO notes(body, user_id) VALUES(:body, :user_id)", [
    'body' => $_POST['body'],
    'user_id' => 1,
]);

header("location: /notes");
die();
```
*Handles storing a new note in the database*