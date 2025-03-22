# Introduction to Authorisation

In the last lesson a huge security flaw was introduced. By allowing notes to be accessed by a simple `id` and having no authorisation in place, a user *could* simply change the parameter in the query string for the id of a note that they didnt write and access it.

Normally this would be covered by sessions but as this hasn't been covered yet a workaround will be implemented instead. This will involve temporarily hardcoding a `$user_id` to check against `$note['user_id']`, in the controller.

## Note Controller

```php
<?php

$config = require("config.php");
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);

$note = $db->query("SELECT * FROM notes WHERE id = ?", [$_GET['id']])->fetch();

$heading = $note['body'];
$currentUser_id = 1; // Hardcode user_id

// Display a 404 not found if note doesn't exist
if(!$note) {
    abort(Response::NOT_FOUND);
}

// Display a 403 forbidden page if user didn't create the note that they are trying to access
if($note['user_id'] !== $currentUser_id) {
    abort(Response::FORBIDDEN); 
}

require "views/note.view.php";
```

## Response Class
The abort function that was created earlier in the series could accept the response code as an integer. There's nothing wrong with this but it does highlight an issue known as 'the magic number'. Basically when returning to this code in 6 months time or maybe more, will you remember what the numbers mean. As they are response codes, this probably wouldn't be an issue but in the interest of clarity it can be good practice to make things like this more explicit. It can also help out other developers that might look at the code. It is also the reason the the current user id was extracted into a variable instead of being in-lined into the code.

```php
<?php

class Response {
    const NOT_FOUND = 404;
    const FORBIDDEN = 403;
}
```