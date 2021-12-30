<?php

// Page routing(Make home.php be the default homepage)
include_once "functions/functions.php";
$pdo = databaseConnect();

$page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';

// Include and show the requested page
include_once $page . '.php';
