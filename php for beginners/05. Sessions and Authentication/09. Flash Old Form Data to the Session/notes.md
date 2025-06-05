# Flash Old Form Data to the Session
Currently if the login or registration forms are submitted and fail validation for any reason the form inputs will be cleared. Previously this was solved using the $_POST superglobal but as login and registration forms use a PRG pattern any data in the $_POST superglobal wont persist after validation fails.

Fortunately the same approach can be used to capture this data as was used to capture validation errors to make them persist after validation.

**Http/sessions/store.php**
```php
Session::flash('old', [
    'email' => $_POST['email'];
])
// 'old' is a common naming convention when referencing old form data
```

With the data captured in the session it can now be used in the view. This is similar to how the $_POST superglobal was accessed before in the note/create.php view. The value attribute is added to the form field and the value is set to the old form value.

**views/sessions/create.view.php**
```php
<input
    id="email"
    name="email"
    type="email"
    value="<?= $_SESSION[_flash]['old']['email'] ?? '' ?>"
    >
```

This approach is a bit cumbersome though and also means referencing that _flash key that we dont really want to use outside of the class. Another approach would be to use the get() method from the Session class

**views/sessions/create.view.php**
```php
<input
    id="email"
    name="email"
    type="email"
    value="<?= Core/Session::get('old')['email'] ?? '' ?>"
    >
```

But really this is as cumbersome, just looks different. Instead this could be wrapped in a function.

**Core/functions.php**
```php
function old($key, $default = '') {
    return Core\Session::get('old')[$key] ?? $default;
}
```

Now the view can use the function instead, keeping it clean and simple.

**views/sessions/create.view.php**
```php
<input
    id="email"
    name="email"
    type="email"
    value="<?= old('email'); ?>"
    >
```