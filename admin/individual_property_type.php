<?php

// Start a session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if the admin is logged in or not
include_once "./admin/admin_includes/check_login.inc.php";

// Database Connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$propertyType = $success = "";
$propertyType_error = $error = "";

// Process user input when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate PropertyType
    if (empty(trim($_POST['propertyType']))) {
        $propertyType_error = "Field is required!";
    } else {
        // Check if the property type already exists in the database
        // Prepare a SELECT statement
        $sql = "SELECT id FROM property_type WHERE name = :propertyType";

        if ($stmt = $pdo->prepare($sql)) {
            // bind variables to the prepared statement as parameters
            $stmt->bindParam(":propertyType", $param_propertyType, PDO::PARAM_STR);
            // Set parameters
            $param_propertyType = trim($_POST['propertyType']);
            // Attempt to execute
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $propertyType_error = "Property type name already exists!";
                } else {
                    $propertyType = trim($_POST['propertyType']);
                }
            }

            // Close the statement
            unset($stmt);
        }
    }

    // Check for errors before dealing with the database
    if (empty($propertyType_error)) {
        // Prepare an UPDATE statement
        $sql = "UPDATE property_type SET name = :propertyType WHERE id = :id";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":propertyType", $param_propertyType, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            // Set parameters
            $param_propertyType = $propertyType;
            $param_id = $_GET['id'];
            // Attempt to execute
            if ($stmt->execute()) {
                $success = "Property Type name has been updated successfully!";
            } else {
                $error = "Seems like there is an error. Our developers are on it!";
            }

            // Close the statement
            unset($stmt);
        }
    }
}

?>

<!-- Header template -->
<?= header_template('ADMIN | INDIVIDUAL_PROPERTY_TYPE'); ?>

<div id="body">
    <!-- Admin Navbar -->
    <?php include_once "./admin/admin_includes/admin_navbar.inc.php"; ?>

    <!-- Fetch property type from the database with the ID in the URL -->
    <?php
    $sql = $pdo->prepare("SELECT * FROM property_type WHERE id = '" . $_GET['id'] . "'");
    $sql->execute();
    $database_property_type = $sql->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php foreach ($database_property_type as $property_type) : ?>

        <!-- Page Title & Breadcrumb -->
        <div class="container-fluid">
            <div class="page_title">
                <div class="row">
                    <h5>Admin Individual Property Type</h5>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php?page=admin/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?= $property_type['name']; ?> Property Type</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Individual Property Type -->
        <div class="container-fluid">
            <div id="profile">
                <div class="row">
                    <div class="col-md-6">
                        <div class="profile_side1">
                            <div class="title">
                                <h5><i class="bi bi-code"></i> Edit Property Type</h5>
                                <hr>
                            </div>

                            <form action="index.php?page=admin/individual_property_type&id=<?= $property_type['id']; ?>" method="post" class="profile_edit">
                                <!-- Error message -->
                                <div class="form-group">
                                    <span class="errors text-danger">
                                        <?php
                                        if ($error) {
                                            echo $error;
                                        }
                                        ?>
                                    </span>
                                </div>

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
                                <!-- Individual Property Type -->
                                <div class="form-group">
                                    <label for="Property Type">Property Type</label>
                                    <input type="text" name="propertyType" value="<?= $property_type['name']; ?>" class="form-control 
                                <?php echo (!empty($propertyType_error)) ? 'is-invalid' : ''; ?>">
                                    <span class="errors text-danger"><?php echo $propertyType_error; ?></span>
                                </div>

                                <!-- Submit Btn -->
                                <div class="form-group my-3">
                                    <input type="submit" value="Edit Type" class="btn w-100">
                                </div>

                                <!-- Delete Btn -->

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>