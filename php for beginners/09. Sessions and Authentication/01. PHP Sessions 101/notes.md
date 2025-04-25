# Sessions 101

A session is defined as a period devoted to a particular activity. In the context of a website this would be a period that a user interacts with the site.

## Session Superglobal
Like the other superglobals already used in this project, there is also one for sessions `$_SESSION`. A session will hold data temporarily. Generally for the duration of the users visit to the site. Often when the browser is closed or some other action is performed such as logging out, the session will be destroyed.

## Initiatethe Session
The `$_SESSION` superglobal is not avaialable for use by default. To be able to store data in the `$_SESSION` it needs to be started. Generaly this will be done as early as possible in the users visit. Once started the session can store data.

```php
session_start();

$_SESSION['name'] => 'Dave';
```

This could then be used for example to dynamically display a welcome message to the user on login or on the profile page etc. Remember though that the `$_SESSION` data is temprorary so depending on the use of it, it may be worth having fallbacks in place for instances where the `$_SESSION` isnt available.

```html
<div>
    <h1>Welcome, <?= $_SEESION['name'] ?? 'Guest' ?></h1>
</div>
```

## What's Going On?
*Note: Dont be tempted in by the `Session Storage` tab. This is actually part of a JavaScript API*

A view of what the session is can be accessed via the browsers dev tools. In dev tools go to the `Application`->`cookies` or `storage`->`cookies` depending on the browser.

A file containing session data is stored on the server side and a `cookie` is stored browser side. Communication between these is essential for the session data persisting for the duration of the users visit. If the session cookie were to be manually deleted from the browser, the next time a request is made (navigate to another page for example) that data wouldnt be included and the server would respond by resetting the session.

How and where the data is stored server side will differ slightly depending on the server. In the case of a localhost server they will be stored somewhere on the localhost machine. PHP may have somewhere set up for this in the .ini file. If not it will usually default to the temp directory. 

**Terminal**
```
php --info
```

And look for `session.save_path => no value => no value` which may or may not have a value. If it doesnt try running this in the console too:

```
echo $TMPDIR
```

**or**

```
php -r "echo sys_get_temp_dir();"
```

This *may* give the path to the temp directory. If not a quick google should be able to reveal its location. The file will begin with `sess_` followed by the session id which can be crossreferenced from the dev tools.

Once found this file can be dropped into the IDE to view the contents. 

Otherwise, update the php.ini file and specify a path for the `session.save_path` manually.