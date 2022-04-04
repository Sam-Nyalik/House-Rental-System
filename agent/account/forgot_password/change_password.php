<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database Connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$newpassword = $confirmNewPassword = "";
$newPassword_error = $confirmNewPassword_error = "";

$id = false;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

// Process data when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate New password
    if (empty(trim($_POST['newPassword']))) {
        $newPassword_error = "Field is required!";
    } else if (strlen(trim($_POST['newPassword'])) < 8) {
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
            $confirmNewPassword_error = "Passwords do not match!";
        }
    }

    // Check for errors before dealing with the database
    if (empty($newPassword_error) && empty($confirmNewPassword_error)) {
        // Prepare an UPDATE statement based on the id in the url
        $sql = ("UPDATE agent SET password = :newPassword WHERE id = :id");
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":newPassword", $param_newPassword, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            // Set parameters
            $param_newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $param_id = $id;
            // Attempt to execute
            if ($stmt->execute()) {
                // Redirect the agent to the login page
                header("location: index.php?page=agent/account/login");
                exit;
            } else {
                $newPassword_error = "There was an error. Our developers are on it!";
            }
        }
    }
}

?>

<!-- Header template -->
<?= header_template('AGENT | PASSWORD RESET'); ?>

<!-- TopBar Script -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="login-topbar"></div>
        </div>
    </div>
</div>

<!-- Back Link -->
<div class="back_link">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="index.php?page=agent/account/forgot_password/email_addressConfirm"><i class="bi bi-arrow-left-circle"></i> Back</a>
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Script -->
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="title">
                    <h5>Agent Password Reset</h5>
                    <hr>
                </div>

                <form action="index.php?page=agent/account/forgot_password/change_password&id=<?= $id ?>" method="post" class="login-form">
                    <!-- New Password -->
                    <div class="form-group my-3">
                        <label for="New Password">New Password</label>
                        <input type="password" name="newPassword" class="form-control 
                        <?php echo (!empty($newPassword_error)) ? 'is-invalid' : ''; ?>">
                        <span class="errors text-danger"><?php echo $newPassword_error ?></span>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="form-group my-3">
                        <label for="Confirm New Password">Confirm New Password</label>
                        <input type="password" name="confirmNewPassword" class="form-control 
                        <?php echo (!empty($confirmNewPassword_error)) ? 'is-invalid' : ''; ?>">
                        <span class="errors text-danger"><?php echo $confirmNewPassword_error; ?></span>
                    </div>

                    <!-- Submit Btn -->
                    <div class="form-group my-2">
                        <input type="submit" value="Reset Password" class="btn w-100">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>