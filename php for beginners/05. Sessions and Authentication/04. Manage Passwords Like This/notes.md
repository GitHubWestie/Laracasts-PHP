# Manage Passwords Like This For The Rest of Your Career
One thing that should never be done when handling user data is to store a password in clear text. It is a huge security risk. Instead passwords should be hashed to encrypt the contents and hide the true password from a malicious user should a database ever be breached.

## Bcrypt
Fortunately PHP has a built in function for encrypting passwords `password_hash()`.

**controllers/store.php**
```php
    $db->query("INSERT INTO users(email, password) VALUES(:email, :password)", [
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
    ]);
```

The `password_hash()` function requires two arguments. The first is the password that needs to be hashed and the second is the encryption algorithm that will be used to encrypt the password. A thrid, optional argument can be given in the form of an assoc array if needed.

Now, whenever a password is saved to the database it will look like absolute nonsense. 👍🏻