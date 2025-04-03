# Namespacing

One way to think of namespacing is in the context of a digital music collection. Typically a digital music collection would be stored in folders representing artists, genres or some other broad category. This would be as granular as you could be bothered to make it but one of the main reasons for this is to make it easy to find what you're looking for and more importantly, avoid clashing. Two or more artists may have songs with exactly the same name but a file title must be unique so those songs couldn't exist in the same directory (or namespace).

## Create a Namespace
A namespace must be declared at the beginng of the file before anything else with the exception of `declare`. To declare a namespace just use the `namespace` keyword.

```php
<?php

namespace Core;

class Database {
    // 
}
```
*The class `Database` now exists inside the `Core` namespace*

Some classes exist in the global space such as `PDO` which is provided by PHP. As the PDO class is used inside the Database class PHP will also look for it in the Core namespace but it doesn't exist there. One approach to fix this is to use a backslash before any reference to the PDO class.

```php
class Database {
    $db = new \PDO($dsn);
}
```

This could quickly get repetitive though and doesn't scale well so a better approach is to implement the `use` keyword again on the PDO class
```php
<?php

use Core;
use PDO;

class Database {
    // 
}
```

## Use the Namespace
Once a class is inside a namespace the namespace will have to be `use`d in any files where that class is referenced. To use a namespace just use the `use` keyword at the top of the file follwed by the namespace name.
```php
<?php

use Core;

$db = new Database();
```
## A Practicle Example
With small projects it may seem difficult to understand why namespaces might be useful but considering larger codebases where third party classes may be introduced from libraries where there is no control over class names etc it starts to make more sense.

PHP itself doesn’t enforce any connection between directories and class names. Namespaces provide an explicit way to scope things at the language level, independent of file structure.
```php
namespace App\Logging;

class Logger {
    public function log($message) {
        echo "App log: $message";
    }
}
```
```php
namespace ThirdParty\Logging;

class Logger {
    public function log($message) {
        echo "Third-party log: $message";
    }
}
```
Without namespaces, if both Logger classes existed in the same project, they’d clash. Even if they were in separate directories, PHP wouldn’t inherently know which one to use. You’d have to manually include the correct file and make sure it didn’t conflict.