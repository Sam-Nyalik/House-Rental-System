<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$emailAddress = "";
$emailAddress_error = "";

// Process form data when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate email address
    if (empty($_POST['emailAddress'])) {
        $emailAddress_error = "Field is required!";
    } else {
        $emailAddress = trim($_POST['emailAddress']);
    }

    // Check for errors before dealing with the database
    if (empty($emailAddress_error)) {
        // Prepare a SELECT statement
        $sql = ("SELECT id from agent WHERE emailAddress = :emailAddress");
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":emailAddress", $param_emailAddress, PDO::PARAM_STR);
            // Set parameters
            $param_emailAddress = $emailAddress;
            // Attempt to execute
            if ($stmt->execute()) {
                // Check if the input email address exists in the database
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row['id'];

                        // redirect the agent to the change_password page
                        header("location: index.php?page=agent/account/forgot_password/change_password&id=$id");
                        exit;
                    }
                } else {
                    $emailAddress_error = "This email address does not exist!";
                }
            } else {
                $emailAddress_error = "There was an error. Our developers are working on it!";
            }
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('AGENT | EMAIL ADDRESS CONFIRMATION') ?>

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
                <a href="index.php?page=agent/account/login"><i class="bi bi-arrow-left-circle"></i> Back</a>
            </div>
        </div>
    </div>
</div>

<!-- Email Address Confirmation script -->
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="title">
                    <h5>Agent Email Confirmation</h5>
                    <hr>
                </div>

                <form action="index.php?page=agent/account/forgot_password/email_addressConfirm" method="post" class="login-form">
                    <!-- Email Address Form -->
                    <div class="form-group my-3">
                        <label for="Email Address">Email Address</label>
                        <input type="email" name="emailAddress" class="form-control 
                            <?php echo (!empty($emailAddress_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $emailAddress; ?>">
                        <span class="errors text-danger"><?php echo $emailAddress_error ?></span>
                    </div>

                    <!-- Submit btn -->
                    <div class="form-group my-2">
                        <input type="submit" value="Check Email" class="btn w-100">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>