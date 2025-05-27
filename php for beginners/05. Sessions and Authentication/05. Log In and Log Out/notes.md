# Log In and Log Out

As usual, setting up a new page requires:

- A link to that page
    - Add `login` link to the nav component

- A route for that link that points to the controller
    - Add a new route for the controller
    ```php
    $router->get('/login', 'controllers/sessions/create.php')->only('guest');
    // Note that this route is using the guest middleware method that was created previously
    ```

- A controller that responds to the route
    - Test that the route is reaching the controller using a quick `dd('Hello!')`

- A view that will be called by the controller
    - For now just duplicate the registration form. In reality it would make more sense for the form to be a component that could be reused to reduce duplication. Change anything that refers to the registration of a user and make sure to change the forms `POST` action to `/sessions`.
    
    *Remember using the resource name is in keeping with RESTful conventions but it could also be `/login` if that isnt a concern.*

## Login Functionality
With the login page built and the form POSTing to the correct endpoint the login functionality can be implemented.

- Add a route for the POST request
    - $router->post('/sessions', 'controllers/sessions/store.php')->only('guest');

- Add a controller to respond to that route
    - Test that the route is reaching the controller using a quick `dd('Hello!')` or something and then submit the login form.

In the registration/store.php controller there is already a snippet of code responsible for adding the user to the session upon successful registration. Currently this is essentially the login functionality so this can be re-used. If it's going to be re-used it makes sense to extract that into it's own function. 

**Core/functions.php**
```php
function login($user) {
    $_SESSION['user'] = [
        'email' => $user['email'],
    ];
}
```
A good practice with login functionality is to regenerate the session id, just in case a mallicious user is trying to use an existing session id to do something naughty. That is what the `session_regenerate_id()` method is doing here.

After that the logic is largely the same as the registration controller. The key difference being in the retrieval and comparison of the provided user data to the stored user data, specifically the password. As the stored password is hashed and the user provided password will be the original password, the stored password needs to be de-crypted before it can be compared to the user provided password. Fortunately PHP does the heavy lifting on this too using the `password_verify()` function.

```php
if (password_verify($password, $user['password'])) {
    login([
        'email' => $email,
    ]);

    header('location: /');
    exit();
};
```

The `password_verify()` function takes two parameters and returns a `bool`. The first parameter is the provided password and the second is the stored, hashed password. It then returns `true` or `false` depending on whether the two passwords match or not.

## Logging Out
Now that the user can log in it would be nice to also let them leave. To do this the app needs a logout functionality. Generally speaking this should be done using a `form element` rather than an `anchor tag`, however some developers do use anchor tags, because they're naughty. This comes back to the topic of idempotency which was discussed ealrier.

- Create a form element in nav.php. This will send a delete request using the hidden input method
    ```php
        <?php if($_SESSION['user'] ?? false) : ?>
            <div class="ml-3">
            <form action="/sessions" method="POST">
                <input type="hidden" name="_method" value="DELETE" />
                <button class="text-white">Logout</button>
            </form>
            </div>
        <?php endif; ?>
    ```

- Create a route to listen for the delete request
    ```php
    $router->delete('/sessions', 'controllers/sessions/destroy.php')->only('auth');
    ```

- Create the destroy controller

    Essentially all that has to be done here is the session data needs to be destroyed and the user redirected to a page that anonymous users can access. Again PHP can do most of the heavy lifting here using the session_destroy() function.

    What it doesnt do though is clear the session cookie but this isnt too bad to do. The `setcookie()` method can be used to overwrite the current session cookie with an expired cookie. This way the original session cookie cant be used to return to that users account after they have logged out.

    **controllers/sessions/destroy.php**
    ```php
    <?php

    // Log the user out
    $_SESSION = [];
    session_destroy();

    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    header('location: /');
    exit();
    ```

    ### What's going on?
    ---

    The `setcookie()` method requires several parameters.
    ```php
    function setcookie(string $name, string $value = "", int $expires_or_options = 0, string $path = "", string $domain = "", bool $secure = false, bool $httponly = false): bool {}
    ```
    *Function signature for setcookie()*

    In this case:
    - `$name` is set to 'PHPSESSID', which will overwrite the original
    - `$value` is an empty string,
    - `$expires` is set to a point in time in the past using the `time()` method. This ensures that it expires instantly.
    
    The rest of the parameters are acquired by using the `session_get_cookie_params()` method. This returns an array that contains all of the remaining data required for the `setcookie()` method.

With that done the user should now be able to safely logout. And of course this can all be extracted into it's own function to keep things neat and tidy if you like, but as this will only be written once in this controller it's nto a huge issue to leave it as it is.