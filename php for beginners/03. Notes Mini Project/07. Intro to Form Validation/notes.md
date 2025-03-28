# Intro to Form Validation
A simple but reasonably effective way to at least make sure the form receives some sort of input is to use the `required` attribute on the form input. Although this isn't bomb-proof and it can be circumvented it is considered best practice and gives rapid client side validation back to the user.

## Server-side Validation
A more robust approach is server-side validation and for the sake of clarity it's worth nothing that *BOTH* types should be used. Server-side should be seen as a last line of defence.

**controllers/note-create.php**
```php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors; // Added errors array

    // Check string length of body. If 0 add error to error array
    if(strlen($_POST['body']) === 0) {
        $errors['body'] = 'A body is required';
    }

    // Only proceed with db query if error array is empty
    if(empty($errors)) {
        $db->query("INSERT INTO notes(body, user_id) VALUES(:body, :user_id)", [
            'body' => $_POST['body'],
            'user_id' => 1,
        ]); 
    }
}
```

## Inform the User
If a validation error comes from the server-side then the user needs to know. The error can be displayed under the form using the `$errors` array provided by the controller.

```php
<?php if(isset($errors['body'])) : ?>
    <p class="text-sm text-red-500 mt-2"><?= $errors['body'] ?></p>
<?php endif ?>
```

## Length
Another common validation rule is length. It's usually sensible to set some sort of min and max length on user inputs. Again this can be handled client-side using attributes like `minlength` and `maxlength`, but it should also have a server-side defence in place.

**controllers/note-create.php**
```php
if(strlen($_POST['body']) > 1000) {
    $errors['body'] = 'Note body cannot exceed 1000 characters';
}
```

This is enough to provide feedback to the user but on submission the 1000 characters will be lost which is bad for user experience. The data can be rescued from the `$_POST` superglobal though and echoed into the textarea element so the user doesn't have to start from scratch.

```php
<textarea><?= isset($_POST['body']) ? $_POST['body'] : '' ?></textarea> // Checks if the body has a value and displays it if it does
```

The `null coalescing operator` can also be used for this to keep things a little shorter and cleaner.
```php
<textarea><?= $_POST['body'] ?? '' ?></textarea> // Checks that the body is not NULL displays it if it exists
```