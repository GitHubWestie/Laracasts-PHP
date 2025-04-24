# Updating with PATCH Requests

So far the app can create a note, show all of the notes, show a specific note and delete a note. But it cant yet edit or update a note. 

## Get the note to edit

- Create the edit view. Duplicate the `create` view and rename it to `edit` as they will be very similar.
- Update the routes file with a new get route for the view

    ```php
    $router->get('/note/edit', 'controllers/notes/edit.php');
    ```
- Create the controller. Duplicate the `create` controller. A good check at this stage is to quickly add an `echo("Hello! 👋🏻");` to the controller to verify that the app can reach the router.
- Once contact with the controller has been verified edit the view template, removing any references to creating a note
- In `show.view.php`, add an `Edit` link to the edit view. Style it if you like but be sure to include the id of the note in the query string.

    ```html
    <a href="/note/edit?id=<?= $note['id'] ?>">Edit</a>
    ```
- The controller now needs to use that `id` to retrieve the note in question. The logic can be copied from the `show.php` controller as it is the same. Be sure to include the `$note` in the `view()` payload.
- In the edit view the note body can now be used to autofill the textarea

    ```html
    <textarea><?= $note['body'] ?></textarea>
    ```

## Posting the update
- When the form is saved it should use a `PATCH` request to persist the data to the database. As `HTML` forms can only accept `GET` or `POST` requests this needs to be sneakily included in the `$_POST` request. 

### Handle the view
To do this include a hidden input field in the form. The controller will also need the id of the note being updated. This can also be included in the post request. A more common approach is to include this in the uri but this is covered later.

    ```html
    <form method="POST" action="/note">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="_id" value="<?= $note['id'] ?>">
    ```

### Handle the route
Create a route using the router class `patch` method. This should invoke a new controller for handling patch requests.
    
```php
$router->patch('/note', 'contollers/notes/update.php');
```

### Handle the controller
Create the `update.php` controller. Again use an echo or `dd()` to quickly verify the route reaches the controller. Once verified the controller logic can be built. This will be similar to the other resource controllers so can mostly be duplicated from those.

```php
use Core\App;
use Core\Database;
use Core\Validator;

$currentUser_id = 1;

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
```

#### Final recap on conventions used:

```php
'index' => 'Will show ALL notes'
'show' => 'Will show a SINGLE note'
'create' => 'Shows a form for creating a note'
'store' => 'The controller that is invoked by the create form and is responsible for actually persisting the note to the database'
'edit' => 'Shows a form to edit a note'
'delete' => 'The controller that is invoked by the edit form and is responsible for actually persisting the updated note to the database'
'destroy' => 'DELETES the note'
```