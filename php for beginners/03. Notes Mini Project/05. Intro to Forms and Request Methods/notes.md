# Intro to Forms and Request Methods

This covers creating a form for creating new notes and storing them in the database.

## Create a Link to the Form
First a link to the form is needed. This follows the same cycle as before.

- Create a route/endpoint
- Create a controller to respond to that endpoint
- Create a view which will contain the form

***Note:** Normally a single controller is able to respond to multiple requests and will contain various methods for doing so. For now though these controllers are kept simple so each controller responds to a single endpoint*

**routes.php**
```php
$routes = [
    "/" => "controllers/index.php",
    "/about" => "controllers/about.php",
    "/contact" => "controllers/contact.php",
    "/notes" => "controllers/notes.php",
    "/note" => "controllers/note.php",
    "/note/create" => "controllers/note-create.php", // Add new endpoint
];
```

**controllers/note-create.php**
```php
<?php

$heading = "Create a Note";

require("views/note-create.view.php");
```

**views/note-create.php**
```php
<?php require("partials/head.php") ?>
<?php require("partials/nav.php") ?>
<?php require("partials/banner.php") ?>

<main>
  <div class="mx-auto max-w-7xl my-6 px-4 py-1 sm:px-6 lg:px-8">
    <form method="POST">
        <label for="body">Description</label>
        <div>
            <textarea name="body" id="body"></textarea>
        </div>
        <p>
            <button>Create</button>
        </p>
    </form>
  </div>
</main>

<?php require("partials/footer.php") ?>
```
***Note:** The name attribute for the textarea is body. This is because the column that this data will be saved to in the database is also called body. While this isn't essential it is good practice. What **is** essential is that the form inputs have a name. Without a name the data will not be submitted with the form.*

By default the form will send a GET request when the button is clicked. When submitting a form a POST request should be used instead. This is achieved by declaring the method attribute on the form element and assigning it the property of POST.
```html
<form method="POST">
```

Normally the form would also have an `action` attribute. This is used to specify where the request should be sent on submission. By default the request is sent to the same page that the form is on but in the future this will be used to set a destination for the data.

## Superglobals
Using the `$_SERVER` superglobal the `request method` can be detected. Furthermore the `$_POST` superglobal can provide information that is contained within the `$_POST request`.

**controllers/note-create.php**
```php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    dd($_POST);
}
```
