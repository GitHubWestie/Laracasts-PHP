# PHP Auto-Loading and Extraction

## Setting the Root
Unfortuantely the notes app has a huge security flaw in that any of the files can be accessed by simply appending the filename to the url. For example:
```http://localhost:8888/config.php```

A common setup used in frameworks is to have a `public` folder which contains the entry point to the application. This approach can be utilised here too

- Create a `public` directory at the root level and move `index.php` into it.

Unfortunately that isnt quite enough to solve the problem though. When starting the server the root needs to be set to the public folder
```php
php -S localhost:8888 -t public
```

Now the root wil be set to public and prevent access to anything outside of that directory.

## Base Path
Now that `index.php` has been moved to a deeper directory it also doesn't know about anything else and therefore all of the `require()` files arent loading. Another common convention is to set a `BASE_PATH`. This could be done inside `index.php` as a `const`
```php
const BASE_PATH = __DIR__ . '/../';

require(BASE_PATH . 'functions.php');
```
This would then need to be added to anything that has lost track of the root (which at this point is just abouit everything 😅)

An alternative method is to create a helper function in functions.php

```php
function base_dir($path) {
    return BASE_PATH . $path;
}
```
Now in `index.php`, the function can be used with the exception being on the `functions.php` itself as it requires the `BASE_PATH` constant to be able to be loaded into `index.php`.

**index.php**
```php
<?php

const BASE_PATH = __DIR__ . '/../';

require(BASE_PATH . "functions.php");
require(base_path("Database.php"));
require(base_path("Response.php"));
require(base_path("router.php"));
```

## A Friendly View
This approach could also be applied to all the required views in the controllers but it could be made more user friendly by instead creating another helper function that's named a bit more intuitively.

**functions.php**
```php
<?php

$heading = "Home";

require(view("index.view.php"));
```
This can be condensed even further by puting the `require()` inside the `view()` function.
```php
function view($path) {
    require(base_path('views/' . $path));
}
```
The only downside to this approach is that because the function is called from outside the scope of the controller it has no idea about the defined `$heading` variable. To solve this it can be passed through to the function and then unpacked using a built-in php method `extract`.
```php
function view($path, $attributes = []) {
    extract($attributes); // Extract unpacks arrays into key:value pairs

    require(base_path('views/' . $path));
}
```

These changes then need to be implemented in all controllers, changing the `$attributes` array for each one.

## Deeper and deeper, down the rabbit hole
Once all of those have been changed attempt to load a notes page. And it fails. config.php needs to be updated too.

Once that is updated, the `notes/partials` start complaining. Update the file path for these too, using the `base_path()`function.

## Lazy Boy
Currently, whenever a class is required to be available across the application it has to be required() in index.php. These can in fact be automatically or lazyily loaded when they are instantiated, using another built-in php function `spl_autoload_register`.
```php
spl_autoload_register(function($class) {
    dd($class);
});
```
Basically, when this function is used, php will go looking for a class whenever it sees an instance of a class being created. So in this case when it sees 
```php
new Database();
```
in `controllers/index.php` it goes looking for a class named Database. So in order to fetch it the require() needs to be added to that function:

```php
spl_autoload_register(function($class) {
    require(base_path($class . 'php'));
})
```
This has the benefit of working for multiple classes so only needs one declaration and then any class that is instantiated will find this function, saving having to require() each class individually.

## Core Functionality
Things that arent unique to the application can also be moved into their own directory. 
- Create a directory called 'Core'

And add to it the more generic files and classes:
- Database.php
- functions.php
- router.php
- Validator.php
- Response.php

As classes no longer need the require() method thanks to php's `spl_autoload_register` any requires on classes can be removed. For example the Validator class that is required in `index.php` controller can be removed, as it is instantiated in the controller and will be found by `spl_autoload_register`.