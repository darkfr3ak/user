<?php

/**
 * OZ\User logout demo
 */
require 'config.php';

use OZ\User as User;

User::logout();

header('Location: login.php');
exit();
