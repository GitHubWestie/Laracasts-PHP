# Extract a Form Validation Class
Initially, coding is about geting things to function. Write some code, check in the browser. Does it work? But eventually it also becomes about efficiency, readability, re-usability and more. This is where re-factoring comes in. Rarely is code written perfectly first time round and even if it is, as the application grows and evolves pathways to refactors will likely open up where they werent previously possible. Being mindful of refactoring will keep the code enjoyable to work on and prevent it from becoming a bloated, tangled mess.

## Login Form
Currently the login form controller has a bunch of procedural validation taking place to validate the email and password provided. 

**Controllers/sessions/store.php**
```php
// Validate the data provided
if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password)) {
    $errors['password'] = 'Please provide a valid password.';
}

// If validation fails, return the view with errors
if (!empty($errors)) {
    return view('/sessions/create.view.php', [
        'errors' => $errors,
    ]);
}
```

This can be extracted into it's own class, making it more obvious what's going on and keeping the controller lighter and easier to read.

**Http/Forms/LoginForm.php**
```php
<?php

namespace Http\Forms;
use Core\Validator;

class LoginForm {
    protected $errors = [];

    public function validate($email, $password)
    {
        if (!Validator::email($email)) {
            $this->errors['email'] = 'Please provide a valid email address.';
        }

        if (!Validator::string($password)) {
            $this->errors['password'] = 'Please provide a valid password.';
        }

        // Returns a boolean value 
        return empty($this->errors);
    }

    /*
     * This is a 'getter' and allows the $errors array to be accessed outside
     * of this class safely even though it is a protected attribute.
     */
    public function errors()
    {
        return $this->errors;
    }
}
```

Then this class can be used in the controller instead of the procedural code.

**Controllers/sessions/store.php**
```php
// Instantiate the class so we can use it
$form = new LoginForm();

/* 
 * Call the validate method from the LoginForm class wrapped in a conditional.
 * If this is falsey return the view with errors using the error() method
 */
if(! $form->validate($email, $password)) {
    return view('/sessions/create.view.php', [
        'errors' => $form->errors(),
    ]);
};
```

And that's it. All the validation for that form condensed down into six lines of code. Winner. 🏆