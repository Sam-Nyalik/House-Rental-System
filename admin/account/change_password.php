<?php

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if the admin is logged in, if not redirect to the login page
include_once "./admin/admin_includes/check_login.inc.php";

// Database connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$currentPassword = $newPassword = $confirmNewPassword = $success = "";
$currentPassword_error = $newPassword_error = $confirmNewPassword_error = $error = "";

// Process user input when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate Current Password
    if (empty(trim($_POST['currentPassword']))) {
        $currentPassword_error = "Field is required!";
    } else {
        //Prepare a SELECT statment
        $id = false;
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
        }
        $sql = $pdo->prepare("SELECT password FROM admin WHERE id = '$id'");
        $sql->execute();
        $database_admin = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach ($database_admin as $admin_password): {
              if (password_verify($_POST['currentPassword'], $admin_password['password'])) {
            $currentPassword = trim($_POST['currentPassword']);
        } else {
            $currentPassword_error = "Wrong Password!";
        }
        }
    endforeach;
    }

    // Validate New Password
    if (empty(trim($_POST['newPassword']))) {
        $newPassword_error = "Field is required!";
    } elseif (strlen(trim($_POST['newPassword'])) < 8) {
        $newPassword_error = "Passwords must have more than 8 characters!";
    } else {
        $newPassword = trim($_POST['newPassword']);
    }

    // Validate Confirm New Password
    if (empty(trim($_POST['confirmNewPassword']))) {
        $confirmNewPassword_error = "Field is required!";
    } else {
        $confirmNewPassword = trim($_POST['confirmNewPassword']);

        if (empty($newPassword_error) && ($newPassword !== $confirmNewPassword)) {
            $confirmNewPassword_error = "New Passwords do not match!";
        }
    }

    // Check for errors before dealing with the database
    if (empty($currentPassword_error) && empty($newPassword_error) && empty($confirmNewPassword_error)) {
        // Prepare an UPDATE stetement
        $sql = "UPDATE admin SET password = :newPassword WHERE id = :id";
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":newPassword", $param_newPassword, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            // Set parameters
            $param_newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $param_id = $_SESSION['id'];
            // Attempt to execute
            if ($stmt->execute()) {
                header("location: index.php?page=admin/account/login");
                exit;
            }
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('ADMIN | CHANGE-PASSWORD'); ?>

<!-- Admin Navbar -->
<?php include_once "./admin/admin_includes/admin_navbar.inc.php" ?>

<!-- Page Title & BreadCrumb -->
<div class="container-fluid">
    <div class="page_title">
        <div class="row">
            <h5>Admin Password Change</h5>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Change Password -->
<div class="container-fluid">
    <div id="profile">
        <div class="row">
            <div class="col-md-6">
                <div class="profile_side1">
                    <div class="title">
                        <h5><i class="bi bi-lock"></i> Change Password</h5>
                        <hr>
                    </div>
                    <form action="index.php?page=admin/account/change_password" method="post" class="profile_edit">
                        <!-- Current Password -->
                        <div class="form-group">
                            <label for="CurrentPassword">Current Password</label>
                            <input type="password" name="currentPassword" id="password" class="form-control 
                            <?php echo (!empty($currentPassword_error)) ? 'is-invalid' : ''; ?>">
                            <i class="bi bi-eye-slash" id="passwordEyeToggle"></i>
                            <span class="errors text-danger"><?php echo $currentPassword_error; ?></span>

                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <label for="NewPassword">New Password</label>
                            <input type="password" name="newPassword" id="newPassword" class="form-control 
                            <?php echo (!empty($newPassword_error)) ? 'is-invalid' : ''; ?>">
                            <i class="bi bi-eye-slash" id="newPasswordEyeToggle"></i>
                            <span class="errors text-danger"><?php echo $newPassword_error; ?></span>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="form-group">
                            <label for="ConfirmNewPassword">Confirm New Password</label>
                            <input type="password" name="confirmNewPassword" id="confirmPassword" class="form-control 
                            <?php echo (!empty($confirmNewPassword_error)) ? 'is-invalid' : ''; ?>">
                            <i class="bi bi-eye-slash" id="confirmPasswordEyeToggle"></i>
                            <span class="errors text-danger"><?php echo $confirmNewPassword_error; ?></span>
                        </div>

                        <!-- Submit btn -->
                        <div class="form-group my-3">
                            <input type="submit" value="Update Password" class="btn w-100">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Template -->
<?= footer_template(); ?>