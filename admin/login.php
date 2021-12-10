<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Start session
session_start();

// Check whether the admin is logged in(redirect to the dashboard), if not(redirect to the login page)

// Connect to the database
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$email = $password = "";
$email_error = $password_error = $general_error = "";

// Process user input when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate user input
    // Email Address validation
    if (empty(trim($_POST['emailAddress']))) {
        $email_error = "Field is required!";
    } else {
        $email = trim($_POST['emailAddress']);
    }

    // Password Validation
    if (empty(trim($_POST['password']))) {
        $password_error = "Field is required!";
    } else {
        $password = trim($_POST['password']);
    }

    // Check for errors before dealing with the database
    if (empty($email_error) && empty($password_error)) {
        // Prepare a SELECT statement
        $sql = "SELECT id, emailAddress, password FROM admin WHERE emailAddress = :emailAddress";

        // Prepare our sql statement
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":emailAddress", $param_emailAddress, PDO::PARAM_STR);
            // Set Parameters
            $param_emailAddress = $email;
            // Attempt to execute
            if ($stmt->execute()) {
                // Check if the user with the specified details exists
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row['id'];
                        $email = $row['emailAddress'];
                        $hashed_password = $row['password'];

                        // Verify the password hash in the database with the user input password
                        if (password_verify($password, $hashed_password)) {

                            // Password is correct, start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['admin_loggedIn'] = true;
                            $_SESSION['id'] = $id;

                            // Redirect the admin to the dashboard
                            header("location: index.php?page=admin/dashboard");
                            exit;
                        } else {
                            $general_error = "Invalid credentials!";
                        }
                    }
                } else {
                    $general_error = "User with these credentials doesn't exist!";
                }
            } else {
                echo "Seems like there is an error. Our developers are on it!";
            }

            // Close the statement
            unset($stmt);
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('ADMIN | LOGIN'); ?>

<!-- Login TopBar Script-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="login-topbar">
            </div>
        </div>
    </div>
</div>

<!-- Login Form Script -->
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="title">
                    <h5>Admin Sign In</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <form action="index.php?page=admin/login" method="post" class="login-form">
                    <!-- General Error -->
                    <div class="form-group">
                        <span class="errors text-danger">
                            <?php if ($general_error) {
                                echo $general_error;
                            } ?>
                        </span>
                    </div>
                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="Email Address">Email Address</label>
                        <input type="email" name="emailAddress" class="form-control 
                        <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                        <span class="errors text-danger"><?php echo $email_error; ?></span>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input type="password" name="password" id="password" class="form-control 
                        <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>">
                        <i class="bi bi-eye-slash" id="passwordEyeToggle"></i>
                        <span class="errors text-danger"><?php echo $password_error; ?></span>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group my-3">
                        <input type="submit" value="Sign In" class="btn w-100">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer Template -->
<?= footer_template(); ?>