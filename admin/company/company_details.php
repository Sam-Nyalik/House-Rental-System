<?php

// Error Handlers
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Start session
session_start();

// Check if the admin is logged in or not
include_once "./admin/admin_includes/check_login.inc.php";

// Database connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$companyName = $success = "";
$companyName_error = $error = "";

// Process user input when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate Company Name
    if (empty(trim($_POST['companyName']))) {
        $companyName_error = "This field is required!";
    } else {
        $companyName = trim($_POST['companyName']);
    }

    // Check for errors before dealing with the database
    if (empty($companyName_error)) {
        // Prepare an INSERT statement
        $sql = "UPDATE company_details SET companyName = :companyName WHERE id = 1";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":companyName", $param_companyName, PDO::PARAM_STR);
            // Set parameters
            $param_companyName = $companyName;
            // Attempt to execute
            if ($stmt->execute()) {
                $success = "Company Profile has been updated successfully!";
            } else {
                $error = "There was an error. Our developers are on it!";
            }

            // Close the statement
            unset($stmt);
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('ADMIN | COMPANY_DETAILS'); ?>

<!-- Main Admin Navbar -->
<?php include_once "./admin/admin_includes/admin_navbar.inc.php"; ?>

<!-- Page Title & Breadcrumb -->
<div class="container-fluid">
    <div class="page_title">
        <div class="row">
            <h5>Admin Company Details</h5>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Company Details</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Company Details -->
<div class="container-fluid">
    <div id="profile">
        <div class="row">
            <!-- Fetch the company details from the database -->
            <?php
            $sql = $pdo->prepare("SELECT * FROM company_details WHERE id = 1");
            $sql->execute();
            $database_company_details = $sql->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($database_company_details as $companyDetails) : ?>
                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5><i class="bi bi-building"></i> Update Company Profile</h5>
                            <hr>
                        </div>

                        <form action="index.php?page=admin/company/company_details" method="post" class="profile_edit">
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

                            <!-- Company Name -->
                            <div class="form-group">
                                <label for="CompanyName">Company Name</label>
                                <input type="text" name="companyName" value="<?= $companyDetails['companyName']; ?>" class="form-control 
                            <?php echo (!empty($companyName_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $companyName_error; ?></span>
                            </div>

                            <!-- Submit Btn -->
                            <div class="form-group my-3">
                                <input type="submit" value="UPDATE COMPANY DETAILS" class="btn w-100">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5><i class="bi bi-building"></i> Company Profile</h5>
                            <hr>
                        </div>

                        <form class="profile_edit">
                            <div class="form-group">
                                <label for="Name Of Company">Name of Company</label>
                                <span class="profile_details">
                                    <h5><?= $companyDetails['companyName']; ?></h5>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Footer Template -->
<?= footer_template(); ?>