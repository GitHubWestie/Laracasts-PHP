<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
    <style>
        body {
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            font-family: sans-serif;
        }
    </style>
</head>
<body>
    <?php
        $name = 'Dark Matter';
        $read = true;

        if($read) {
            $message = "You have read $name";
        } else {
            $message = "You've got some reading to do!";
        }
    ?>
    <h1>
        <?= $message ?>
    </h1>

    <a href="books.php">Recommended Books</a>
</body>
</html>