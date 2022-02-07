<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Connect to the database
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$prefix = $firstName = $lastName = $email = $profileImage = $password = $confirmPassword = "";
$prefix_error = $firstName_error = $lastName_error = $email_error = $profileImage_error = $password_error = $confirmPassword_error = "";

// Process form data when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate form input
    // Prefix Validation
    $agreedToPrefix = false;

    if (isset($_POST['prefix']) && !empty($_POST['prefix'])) {
        // Create an array containing the input values that are allowed
        $allowedAnswers = array('Mr.', 'Ms.', 'Mrs.', 'Other');

        // Create a variable that contains the value the user sends
        $prefix = $_POST['prefix'];

        // Ensure that the value sent by the user is in our array of values
        if (in_array($prefix, $allowedAnswers)) {
            // Check if the user selected Mr.
            if (strcasecmp($prefix, 'Mr.') == 0) {
                $agreedToPrefix = true;
            }
        }
    }

    // First Name Validation
    if (empty(trim($_POST['firstName']))) {
        $firstName_error = "Field is required!";
    } else {
        $firstName = trim($_POST['firstName']);
    }

    // Last Name Validation
    if (empty(trim($_POST['lastName']))) {
        $lastName_error = "Field is required!";
    } else {
        $lastName = trim($_POST['lastName']);
    }

    // Email Validation
    if (empty(trim($_POST['email']))) {
        $email_error = "Field is required!";
    } else {
        // Check if the email already exists
        $sql = "SELECT * FROM admin WHERE emailAddress = :email";

        // Prepare our 'sql' query and store it in a variable called 'stmt'
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
            // Set parameters
            $param_email = trim($_POST['email']);
            // Attempt to execute
            if ($stmt->execute()) {
                // Check if there is a similar email address like the one put
                if ($stmt->rowCount() == 1) {
                    $email_error = "Email Address is already taken!";
                } else {
                    $email = trim($_POST['email']);
                }
            }

            // Close the statement
            unset($stmt);
        }
    }

    // Password Validation
    if (empty(trim($_POST['password']))) {
        $password_error = "Field is required!";
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $password_error = "Passwords must have at least 8 characters!";
    } else {
        $password = trim($_POST['password']);
    }

    // Confirm Password Validation
    if (empty(trim($_POST['confirmPassword']))) {
        $confirmPassword_error = "Field is required!";
    } else {
        $confirmPassword = trim($_POST['confirmPassword']);

        // Check if the password and confirm password match
        if (empty($password_error) && ($password !== $confirmPassword)) {
            $confirmPassword_error = "Passwords do not match!";
        }
    }

    // Check for errors before dealing with the database
    if (empty($prefix_error) && empty($firstName_error) && empty($lastName_error) && empty($email_error) && empty($password_error) && empty($confirmPassword_error)) {
        // Upload the profile image
        if (!empty($_FILES['profileImage']['name'])) {
            move_uploaded_file($_FILES['profileImage']['tmp_name'], "admin/profileImages/" . $_FILES['profileImage']['name']);
            $profileImage = "admin/profileImages/" . $_FILES['profileImage']['name'];

            // Prepare an INSERT statement(firstName, lastName, emailAddress, profileImage, prefix, password)
            $sql = "INSERT INTO admin(firstName, lastName, emailAddress, profileImage, prefix, password) VALUES (:firstName, :lastName, :email, :profileImage, :prefix, :password)";

            // Prepare our 'sql' query
            if ($stmt = $pdo->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":firstName", $param_firstName, PDO::PARAM_STR);
                $stmt->bindParam(":lastName", $param_lastName, PDO::PARAM_STR);
                $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                $stmt->bindParam(":profileImage", $param_profileImage, PDO::PARAM_STR);
                $stmt->bindParam(":prefix", $param_prefix, PDO::PARAM_STR);
                $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

                // Set Parameters
                $param_firstName = $firstName;
                $param_lastName = $lastName;
                $param_email = $email;
                $param_prefix = $prefix;
                $param_profileImage = $profileImage;
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                // Attempt to execute
                if ($stmt->execute()) {
                    header("location: index.php?page=admin/account/login");
                    exit;
                } else {
                    echo "Seems like there is an error. Our developers are on it!";
                }

                // Close the statement
                unset($stmt);
            }
        } else {
            $profileImage_error = "Field is required!";
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('ADMIN | REGISTER'); ?>

<!-- Registration TopBar Script -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="login-topbar">
            </div>
        </div>
    </div>
</div>

<!-- Registration Form Script -->
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="title">
                    <h5>Admin Register</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <form action="index.php?page=admin/register" method="post" enctype="multipart/form-data" class="login-form">
                    <div class="row">

                        <!-- Prefix -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="prefix">Prefix</label>
                                <select name="prefix" class="form-control">
                                    <option value="Select Prefix" disabled>Select Prefix</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- FirstName -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="FirstName">First Name</label>
                                <input type="text" name="firstName" class="form-control 
                                <?php echo (!empty($firstName_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>">
                                <span class="errors text-danger"><?php echo $firstName_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- LastName -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" name="lastName" class="form-control 
                                <?php echo (!empty($lastName_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>">
                                <span class="errors text-danger"><?php echo $lastName_error; ?></span>
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="EmailAddress">Email Address</label>
                                <input type="email" name="email" class="form-control 
                                <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                <span class="errors text-danger"><?php echo $email_error; ?></span>
                            </div>
                        </div>

                    </div>

                    <!-- Profile Image -->
                    <div class="form-group">
                        <label for="profileImage">Profile Image</label>
                        <input type="file" name="profileImage" class="form-control 
                        <?php echo (!empty($profileImage_error)) ? 'is-invalid' : ''; ?>">
                        <span class="errors text-danger"><?php echo $profileImage_error; ?></span>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Password">Password</label>
                                <input type="password" name="password" id="password" class="form-control 
                                <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>">
                                <i class="bi bi-eye-slash" id="passwordEyeToggle"></i>
                                <span class="errors text-danger"><?php echo $password_error; ?></span>
                            </div>
                        </div>

                        <!-- Password Confirm -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ConfirmPassword">Confirm Password</label>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control 
                                <?php echo (!empty($confirmPassword_error)) ? 'is-invalid' : ''; ?>">
                                <i class="bi bi-eye-slash" id="confirmPasswordEyeToggle"></i>
                                <span class="errors text-danger"><?php echo $confirmPassword_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Btn -->
                    <div class="form-group my-3">
                        <input type="submit" value="Register" class="btn w-100">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer Template -->
<?= footer_template(); ?>