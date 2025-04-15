# Make Your First Service Container

This lesson comes with a disclaimer that it is essentially, unnecessary. This is because in real life it will almost never be necessary to build a service container. Most of the time a project will use some sort of framework such as Laravel and these come with things like service containers pre-built. There is no need to re-invent the wheel!

That said it is worth having an understanding of how service containers work and that's what this lesson aims to deliver.

## Why a Service Container
Currently, in the notes/destroy controller there is a bunch of code responsible for creating a Database instance and executing a query. This code is also duplicated to some extent in a few places in the codebase, basically wherever there is a query for the database.

This is why a service container is necessary. A service container allows a database instance to be created once and then stored in the container for whenever it's needed. This saves on all the duplication currently in the codebase and containers can also be used for other things like API's, complex objects and much more.

## Create the Container Class
In the Core directory create the Container class
```php
<?php

namespace Core;

class Container {
    public function bind()
    {
        // Think of bind as 'add'. Bind is used as it is common in frameworks. It will add things to the service container
    }
    
    public function resolve()
    {
        // Think of resolve as 'remove'. Resolve is used as it is common in frameworks. It will allow us to take things out of the service container
    }
}
```

## By Your Bootstraps
Create another file `bootstrap.php` at the root level and `require()` it in `index.php`. This will serve as a playground of sorts to interact with the Container.

**bootstrap.php**
```php
<?php

use Core\Container;

$container = new Container;
```

The main goal at the moment is to remove all of that duplication of instantiating the Database in the controllers etc. So the Database is also required. Then the bind() method can be called (which doesnt do anything yet) and that logic can be added there instead.

**bootstrap.php**
```php
<?php

use Core\Container;
use Core\Database;

$container = new Container;

$container->bind('Core\Database', function() {
    $config = require(base_path('config.php'));

    return new Database($config['database'], $config['database']['user'], $config['database']['password']);
});
```

## In a Bind
Then, back in the Container class, build the `bind()` method. The setup for this will be almost identical to the setup used for the `Router` class previously. 

The function itself needs to accept a `$key` and the function, in this case `$resolver`. Anything passed into the `bind()` method will need to be stored somewhere (same as in the Router) so the `$bindings` array is created. Anything process by `bind()` will be `pushed` into that array.

**Container.php**
```php
class Container {
    protected $bindings = [];

    public function bind($key, $resolver)
    {
        $this->bindings[$key] = $resolver;
    }
    
    public function resolve()
    {
        //
    }
}
```
*Remember the values in the `bind()` signature are parameters. They could have been called anything. This is just what the lesson uses*

💡 So now, at this point the service container contains one item in the `$bindings` array. A `$key` named `Core\Database` with a value returned from that `$resolver` function which should be the actual Database connection.

## Retrieving from the Container
Whatever goes in to the service container needs to be able to come back out. This is achieved with the `resolver()` method.

**bootstrap.php**
```php
$db = $container->resolve('Core\Database');
```

**Container.php**
```php
public function resolve($key)
{
    if(!array_key_exists($key, $this->bindings)) {
        throw new \Exception('No matching binding found for ' . $key);
    }

    $resolver = $this->bindings[$key];

    return call_user_func($resolver);
}
```
💡 WTF is Going on...?
---
So, the method is called in `bootstrap.php` and the same key is provided as what was given to the `bind()` method. Then in the service container the `resolve()` method:
- Checks if the `$key` exists in the `$bindings` array
- If it doesnt, throws a new error providing some feedback
- If it does the `$resolver` variable is assigned the value associated with the `$key` in $bindings (which is the function to build the database)
- Finally, `call_user_function($resolver)` is called and returned. This is a function provided by PHP that executes the function contained in the `$resolver` variable.

### Continued in part two...