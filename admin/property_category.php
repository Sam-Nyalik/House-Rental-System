<?php

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if the admin is logged in
include_once "./admin/admin_includes/check_login.inc.php";

// Database connection & functions
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$propertyCategoryName = $propertyCategoryImage = $success = "";
$propertyCategoryName_error = $propertyCategoryImage_error = $error = "";

// Process user input when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate propertyCategoryName
    if (empty(trim($_POST['propertyCategoryName']))) {
        $propertyCategoryName_error = "Field is required!";
    } else {
        // Check if the property category name already exists
        // Prepare a SELECT statement
        $sql = "SELECT id FROM property_category WHERE name = :propertyCategoryName";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":propertyCategoryName", $param_propertyCategoryName, PDO::PARAM_STR);
            // Set parameters
            $param_propertyCategoryName = trim($_POST['propertyCategoryName']);
            // Attempt to execute
            if ($stmt->execute()) {
                if ($stmt->rowCount() >= 1) {
                    $propertyCategoryName_error = "Product Category already exists!";
                } else {
                    $propertyCategoryName = trim($_POST['propertyCategoryName']);
                }
            }

            // Close the statement
            unset($stmt);
        }
    }

    // Check for errors before dealing with the database
    if (empty($propertyCategoryName_error)) {
        // Validate and Process the file / image field
        if (!empty($_FILES['propertyCategoryImage'])) {
            move_uploaded_file($_FILES['propertyCategoryImage']['tmp_name'], "./admin/propertyCategoryImages/" . $_FILES['propertyCategoryImage']['name']);
            $propertyCategoryImage = "./admin/propertyCategoryImages/" . $_FILES['propertyCategoryImage']['name'];

            // Prepare an INSERT statement
            $sql = "INSERT INTO property_category(name, propertyCategoryImage) VALUES(:propertyCategoryName, :propertyCategoryImage)";

            if ($stmt = $pdo->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":propertyCategoryName", $param_propertyCategoryName, PDO::PARAM_STR);
                $stmt->bindParam(":propertyCategoryImage", $param_propertyCategoryImage, PDO::PARAM_STR);
                // Set parameters
                $param_propertyCategoryName = $propertyCategoryName;
                $param_propertyCategoryImage = $propertyCategoryImage;
                // Attempt to execute
                if ($stmt->execute()) {
                    // Success message
                    $success = "Product Category name and image have been added successfully!";
                } else {
                    $error = "There was an error. Our developers are on it!";
                }

                // Close the statement
                unset($stmt);
            }
        } else {
            $propertyCategoryImage_error = "Field is required!";
        }
    }
}


?>

<!-- Header Template -->
<?= header_template('ADMIN | PROPERTY_CATEGORIES'); ?>

<div id="body">
    <!-- Admin Navbar -->
    <?php include_once "./admin/admin_includes/admin_navbar.inc.php"; ?>

    <!-- Page Title & Breadcrumb-->
    <div class="container-fluid">
        <div class="page_title">
            <div class="row">
                <h5>Admin Property Categories</h5>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Property Categories</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Product Categories -->
    <div class="container-fluid">
        <div id="profile">
            <div class="row">
                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5><i class="bi bi-plus-lg"></i> Add Category</h5>
                            <hr>

                            <form action="index.php?page=admin/property_category" method="post" enctype="multipart/form-data" class="profile_edit">
                                <!-- Success Message -->
                                <div class="form-group">
                                    <span class="errors text-success">
                                        <?php
                                        if ($success) {
                                            echo $success;
                                        }
                                        ?>
                                    </span>
                                </div>

                                <!-- Error Message -->
                                <div class="form-group">
                                    <span class="errors text-danger">
                                        <?php
                                        if ($error) {
                                            echo $error;
                                        }
                                        ?>
                                    </span>
                                </div>

                                <!-- Propduct Name -->
                                <div class="form-group">
                                    <label for="PropertyCategoryName">Property Category Name</label>
                                    <input type="text" name="propertyCategoryName" class="form-control 
                                    <?php echo (!empty($propertyCategoryName_error)) ? 'is-invalid' : ''; ?>">
                                    <span class="errors text-danger"><?php echo $propertyCategoryName_error; ?></span>
                                </div>

                                <!-- PropertyCategoryImage -->
                                <div class="form-group">
                                    <label for="PropertyCategoryImage">Property Category Image</label>
                                    <input type="file" name="propertyCategoryImage" class="form-control 
                                    <?php echo (!empty($propertyCategoryImage_error)) ? 'is-invalid' : ''; ?>">
                                    <span class="errors text-danger"><?php echo $propertyCategoryImage_error; ?></span>
                                </div>

                                <!-- Submit Btn -->
                                <div class="form-group my-3">
                                    <input type="submit" value="Add Category" class="btn w-100">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5>Property Category Details</h5>
                            <hr>
                        </div>

                        <!-- Prepare a SELECT statement to fetch all the product category images from the database -->
                        <?php
                        $sql = $pdo->prepare("SELECT * FROM property_category ORDER BY dateAdded DESC");
                        $sql->execute();
                        $database_propertyCategoryDetails = $sql->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <?php foreach ($database_propertyCategoryDetails as $details) : ?>
                            <div class="row">
                                <div class="category">
                                    <div class="col-md-6">
                                        <a href="index.php?page=admin/specific_property_categories&id=<?= $details['id']; ?>"> <img src="<?= $details['propertyCategoryImage']; ?>" alt="<?= $details['name']; ?>" class="img-fluid propertyCategoryImage">
                                            <h5 class="text-center propertyCategoryTitle"><?= $details['name']; ?></h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Footer Template -->
<?= footer_template(); ?>