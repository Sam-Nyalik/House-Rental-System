<?php

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if the admin is logged in(If not refer him/her to the login page)
include_once "./admin/admin_includes/check_login.inc.php";

// Database Connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$propertyType = $success = "";
$propertyType_error = $error = "";

// Process user input when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate PropertyType
    if (empty(trim($_POST['propertyType']))) {
        $propertyType_error = "Field is required!";
    } else {
        // Check if the propertyType input already exists
        $sql = "SELECT id FROM property_type WHERE name = :propertyType";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":propertyType", $param_propertyType, PDO::PARAM_STR);
            // Set parameters
            $param_propertyType = trim($_POST['propertyType']);
            // Attempt to execute
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $propertyType_error = "Property Type already exists!";
                } else {
                    $propertyType = trim($_POST['propertyType']);
                }
            }

            // Close the prepared statement
            unset($stmt);
        }
    }

    // Check for errors before dealing with the database
    if (empty($propertyType_error)) {
        // Prepare an INSERT statement
        $sql = "INSERT INTO property_type(name) VALUES(:propertyType)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":propertyType", $param_propertyType, PDO::PARAM_STR);
            // Set parameters
            $param_propertyType = $propertyType;
            // Attempt to execute
            if ($stmt->execute()) {
                $success = "Property Type has been added successfully!";
            } else {
                $error = "There is an error. Hang tight, our developers are on it!";
            }

            // Close the prepared statement
            unset($stmt);
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('ADMIN | PROPERTY_TYPES'); ?>

<div id="body">
    <!-- Admin Navbar -->
    <?php include_once "./admin/admin_includes/admin_navbar.inc.php"; ?>

    <!-- Page Title & Breadcrumb -->
    <div class="container-fluid">
        <div class="page_title">
            <div class="row">
                <h5>Admin Property Types</h5>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Property Types</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Property Types -->
    <div class="container-fluid">
        <div id="profile">
            <div class="row">
                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5><i class="bi bi-plus-lg"></i> Add Type</h5>
                            <hr>
                        </div>

                        <form action="index.php?page=admin/property_type" method="post" class="profile_edit">
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

                            <!-- Property Type -->
                            <div class="form-group">
                                <label for="AddPropertyType">Add Property Type</label>
                                <input type="text" name="propertyType" class="form-control 
                                <?php echo (!empty($propertyType_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $propertyType_error; ?></span>
                            </div>

                            <!-- Submit Btn -->
                            <div class="form-group my-3">
                                <input type="submit" value="Add Type" class="btn w-100">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5><i class="bi bi-code"></i> All Property Types</h5>
                            <hr>
                        </div>

                        <!-- All property types -->
                        <?php
                        // Fetch all property types from the database
                        $sql = $pdo->prepare("SELECT * FROM property_type");
                        $sql->execute();
                        $count = 1;
                        $database_property_type = $sql->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Date Added</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <?php foreach ($database_property_type as $property_type) : ?>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?= $property_type['name']; ?></td>
                                            <td><?= $property_type['dateAdded']; ?></td>
                                            <td>
                                                <a href="index.php?page=admin/individual_property_type&id=<?= $property_type['id']; ?>"><i class="bi bi-eye"></i></a> | <a href="index.php?page=admin/delete_property_type&id=<?= $property_type['id']; ?>" onclick="return confirm('Are you sure you want to delete <?= $property_type['name']; ?> as a property type?')"><i class="bi bi-trash text-danger"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Template -->
<?= footer_template(); ?>