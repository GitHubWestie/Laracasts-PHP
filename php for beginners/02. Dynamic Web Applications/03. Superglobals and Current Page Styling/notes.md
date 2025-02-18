# Superglobals and Current Page Styling

Superglobals are variables that are always available from any script or file.

$_SERVER, $_SESSION, $_POST and $_GET are all examples of superglobals

## Inspect a Superglobal 👀

To inspect the contents of a superglobal use the var_dump() method. 

```php
var_dump($_SERVER);
```
*`echo()` cannot be used as it expects a string and superglobals are generally objects or arrays*

Use `<pre>` tags to preserve formatting on a var_dump() superglobal. This makes it a lot easier to read.

```php
echo "<pre>";
var_dump($_SERVER);
echo "</pre>";
```

## Die(🪦)

A method often used with this approach to inspecting variables is `die()`. `die()` simply stops the script or file from executing any more lines of code.

As they are used together so often it makes sense to wrap them all in a function.

```php
function dd($value) {
  echo "<pre>";
  var_dump($_value);
  echo "</pre>";

  die();
}
```
*In Laravel this actually exists out of the box*

## Location

There are many items contained in the $_SERVER superglobal. `["REQUEST_URI"]` is useful for getting the current page.

Using `["REQUEST_URI"]` things can applied conditionally bsed on the page that is currently displayed, such as classes on elements.

```html
<a href="/" class="<?= urlIs('/') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium" aria-current="page">Home</a>
```
*This applies a class based on the current page which is determined using a custom function `urlIs()` on the backend*

```php
function urlIs($value) {
    return $_SERVER['REQUEST_URI'] === $value;
}
```

