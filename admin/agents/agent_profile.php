<?php

// Start session
session_start();

// Check if the admin is logged in or not
include_once "./admin/admin_includes/check_login.inc.php";

// Database Connection
include_once "functions/functions.php";
$pdo = databaseConnect();

?>

<!-- Header Template -->
<?= header_template('ADMIN | AGENT_PROFILE'); ?>

<div id="body">
    <!-- Admin navbar -->
    <?php include_once "./admin/admin_includes/admin_navbar.inc.php" ?>

    <!-- Page Title & Breadcrumb -->
    <div class="container-fluid">
        <div class="page_title">
            <div class="row">
                <h5>Admin Agent Profile</h5>

                <nava aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Real Estate Agent Profile</li>
                    </ol>
                    </nav>
            </div>
        </div>
    </div>

    <!-- Agent Profile -->
    <!-- Fetch Agent Details from the database based on the id in the URL -->
    <?php
    $sql = $pdo->prepare("SELECT * FROM agent WHERE id = '" . $_GET['id'] . "'");
    $sql->execute();
    $database_agent_profile = $sql->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="container-fluid mt-5">
        <div class="agent_profile">
            <div class="row no-gutters">
                <?php foreach ($database_agent_profile as $agent_profile) : ?>
                    <div class="col-md-4 col-lg-4">
                        <img src="<?= $agent_profile['profileImage']; ?>" alt="<?= $agent_profile['firstName']; ?>" class="img-fluid img-responsive">
                    </div>
                    <div class="col-md-8 col-lg-8">
                        <div class="d-flex flex-column">
                            <div class="data text-dark">
                                <h4><span class="title"><b>Name: </b></span><?= $agent_profile['prefix']; ?> <?= $agent_profile['firstName']; ?> <?= $agent_profile['lastName']; ?></h4>
                                <hr>
                                <h4><span class="title"><b>Email Address: </b></span><?= $agent_profile['emailAddress']; ?></h4>
                                <hr>
                                <h4><span class="title"><b>Phone Number: </b></span>+<?= $agent_profile['phoneNumber']; ?></h4>
                                <hr>
                                <h4><span class="title"><b>Experience: </b></span><?= $agent_profile['yearsOfExperience']; ?></h4>
                                <hr>
                                <h4><span class="title"><b>Rate Card: </b></span>Ksh. <?= $agent_profile['rateCard']; ?></h4>
                            </div>
                            <!-- <div class="p-4">
                                <h3>Contact</h3>
                                <p>+<?= $agent_profile['phoneNumber']; ?></p>
                            </div> -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>