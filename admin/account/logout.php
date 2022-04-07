<?php
// Start session
session_start();

// Unset all session variables
unset($_session['id']);
unset($_SESSION['admin_loggedIn']);

// Redirect admin to the login page
header("location: index.php?page=home");
exit;
