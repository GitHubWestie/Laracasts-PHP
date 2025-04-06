# Handle Multiple Request Methods From a Controller Action?

This lesson discusses the correct approach for submitting delete request conforming to RESTful architectural styles. This response on RESTful api's from [Stack Overflow](https://stackoverflow.com/questions/671118/what-exactly-is-restful-programming) explains these principles well but is summarised here:

An architectural style called REST (Representational State Transfer) advocates that web applications should use HTTP as it was originally envisioned. Lookups should use `GET` requests. `PUT`, `POST`, and `DELETE` requests should be used for mutation, creation, and deletion respectively.

## Delete
Currently there is no way to delete a note in the app. One approach could be to add an anchor tag for the delete function. But this would be wrong for a RESTful approach. GET requests should always be idempotent which means they should be the same whether they are requested once or a thousand times.

Another option then would be to use a form and a button that sends a `POST` request to a specific endpoint for deleting notes. Although common this too is technically not the best solution as it doesnt confrom to a RESTful approach.

A RESTful approach states that each reqest should have it's own type (`GET`, `PUT`, `PATCH`, `DELETE`) but modern browsers only support GET and POST.

For now the app will need to be told how to handle the POST request but this will be changed later.

**notes.show view**
```html
  <form class="mt-6" method="POST">
    <input type="hidden" name="id" value="<?= $note['id'] ?>"> <!-- Input field contains the $note['id'] and is submitted with the post request -->
    <button class="text-sm text-red-500">Delete</button>
  </form>
```

**notes.show controller**
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Deletes the note if request type is post and user_id matches
    $note = $db->query('select * from notes where id = :id', [
        'id' => $_GET['id']
    ])->findOrFail();

    authorise($note['user_id'] === $currentUser_id);

    $db->query('delete from notes where id = :id', [
        'id' => $_GET['id']
    ]);

    header('location: /notes');
    exit();
} else {
    $note = $db->query('select * from notes where id = :id', [
        'id' => $_GET['id']
    ])->findOrFail();

    authorise($note['user_id'] === $currentUser_id);

    view("notes/show.view.php", [
        'heading' => 'Note',
        'note' => $note
    ]);
}
```