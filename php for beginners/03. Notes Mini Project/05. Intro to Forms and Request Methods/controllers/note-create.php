<?php

$heading = "Create a Note";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    dd($_POST);
}

require("views/note-create.view.php");