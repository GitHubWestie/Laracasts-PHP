# PDO First Steps

A PDO is a PHP Data Object. The PDO is created by creating an instance of the PDO class using the data for the database to be connected to. 

Classes are like blueprints and objects are instances of those classes. 

```php
class Person
{
    public $name;
    public $age;

    public function breathe() {
        $this->name . " is breathing";
    }
}
// The $this keyword is a keyword that refers to the current instance of the class
```
*This Person class provides a blueprint for a person*

To use the class it needs to be instantiated.

```php
$person = new Person(); // Creates a new person object from the class

$person->name = "Jack"; // Assigns a value to the name property of this Person instance
$person->age = 23; // Assigns a value to the age property of this Person instance

$person->breathe(); // Calls the breathe method from the class
```

## Create a PDO

In the case of the PDO the class already exists in PHP but the properties need to be assigned. This is done using a `$DSN` or `Data Source Name`. A Data Source Name is a string that contains the data needed for the PDO such as `host`, `port`, `database name` and more.

```php
$dsn = "mysql:host=localhost;port=3306;user=<user>;password=<password>;dbname=beginner_php;charset=utf8mb4";
```

Then create an instance of the `PDO` class and give it the `$DSN`
```php
$pdo = new PDO($dsn);
```

## Prepared a Statement
To get any data from the database a `prepared statement` is required. This is essentially a SQL query given to the class instance using the `prepare()` method from the PDO class.

```php
$statement = $pdo->prepare("select * from posts");
```

The prepared statement is then executed
```php
$statement->execute();
```

## Fetch
Finally the results need to be fetched
```php
$posts = $statement->fetchAll();
```
And for easy viewing and sanity checks can be dump n died

`dd($posts)`

## Seeing Double? 👀
By default `fetchAll()` will return an array key AND an array index so the results might look like this:

```sql
array(3) {
  [0]=>
  array(6) {
    ["id"]=>
    int(1)
    [0]=>
    int(1)
    ["title"]=>
    string(18) "My first blog post"
    [1]=>
    string(18) "My first blog post"
    ["body"]=>
    string(0) ""
    [2]=>
    string(0) ""
  }
}
```

This can be prevented by being explicit with the `fetchAll()` method

```php
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);
```

This will then only return an associative array.
```sql
array(3) {
  [0]=>
  array(3) {
    ["id"]=>
    int(1)
    ["title"]=>
    string(18) "My first blog post"
    ["body"]=>
    string(0) ""
  }
}
```