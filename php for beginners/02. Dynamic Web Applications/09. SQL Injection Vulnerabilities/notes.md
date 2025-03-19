# SQL Injection Vulnerabilities

Bascially, the TL:DR; of this was to never inline user input as part of a database query. It opens up significant security flaws.

When accepting a users input as part of a database query the input should be kept separate from the query.

This example introduces security vulnerabilities by passing user entered data directly into a SQL query

```php
$id = $_GET['id']; // If a user enters id=1 into the url as a query string it would become the value of $id

$query = "SELECT * FROM posts WHERE id = {$id}"; // Query string from user is injected directly into the database query

$posts = $db->query($query)->fetch();
```

## Everyone's a Suspect! 🚨
Initially this may not seem like a big deal but it must be assumed that every user is a malicious user. Guilty until proven innocent. 

So while this query is harmless, if a user were to enter something like this into the url query string instead:

```
http://localhost:8888/?id=1;drop table users;
```

The resulting query would be:

```php
$id = $_GET['id']; // Now contains 'id=1;drop table users;'
$query = "SELECT * FROM posts WHERE id = {$id}"; // Passes query string into database query and users table goes pop!

$posts = $db->query($query)->fetch();
```

Web applications need to be designed defensively to prevent malicious users from exploiting security vulnerabilities like this.

## Safe SQL
In order to do this safely the query needs to be sent separately from the query string.

```php
$query = "SELECT * FROM posts WHERE id = ?";

$posts = $db->query($query, [$id])->fetch();
```

**or**

```php
$query = "SELECT * FROM posts WHERE id = :id";

$posts = $query->fetch($query, [':id' => $id]);
```
These examples replace the previously inlined value with a wildcard.

Then, the `query()` method signature in the `Database` class can be updated to accept additional, separate data.

```php
public function query($query, $params = [])
{
    $statement = $this->connection->prepare($query);

    $statement->execute($params);

    return $statement;
}
```