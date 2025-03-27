# Programming is Rewriting

A large part of programming is rewriting code. The pursuit of efficiency. Get the first draft down, get it functional, then get into the refactor. Make it cleaner, compact, readable etc.

## Taking Ownership
In the `note controller` there's a statement being executed on the database, 
```php
$note = $db->query("SELECT * FROM notes WHERE id = ?", [$_GET['id']])->fetch();
```
And there's a conditional returning an response code and aborting if a note doesn't exist,
```php
if(!$note) {
    abort(Response::NOT_FOUND);
}
```
It would be nice to refactor these into one method but as it stands that can't be done because the `fetch()` method is owned by php and cannot be changed.

## Wrap It Up
One way to tackle this is to wrap the `fetch()` method in a custom method inside the `Database` class. This method can then be given a custom name and also have whatever additional functionality is needed. 

```php
class Database
{
    public $connection;
    public $statement; // Make statement accessible outside of the query function

    // $connection constructor doesn't change

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query); // Add $this to update value of $statement
        
        $this->statement->execute($params); // Same here
        
        return $this;
    }

    /*
     *Then add custom function. Note that php's fetch() is still used
     */
    public function find()
    {
        return $this->statement->fetch();
    }

    public function findorfail()
    {
        $result = $this->find();

        if(!$result) {
            abort(Response::NOT_FOUND);
        }

        return $result;
    }

    public function findAll()
    {
        return $this->statement->fetchAll();
    }
}
```

The conditional that checks if a user owns the note that they are trying to view can also be refactored. 

1. Move it into the functions.php file
2. Create the function:
  ```php
  function authorise($condition, $status = Response::FORBIDDEN) {
    if(!$condition) {
      abort($status);
    }
  }
  ```
3. Then the function can be called in the note controller and passed the condition to check.
```php
authorise($note['user_id'] === $currentUser_id);
```