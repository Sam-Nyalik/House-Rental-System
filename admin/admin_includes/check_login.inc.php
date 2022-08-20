<?php

if ($_SESSION['admin_loggedIn'] !== true) {
    header("location: index.php?page=admin/account/login");
    exit;
}
