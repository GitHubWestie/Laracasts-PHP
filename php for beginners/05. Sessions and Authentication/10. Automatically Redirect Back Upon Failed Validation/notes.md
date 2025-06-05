# Automatically Redirect Back Upon Failed Validation
Continuing on the refactor hype this lesson turns its attention to the validation on the LoginForm class and how it could be better structured. Currently the form validation and the controller for `sessions/store.php` is starting to look a little messy. Additionally, the potential for duplication of code is quite big. Imagine a full size project with multiple forms for various things. The code in this controller could end up being duplicated many times over.

As usual the method around this is to extract things to a class. Too much was done in this episode for me to really make notes on all of it. Instead, this is the list of files that has been created, updated and refactored. 

## New File
**Core\ValidationException.php**

```php
namespace Core;

use Exception;

class ValidationException extends \Exception {

    public readonly array $errors;
    public readonly array $old;

    public static function throw($errors, $old)
    {
        $instance = new static;

        $instance->errors = $errors;
        $instance->old = $old;

        throw $instance;
    }
}
```

## Updated Files
**Http\controllers\sessions\store.php**
```php
use Core\Authenticator;
use Http\Forms\LoginForm;

$form = LoginForm::validate($attributes = [
    'email' => $_POST['email'],
    'password' => $_POST['password'],
]);

$signedIn = (new Authenticator)->attempt($attributes['email'], $attributes['password']);

if(!$signedIn) {
    $form->error('email', 'Unable to authenticate user')->throw();
}
redirect('/');
```
**Http\Forms\LoginForm.php**

The biggest refactor is in this file. 
```php
namespace Http\Forms;

use Core\ValidationException;
use Core\Validator;

class LoginForm {

    // Store validation errors
    protected $errors = [];

    // Constructor takes in user-submitted attributes (email, password)
    public function __construct(public array $attributes)
    {
        // Validate email format with Validator class method
        if (!Validator::email($attributes['email'])) {
            $this->errors['email'] = 'Please provide a valid email address.';
        }

        // Validate password with Validator class method
        if (!Validator::string($attributes['password'])) {
            $this->errors['password'] = 'Please provide a valid password.';
        }
    }

    // Validate attributes and either return the instance or throw errors
    public static function validate($attributes)
    {
        $instance = new static($attributes);

        // If validation failed, throw an exception; otherwise return the instance
        return $instance->failed() ? $instance->throw() : $instance;
    }

    // Throws a validation exception using collected errors
    public function throw()
    {
        ValidationException::throw($this->errors, $this->attributes);
    }

    // Returns true if there are any validation errors
    public function failed()
    {
        return count($this->errors);
    }

    // Returns all validation errors
    public function errors()
    {
        return $this->errors;
    }

    // Adds a custom error for a specific field
    public function error($field, $message) {
        $this->errors[$field] = $message;
        return $this;
    }
}
```
**public\index.php**
Validation checks moved to a try/catch block in index.php. This way all validation is handled here for every request instead of having to be implemented in the controller for each resource. 

```php
try {

    $router->route($uri, $method);

} catch (ValidationException $exception) {
    
    Session::flash('errors', $exception->errors);
    Session::flash('old', $exception->old);

    redirect($router->previousUrl());
}
```
**Core\Router.php**

Small helper function added to the router class so that any redirects can be dynamic instead of hardcoded like they were previously.
```php
public function previousUrl() {
    return $_SERVER['HTTP_REFERER'];
}
```