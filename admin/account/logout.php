<?php

// Destroy all sessions
$_SESSION = array();

session_destroy();

// Redirect admin to the login page
header("location: index.php?page=admin/account/login");
exit;
