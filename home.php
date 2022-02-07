<?php

include_once "functions/functions.php";
$pdo = databaseConnect();

?>

<?= header_template("HOME"); ?>

<div class="container">
    <div class="row">
            <div class="col-md-3">
                <a href="index.php?page=admin/account/login">Admin Login</a>
            </div>
            <div class="col-md-3">
                <a href="index.php?page=agent/account/login">Agent Login</a>
            </div>
        </div>
    </div>