# Always Escape Untrusted Inputs

In this lesson, we'll finally persist a new note to the database. But, in doing so, you'll be introduced to a new security concern that requires us to always escape user-provided input.

## Adding a Note to the Database
As the database sin't yet available globally via a service container or similar the database setup needs to be brought into the `note-create controller`. 

```php
$config = require("config.php");
$db = new Database($config['database'], $config['database']['user'], $config['database']['password']);
```

Then SQL syntax can be used to add the note to the database.

```php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->query("INSERT INTO notes(body, user_id) VALUES(:body, :user_id)", [
        'body' => $_POST['body'],
        'user_id' => 1,
    ]);
}
```
*Tip: To get a reminder of how to insert into the database, create a record manually in table plus then check the history tab. This will show the raw SQL required to create that record*

The `VALUES()` method uses wildcards to protect against SQL injections. The actual valuse are sent through in an associative array as the second argument to the `VALUES()` method. The note body is pulled from the `$_POST` superglobal. For now the user is hardcoded as 1 as session handling hasn;t been covered yet.

The new note should now be persisted in the database.

## More Vulnerabilities...
Separating the query and the data to be inserted into the database is good for stopping malicious SQL injections but a malicious user could still mess with the database in other ways. For example, now that a method of uploading data to the database has been provided via the note creation form a user could include anything in that data. If a user was to upload a note that contained html for example:

```html
Work on <strong class="text-red-500 font-bold">something</strong>
```

Then when that note is displayed in the notes list it would render whatever html is in that note. A user could even open `<script></script>` tags and write some malicious JavaScript in there that would execute when that note is loaded.

## Escaping Inputs
One option to deal with this scenario is escaping the input once it has reached the database. Thankfully this is easily achieved using a built-in function provided by php called `htmlspecialchars()`. By calling this function where the note is displayed in the view, and wrapping it around the database output, everything is essentially converted into a long string and stops anything from being inerpreted as code.

```php
<?= htmlspecialchars($note['body']); ?>
```