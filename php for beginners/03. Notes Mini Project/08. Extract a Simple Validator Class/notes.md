# Extract a Simple Validator Class

Let's extract the validation rules from the `note-create.php` controller and put them in a dediacted validator class. That way the validator rules can be referenced easily from anywhere in the application, saving time and repetition.

## Create the Class

- Create `Validator.php` Remember class names are always capitalised.
```php
<?php

class Validator {
    //
}
```

- Require the Validator in the `note-create.php controller` and instantiate it.
```php
<?php

require("Validator.php");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
}

```

## Make the Rules
Within the validator class, start adding the rules.

```php
class Validator {
    public function string($value)
    {
        return = trim($value); // Removes any whitespace before and after a string. This also prevents a bunch of spaces being sent to the database as string.
    }
}
```

Previously the rules were all declared separately, but the class function can be modified to accept additional parameters to process more at once.

```php
public function string($value, $min = 1, $max = INF) {
    $value = trim($value);

    return strlen($value) >= $min && strlen($value) <= $max;
}
```

Which means in the controller the validation can all take place in one method
```php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors;

    $validator = new Validator();

    if(!$validator->string($_POST['body'], 1, 1000)) {
        $errors['body'] = 'A body of no more than 1000 characters is required';
    }
}
```

## Purity
What has just been created in the Validator class is what's known as a 'pure' function. This means that the function doesnt rely on anything outside it (other than the arguments given to it when it is called), it doesn't defer to anything else and it doesn't use the `$this` keyword.

Pure functions can be declared as `static` and this means that the methods within them can be called without first instantiating the class.

```php
public static function string()
{
    //
}
```

Now, instead of instantiating the class and using the methods using the arrow syntax, a double colon syntax is used instead
```php
Validator::string();
```
```php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors;

    if(!Validator::string($_POST['body'], 1, 1000)) {
        $errors['body'] = 'A body of no more than 1000 characters is required';
    }
}
```