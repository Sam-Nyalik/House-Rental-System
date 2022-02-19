<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once "functions/functions.php";
// Database Connection
$pdo = databaseConnect();

// Define variables and assign them empty values
$prefix = $firstName = $lastName = $emailAddress = $password = $confirmPassword = "";
$prefix_error = $firstName_error = $lastName_error = $emailAddress_error = $password_error = $confirmPassword_error = "";

// Process the user input when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate Prefix
    $prefixAnswerable = false;

    if (isset($_POST['prefix']) && !empty($_POST['prefix'])) {
        // Allowed prefix's
        $allowedPrefix = array('Mr.', 'Mrs.', 'Ms.', 'Other');;
        // User Input
        $prefix = $_POST['prefix'];
        // Check if  whatever the user input matches any of our allowed prefix's
        if (in_array($prefix, $allowedPrefix)) {
            // Check if the user selected Mr.
            if (strcasecmp($prefix, 'Mr.') == 0) {
                $prefixAnswerable = true;
            }
        }
    }

    // Validate firstName
    if (empty(trim($_POST['firstName']))) {
        $firstName_error = "Field is required!";
    } else {
        $firstName = trim($_POST['firstName']);
    }

    // Validate lastName
    if (empty(trim($_POST['lastName']))) {
        $lastName_error = "Field is required!";
    } else {
        $lastName = trim($_POST['lastName']);
    }

    // Validate emailAddress
    if (empty(trim($_POST['emailAddress']))) {
        $emailAddress_error = "Field is required!";
    } else {
        // Check if the email address is already taken
        $sql = "SELECT * FROM users WHERE emailAddress = :emailAddress";
        // Prepare the query and store in $stmt
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":emailAddress", $param_emailAddress, PDO::PARAM_STR);
            // Set parameters
            $param_emailAddress = trim($_POST['emailAddress']);
            // Attempt to execute
            if ($stmt->execute()) {
                // check if the emailAddress appears in more than 1 row
                if ($stmt->rowCount() > 1) {
                    $emailAddress_error = "Email Address is already taken!";
                } else {
                    $emailAddress = trim($_POST['emailAddress']);
                }
            }
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_error = "Field is required!";
    } else if (strlen(trim($_POST['password'])) < 8) {
        $password_error = "Passwords must have more than 8 characters!";
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirmPassword
    if (empty(trim($_POST['confirmPassword']))) {
        $confirmPassword_error = "Field is required!";
    } else {
        $confirmPassword = trim($_POST['confirmPassword']);
        // Check if the password and confirm password inputs match
        if (empty($password_error) && ($password !== $confirmPassword)) {
            $confirmPassword_error = "Passwords do not match!";
        }
    }

    // Ensure there are no errors before dealing with the database
    if (empty($prefix_error) && empty($firstName_error) && empty($lastName_error) && empty($emailAddress_error) && empty($password_error) && empty($confirmPassword_error)) {
        // Prepare an INSERT statement into the users table
        $userInsert = "INSERT INTO users(prefix, firstName, lastName, emailAddress, password) VALUES(
            :prefix, :firstName, :lastName, :emailAddress, :password
        )";

        // Prepare the userInsert query 
        if ($stmt = $pdo->prepare($userInsert)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":prefix", $param_prefix, PDO::PARAM_STR);
            $stmt->bindParam(":firstName", $param_firstName, PDO::PARAM_STR);
            $stmt->bindParam(":lastName", $param_lastName, PDO::PARAM_STR);
            $stmt->bindParam(":emailAddress", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            // Set paramaters
            $param_prefix = $prefix;
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_email = $emailAddress;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            // Attempt to execute
            if ($stmt->execute()) {
                // Redirect the user to the login page
                header("location: index.php?page=user/account/login");
            } else {
                echo "<script>alert('There was an error. Our developers are on it!');</script>";
            }
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('USER | REGISTRATION'); ?>

<!-- Topbar script -->
<?php include_once "includes/loginTopBar.inc.php" ?>

<!-- Back Link -->
<div class="back_link">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="index.php?page=user/account/login"><i class="bi bi-arrow-left-circle"></i> Login</a>
            </div>
        </div>
    </div>
</div>

<!-- User Register Form Script -->
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="title">
                    <h5>User Registration</h5>
                    <hr>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <form action="index.php?page=user/account/register" method="post" class="login-form">
                    <div class="row">
                        <!-- Prefix -->
                        <div class="col-4">
                            <div class="form-group">
                                <label for="Prefix">Prefix</label>
                                <select name="prefix" class="form-control">
                                    <option value="Select Prefix" disabled>Select Prefix</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Other">Other</option>
                                </select>
                                <span class="errors text-danger"><?php echo $prefix_error; ?></span>
                            </div>
                        </div>

                        <!-- First Name -->
                        <div class="col-8">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" name="firstName" class="form-control 
                                <?php echo (!empty($firstName_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $firstName_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Last Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="LastName">Last Name</label>
                                <input type="text" name="lastName" class="form-control 
                                <?php echo (!empty($lastName_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $lastName_error; ?></span>
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="EmailAddress">Email Address</label>
                                <input type="email" name="emailAddress" class="form-control 
                                <?php echo (!empty($emailAddress_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $emailAddress_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Password">Password</label>
                                <input type="password" name="password" class="form-control 
                                <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $password_error; ?></span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Password Confirm">Confirm Password</label>
                                <input type="password" name="confirmPassword" class="form-control 
                                <?php echo (!empty($confirmPassword_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $confirmPassword_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Register Btn -->
                    <div class="form-group my-3">
                        <input type="submit" value="Register" class="btn w-100">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>