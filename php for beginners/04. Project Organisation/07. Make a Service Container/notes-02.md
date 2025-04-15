# Using the Service Container
Now that the service container is built and creating the database instance, use it.

## Create Another Class
In the Core directory create App.php

**App.php**
```php
<?php

namespace Core;

class App {
    protected static $container;

    public static function setContainer($container)
    {
        static::$container = $container;
    }
}
```
*Remember a static fucntion is one that can be called without instantiating the class first using* `App::setContainer()`

When `setContainer()` is called, it's given the `$container` object and the `$container` object is then set to the `$container` property (`protected static $container;`)


Similarly to the Container class what goes in needs to come out. So to get the `$container` out of the App class it needs another function.

**App.php**
```php
public static function container()
{
    return static::$container;
}
```

## Using the App Class
Back in bootstrap.php App can now be used.

**bootstrap.php**
```php
<?php

use Core\Container;
use Core\Database;
use Core\App;

$container = new Container;

$container->bind('Core\Database', function() {
    $config = require(base_path('config.php'));

    return new Database($config['database'], $config['database']['user'], $config['database']['password']);
});

App::setContainer($container);
```
*The* `$container` *object is now stored inside the App class*

**controllers/notes/destroy.php**
Now in the destroy controller all of the old code for creating the database can be replaced with one line.
```php
<?php

use Core\App;
use Core\Database;

$currentUser_id = 1;

$db = App::container()->resolve('Core\Database'); // One line to create the database instance

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

And this can even be simplified even more with a couple of tricks.

- Reference the class directly in the method call
```php
// Instead of...
App::container('Core\Database');

// Do this...
App::container(Database::class);
```
- Add the bind and resolve methods to the App class.

**App.php**
```php
<?php

namespace Core;

class App {
    protected static $container;

    public static function setContainer($container)
    {
        static::$container = $container;
    }

    public static function container()
    {
        return static::$container;
    }

    public static function bind($key, $resolver)
    {
        static::container()->bind($key, $resolver);
    }

    public static function resolve($key)
    {
        return static::container()->resolve($key);
    }
}
```

This way in the controllers App can call the method directly without having to involve the container method.

**Any controller where the old code exists**
```php
$db = App::resolve(Database::class);
```