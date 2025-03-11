# Environments and Configuration Flexibility

Programming is not only about writing code that functions but also making that code elegant, flexible and easy to work with. It often involves going over the same code repeatedly, making minor adjustments each time to make it better. One common way that code is improved is by making it more modular, thereby improving it's usability and versatility, particularly for different environments.

## Refactor Database Class
By ctrl + clicking on a built-in class name you can view the class structure. In the case of the PDO class this reveals that in addition to the `$dsn` string, it also accepts a number of other parameters.

```php
public function __construct(
    #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $dsn,
    #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $username = null,
    #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $password = null,
    #[LanguageLevelTypeAware(['8.0' => 'array|null'], default: '')] $options = null
) {}
```

Using this approach the credentials can be moved out of the `$dsn` string and directly into the `PDO` class instance.

```php
class Database {
    public $connection;

    public function __construct()
    {
        $dsn = "mysql:host=localhost;port=3306;dbname=myDatabase;charset=utf8mb4";
        
        $this->connection = new PDO($dsn, 'root', 'password', [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]); // Options is an array but could be an object?
    }
}
```
The options parameter can be an array. This can inlcude things like the `fetchAll(PDO::FETCH_ASSOC)` method call constant that was used earlier, meaning that it doesn't have to be specified for every query.

## Build a dynamic $dsn string
The same approach can be applied to building the dsn string using the `http_build_query()` function. Although intended for building query strings for url's, by ctrl + clicking on the function name you can see there are a number of possible parameters. 

```php
function http_build_query(object|array $data, string $numeric_prefix = "", ?string $arg_separator = null, int $encoding_type = PHP_QUERY_RFC1738): string {}
```

These can be used to stitch the `$dsn` together dynamically by passing in variables.

#### Create a config array
Take the remaining data from the `$dsn` string and add it to an array within the `Database` class:

```php
class Database {
    public $connection;

    public function __construct()
    {
        $config = [
            'host' => 'localhost',
            'port' => 3306,
            'dbname' => 'myDatabase',
            'charset' => 'utf8mb4',
        ],

        ...
    }
}
```

This means the only part of the `$dsn` that isnt dynamic now is the database type, which can be concatenated onto the config data in the query builder function

```php
$dsn = "mysql:" . http_build_query($config, '', ';');
```

### Scope Resolution Operator
The double colon after PDO.

```php
$statement->fetchAll(PDO::FETCH_ASSOC);
```

## Even Greater Flexibility
This can be made even more felxible by pushing the $config data out of the Database class entirely, similar to environment variables.

Move `$config` into it's own file, `config.php` at the root level. Instead of assigning the array to a variable, simply `return` it.

```php
return [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'myDatabase',
    'charset' => 'utf8mb4',
],
```

Then in `index.php`, require it as an instance.
```php
$config = require("config.php");
```

### Additional Notes:
The double colon used earlier on the PDO class instance is called the `Scope Resolution Operator`

```php
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);
```

It gives access to a static or constant that was defined on the class. These can also be seen by ctrl + clicking on the PDO class name in a php file which will open the class definition.

```php
/**
 * Specifies that the fetch method shall return each row as an object with
 * variable names that correspond to the column names returned in the result
 * set. <b>PDO::FETCH_LAZY</b> creates the object variable names as they are accessed.
 * Not valid inside <b>PDOStatement::fetchAll</b>.
 * @link https://php.net/manual/en/pdo.constants.php#pdo.constants.fetch-lazy
 */
public const FETCH_LAZY = 1;

/**
 * Specifies that the fetch method shall return each row as an array indexed
 * by column name as returned in the corresponding result set. If the result
 * set contains multiple columns with the same name,
 * <b>PDO::FETCH_ASSOC</b> returns
 * only a single value per column name.
 * @link https://php.net/manual/en/pdo.constants.php#pdo.constants.fetch-assoc
 */
public const FETCH_ASSOC = 2;
```
