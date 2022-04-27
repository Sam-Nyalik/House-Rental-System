<?php

// Database Connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Fetch individual product from the datbase based on the unique ID in the url
$id = false;
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = $pdo->prepare("SELECT * FROM all_properties WHERE id = $id");
    $sql->execute();
    $individual_property = $sql->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!-- Header Template -->
<?= header_template('PROPERTY'); ?>

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
                            <li><a href="index.php?page=all_properties/rentals" class="dropdown-item">Rentals</a></li>
                            <li><a href="index.php?page=all_properties/on_sale" class="dropdown-item">On Sale</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
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
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Contact</a>
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

<?php foreach ($individual_property as $single_property) : ?>
    <!-- Individual Property -->
    <div class="individual_property_header">
        <div class="container-fluid">
            <div class="row">
                <img src="<?= $single_property['propertyImage5']; ?>" alt="<?= $single_property['propertyName']; ?>" class="img-fluid img-responsive">
                <div class="header_title">
                    <h5><?= $single_property['propertyAddress']; ?>, <?= $single_property['propertyLocation']; ?> <br>Price: <?= $single_property['propertyPrice']; ?></h5>
                    <h6></h6>
                </div>
            </div>
        </div>
    </div>

    <div class="individual_property_body">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-5">
                    <div id="individualPropertyCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#individualPropertyCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#individualPropertyCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#individualPropertyCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?= $single_property['propertyImage1']; ?>" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= $single_property['propertyImage2']; ?>" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= $single_property['propertyImage3']; ?>" class="d-block w-100" alt="...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="section_title">
                        <h5>Property Details</h5>
                        <h6><?= $single_property['propertyCategory']; ?>, <?= $single_property['propertyType']; ?>, <?= $single_property['numberOfRooms']; ?>, <?= $single_property['propertySize']; ?></h6>
                        <p><?=$single_property['description'];?></p>
                        <h4><a href="tel:0705740958">Call Owner</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Footer Template -->
<?= footer_template(); ?>