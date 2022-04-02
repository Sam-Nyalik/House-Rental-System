<?php
// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once "functions/functions.php";

// Database Connection
$pdo = databaseConnect();

// Define variables and assign them empty values
$emailAddress = $password = "";
$emailAddress_error = $password_error = $general_error = $error = "";

// Process form data when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate EmailAddress
    if (empty(trim($_POST['emailAddress']))) {
        $emailAddress_error = "Field is required!";
    } else {
        $emailAddress = trim($_POST['emailAddress']);
    }

    // Validate Password
    if (empty($_POST['password'])) {
        $password_error = "Field is required!";
    } else {
        $password = trim($_POST['password']);
    }

    // Check for errors before dealing with the database
    if (empty($emailAddress_error) && empty($password_error)) {
        // Prepare a SELECT statement
        $sql = ("SELECT id, emailAddress, password FROM users WHERE emailAddress = :emailAddress");
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":emailAddress", $param_emailAddress, PDO::PARAM_STR);

            // Set parameters
            $param_emailAddress = $emailAddress;

            // Attempt to execute
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row['id'];
                        $emailAddress = $row['emailAddress'];
                        $hashed_password = $row['password'];

                        // Verify if the password in the database is similar to the one in the user input
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct
                            // Start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['admin_loggedIn'] = true;
                            $_SESSION['id'] = $id;

                            // Redirect the user to the dashboard
                            header("location: index.php?page=");
                            exit;
                        } else {
                            $password_error = "Wrong user input!";
                        }
                    }
                } else {
                    $error = "User with this credentials does not exist!";
                }
            } else {
                $error = "Something went wrong. Our developers are on it!";
            }
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('USER | LOGIN'); ?>

<!-- Login TopBar Script -->
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
                <a href="index.php?page=home"><i class="bi bi-arrow-left-circle"></i> Home</a>
            </div>
        </div>
    </div>
</div>

<!-- User Login Form Script -->
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="title">
                    <h5>User Sign In</h5>
                    <hr>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <form action="index.php?page=user/account/login" method="post" class="login-form">
                    <!-- Errors -->
                    <div class="form-group my-2">
                        <span class="errors text-danger">
                            <?php
                            if ($error) {
                                echo $error;
                            }
                            ?>
                        </span>
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="EmailAddress">Email Address</label>
                        <input type="email" name="emailAddress" class="form-control 
                        <?php echo (!empty($emailAddress_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $emailAddress ?>">
                        <span class="errors text-danger"><?php echo $emailAddress_error; ?></span>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input type="password" name="password" id="userPass" class="form-control 
                        <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>">
                        <span class="errors text-danger"><?php echo $password_error; ?></span>
                    </div>

                    <!-- Forgot Password -->
                    <div class="form-group" style="float: right;">
                        <a href="#">
                            <h6 style="font-size: 15px;"> Forgot Password?</h6>
                        </a>
                    </div>

                    <!-- Login Btn -->
                    <div class="form-group my-3">
                        <input type="submit" value="Login" class="btn w-100">
                    </div>
                </form>
            </div>

            <div class="col-md-6" style="padding: 0 20px;">
                <div class="register_redirect">
                    <h5>Don't have an account yet?</h5>
                    <hr>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur, laboriosam! Qui magni dolor ab cum, fugiat vitae eaque omnis necessitatibus.</p>
                    <a href="index.php?page=user/account/register" class="btn w-100">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>