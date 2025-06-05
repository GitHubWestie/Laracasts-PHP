<?php

// Log the user out

use Core\Authenticator;

Authenticator::logout();

redirect('/');