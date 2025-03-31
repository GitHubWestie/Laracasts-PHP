# Resourceful Naming Conventions
This segment focuses on project structure and keeping things organised.

## Notes Files
Currently there are a lot of notes related files. There are three controllers for notes and three views. Group these into their own directory within the controllers and views directories.

The file paths will then need to be updated, starting at the router. In the router the `note-create` endpoint can now be renamed to simply `create` as it exists within its own directory.

## Going Deeper
As these files have now been moved one level deeper into their own sub-directory, the file pathsd for the partials will also need to be updated.

## index
The notes endpoint can now be renamed to `index` as this is a common convention used when displaying all results from a resource.

## show
Another common convention is `show` which is often used for displaying a single result from a resource. In honour of this rename `note.php` files to `show.php`.