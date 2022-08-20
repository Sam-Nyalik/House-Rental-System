<?php

// Start session
session_start();

include_once "functions/functions.php";
$pdo = databaseConnect();

?>

<?= header_template("ALL_PROPERTIES | RENTALS"); ?>

<!-- Main Header -->
<div id="header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Company Name -->
            <!-- Prepare a SELECT statement from the database to fetch the company name -->
            <?php
            $sql = $pdo->prepare("SELECT companyName FROM company_details WHERE id = 1");
            $sql->execute();
            $database_company_name = $sql->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($database_company_name as $companyName) : ?>
                <a class="navbar-brand" href="index.php?page=home"><?= $companyName['companyName']; ?></a>
            <?php endforeach; ?>

            <div class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent" aria-controls="navContent" aria-expanded="false" aria-label="Toggle navigation">
                <div class="navbarToggler1"></div>
                <div class="navbarToggler1"></div>
                <div class="navbarToggler1"></div>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php?page=home">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" id="propertiesDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Properties</a>
                        <ul class="dropdown-menu" aria-labelledby="propertiesDropdown">
                            <li><a href="index.php?page=all_properties/all" class="dropdown-item">All Properties</a></li>
                            <li class="dropdown-active"><a href="index.php?page=all_properties/rentals" class="dropdown-item">Rentals</a></li>
                            <li><a href="index.php?page=all_properties/on_sale" class="dropdown-item">On Sale</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <?php
                        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
                            $userId = false;
                            if (isset($_SESSION['id'])) {
                                $userId = $_SESSION['id'];
                            }
                            // Fetch user details from the database
                            $sql = $pdo->prepare("SELECT * FROM users WHERE id = $userId");
                            $sql->execute();
                            $database_user_details = $sql->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                            <?php foreach ($database_user_details as $user_details) : ?>
                                <a class="nav-link dropdown-toggle" href="#" id="accountsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <!--Accounts--> <?= $user_details['firstName'] ?> <?= $user_details['lastName']; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="accountsDropdown">
                                    <li><a class="dropdown-item" href=""><i class="bi bi-person"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href=""><i class="bi bi-shield-lock"></i> Booked</a></li>
                                    <hr>
                                    <li><a href="index.php?page=user/account/logout" class="dropdown-item"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
                                    <!-- <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li> -->
                                </ul>
                            <?php endforeach; ?>
                        <?php } else { ?>
                            <a class="nav-link dropdown-toggle" href="#" id="accountsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Accounts
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="accountsDropdown">
                                <li><a class="dropdown-item" href="index.php?page=admin/account/login">Admin Login</a></li>
                                <li><a class="dropdown-item" href="index.php?page=user/account/login">User Login</a></li>
                                <li><a href="index.php?page=agent/account/login" class="dropdown-item">Agent Login</a></li>
                                <!-- <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li> -->
                            </ul>
                        <?php } ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=contact">Contact</a>
                    </li>
                </ul>
                <!-- <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
            </div>
        </div>
    </nav>
</div>

<!-- Rentals Title -->
<div id="all_rentals_page">
    <div class="container">
        <div class="row">
            <div class="back_button">
                <h6 onclick="history.back()"><i class="bi bi-arrow-left-circle"></i> back</h6>
            </div>
            <div class="section_title" style="margin-bottom: 45px;">
                <h5 class="text-center">Properties On Rent</h5>
                <hr>
            </div>

            <!-- Fetch all properties from the database -->
            <?php
            $sql = $pdo->prepare("SELECT * FROM all_properties WHERE propertyType LIKE '%rental%' ORDER BY date_added DESC");
            $sql->execute();
            $database_properties_on_rent = $sql->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($database_properties_on_rent as $on_rent) : ?>
                <div class="col-md-3">
                    <div class="all_properties_details">
                        <a href="index.php?page=all_properties/individual_property&id=<?= $on_rent['id']; ?>">
                            <img src="<?= $on_rent['propertyImage1']; ?>" alt="<?= $on_rent['propertyName']; ?>" class="img-fluid img-responsive img-top">
                            <h3><?= $on_rent['propertyName']; ?></h3>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Footer Template -->
<?= footer_template(); ?>