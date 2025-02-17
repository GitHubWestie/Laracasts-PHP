# Partials

Partials are great for avoiding duplication and maintaining a clean, easy to read codebase. They allow for splitting up of components into re-useable chunks

A navigation section for example could be isolated into it's own partial and then improted wherever it is required.

**nav.php**
```html
<nav>
    <div>
        <a href="/">Home</a>
        <a href="/about.php">About Us</a>
        <a href="/contact.php">Contact</a>
    </div>
</nav>
```

Then the nav component can be imported into the views that need it using:

```php
<?php require("partials/nav.php") ?>
```

## Controllers

Sometimes a partial may contain an element that needs to be dynamic, such as the title of a page. When this happens the php files associated with the pages can be used to dynamically generate this data.

The php files (`index.php`, `about.php`) etc. ***Not** the view files* can be thought of as controllers.

A controller can be thought of as being responsible for receiving a request and providing a response. In this context a request is simply a visit to the page, where the browser or 'client' makes a request and the controller returns a response.

In this example the `<h1>` element contains 'Home' as a hardcoded property, but this should be dynamic. 

*header partial*
```html
<header class="bg-white shadow-sm">
  <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Home</h1>
  </div>
</header>
```

To get around this, index.php (or if this were another page, whatever controller is associated with that page), can have this assigned to a variable.

**Remember**: Any variable defined in the controller will be made available to the corresponding view.

*index.php*
```php
$heading = 'Home';
```

Then the hardcoded property can be inserted as a dynamic variable in the partial.

```html
<header class="bg-white shadow-sm">
  <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900"><?= $heading ?></h1>
  </div>
</header>
```