# Extract a PHP Dtabase Class

This lesson covers refatoring the previous database connection into a new Database class.

## Create the Class

Classes are created using the class **keyword**, a class **name** and a set of **curly brackets**. By convention the class name always starts with a capital letter.

```php
class Database {

}
```

Functions are then defined within the class which will be accessible via any instance of the class that is created. Functions within a class are called methods. They are the same as functions, just living inside a class.

Methods are the verbs of the programming world. This is useful when thinkng about how to name functions.

### Create an Instance of the Class
```php
$db = new Database;
```

## Create a Method
Within the class, functions can be declared which can then be called by instances of the class.

```php
class Database {
  public function query()
  {
      $dsn = "mysql:host=localhost;port=3306;user=root;dbname=myDatabase;charset=utf8mb4";

      $pdo = new PDO($dsn);

      $statement = $pdo->prepare("SELECT * FROM psosts");

      $statement->execute();

      return $statement->fetchAll(PDO::FETCH_ASSOC);
  }
}
```

When accessing a class method on a class instance PHP uses an arrow syntax.
```php
// Create class instance
$db = new Database;

// Call class method
$posts = $db->query();
```

## Make it Flexible
The above example isnt very flexible as it simply calls a query method with a hardcoded query. To avoid this the query can accept a variable.

```php
// Connect to the databse and execute a query
class Database {
  public function query($query)
  {
    $dsn = "mysql:host=localhost;port=3306;user=root;dbname=myDatabase;charset=utf8mb4";

    $pdo = new PDO($dsn);

    $statement = $pdo->prepare($query);

    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }
}

// Instantiate the database
$db = new Database;

// Call query method in databse instance and provide query
$db->query("SELECT * FROM posts");
```

Now the query has been moved to the method call and can accept any query.

## One Time Thing
Currently, everytime this class is used to make a query on the database it initialises a new connection to the database by creating the `$dsn` and making an instance of the `PDO class`. This isn't ideal. Especially for a website where users might make hundreds or thousands of queries. 

In order to make the connection element of the class a one time thing, the `__construct` method can be used. Then , subsequent queries will use that connection instead of creating a new connection for each query.

```php
class Database
{
  public function __construct()
  {
    $dsn = "mysql:host=localhost;port=3306;user=root;dbname=myDatabase;charset=utf8mb4";

    $pdo = new PDO($dsn);
  }
}
```

In doing this though, the `$pdo` variable which contains the databse connection is moved into a different scope as it is now in its own function. To overcome this and make the connection visible to the `query()` method, an instance property can be used.

### What's that?
An instance property is simply a property that is defined within a class that relies on the class itself to be created. They are variables that belong to the instance of a class. 

```php
class Database
{
  public $connection

  public function __construct()
  {
    $dsn = "mysql:host=localhost;port=3306;user=root;dbname=myDatabase;charset=utf8mb4";

    $this->connection = new PDO($dsn);
  }
}
```
*Rememeber that properties are accessed using the* `$this` *syntax and the property name **doesn't** require a* `$` *when being referenced in this way*

## Fetch vs. FetchAll
The last thing which is still a bit restrictive is the `fetchAll()` method called on the prepared statement. `FetchAll()` returns an array and so if only one result is expected this is unnecessary and makes accessing the data more awkward than it has to be as it ends up nested inside an array.

The immediate solution is to move the fetch() method call outside of the class to make it dynamic. This way it can be specified at the time of calling the query() method and creating an instance of the results.

```php
$posts = $db->query("SELECT * FROM posts WHERE id=1")->fetch();
```

## Create a Database.php File
Move all this into it's own file at the root level and `require()` it in the `index.php` file. When naming a php file that only contains classes conventions state that the filename shgould be capitalised.