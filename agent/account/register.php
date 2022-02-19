<?php

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database Connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign empty values
$prefix = $firstName = $lastName = $email = $phoneNumber = $experience = $rateCard = $profileImage = $password = $confirmPassword = "";
$prefix_error = $firstName_error = $lastName_error = $email_error = $phoneNumber_error = $experience_error = $rateCard_error = $profileImage_error = $password_error = $confirmPassword_error = "";

//Process user input when the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Validate Prefix
    $agreeToPrefix = false;

    if (isset($_POST['prefix']) && !empty($_POST['prefix'])) {
        // Create an array containing all the input values that are allowed
        $allowed_answers = array("Mr.", "Mrs.", "Ms.", "Other");

        // Create a variable that contains the user input
        $prefix = $_POST['prefix'];

        // Ensure the user input is in our array of allowed values
        if (in_array($prefix, $allowed_answers)) {
            // Check if the user selected Mr
            if (strcasecmp($prefix, 'Mr.') == 0) {
                $agreeToPrefix = true;
            }
        }
    }

    //Validate FirstName
    if (empty(trim($_POST['firstName']))) {
        $firstName_error = "Field is required!";
    } else {
        $firstName = trim($_POST['firstName']);
    }

    //Validate LastName
    if (empty(trim($_POST['lastName']))) {
        $lastName_error = "Field is required!";
    } else {
        $lastName = trim($_POST['lastName']);
    }

    // Validate Email Address
    if (empty(trim($_POST['email']))) {
        $email_error = "Field is required!";
    } else {
        // Check if the email already exists
        $sql = "SELECT * FROM agent WHERE emailAddress = :emailAddress";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":emailAddress", $param_emailAddress, PDO::PARAM_STR);
            // Set parameters
            $param_emailAddress = trim($_POST['email']);
            // Attempt to execute
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 1) {
                    $email_error = "Email Address is already taken!";
                } else {
                    $email = trim($_POST['email']);
                }
            }
        }
    }

    // Validate phone number
    if (empty(trim($_POST['phoneNumber']))) {
        $phoneNumber_error = "Field is required!";
    } else {
        $phoneNumber = trim($_POST['phoneNumber']);
    }

    // Validate Years Of Experience
    $agreeToExperience = false;

    if (isset($_POST['experience']) && !empty($_POST['experience'])) {
        // Create an array containing all the acceptable answers
        $accepetedAnswers = array("Less than 1 year", "5 - 10 years", "11 - 20 years", "More than 21 years");

        // Store user input in a variable
        $experience = $_POST['experience'];

        // Check if user input is in our array of acceptables
        if (in_array($experience, $accepetedAnswers)) {
            // Check if the user chose "5 - 10 years"
            if (strcasecmp($experience, "5 - 10 years")) {
                $agreeToExperience = true;
            }
        }
    }

    // Validate rateCard
    if (empty(trim($_POST['rateCard']))) {
        $rateCard_error = "Field is required!";
    } else {
        $rateCard = trim($_POST['rateCard']);
    }

    // Validate Password
    if (empty(trim($_POST['password']))) {
        $password_error = "Field is required!";
    } else if (strlen(trim($_POST['password']) < 8)) {
        $password_error = "Passwords must have more than 8 characters!";
    } else {
        $password = trim($_POST['password']);
    }

    // Validate Confirm Password
    if (empty(trim($_POST['confirmPassword']))) {
        $confirmPassword_error = "Field is required!";
    } else {
        $confirmPassword = trim($_POST['confirmPassword']);

        if (empty($password_error) && ($password !== $confirmPassword)) {
            $confirmPassword_error = "Passwords do not match!";
        }
    }

    // Check for errors before dealing with the database
    if (empty($firstName_error) && empty($lastName_error) && empty($prefix_error) && empty($email_error) && empty($experience_error) && empty($phoneNumber_error) && empty($rateCard_error) && empty($password_error) && empty($confirmPassword_error)) {
        // Process the profile image input
        if (!empty($_FILES['profileImage']['name'])) {
            move_uploaded_file($_FILES['profileImage']['tmp_name'], "agent/profileImages/" . $_FILES['profileImage']['name']);
            $profileImage = "agent/profileImages/" . $_FILES['profileImage']['name'];

            // Prepare an INSERT statement
            $sql = "INSERT INTO agent(firstName, lastName, prefix, emailAddress, profileImage, yearsOfExperience, phoneNumber, rateCard, password) 
            VALUES(:firstName, :lastName, :prefix, :emailAddress, :profileImage, :yearsOfExperience, :phoneNumber, :rateCard, :password)";

            if ($stmt = $pdo->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":firstName", $param_firstName, PDO::PARAM_STR);
                $stmt->bindParam(":lastName", $param_lastName, PDO::PARAM_STR);
                $stmt->bindParam(":prefix", $param_prefix, PDO::PARAM_STR);
                $stmt->bindParam(":emailAddress", $param_emailAddress, PDO::PARAM_STR);
                $stmt->bindParam(":profileImage", $param_profileImage, PDO::PARAM_STR);
                $stmt->bindParam(":yearsOfExperience", $param_yearsOfExperience, PDO::PARAM_STR);
                $stmt->bindParam(":phoneNumber", $param_phoneNumber, PDO::PARAM_INT);
                $stmt->bindParam(":rateCard", $param_rateCard, PDO::PARAM_STR);
                $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

                // Set parameters
                $param_firstName = $firstName;
                $param_lastName = $lastName;
                $param_prefix = $prefix;
                $param_emailAddress = $email;
                $param_profileImage = $profileImage;
                $param_yearsOfExperience = $experience;
                $param_phoneNumber = $phoneNumber;
                $param_rateCard = $rateCard;
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                // Attempt to execute
                if ($stmt->execute()) {
                    // Refer the agent to the login page
                    header("location: index.php?page=agent/account/login");
                    exit;
                } else {
                    echo "Our developers are on it, seems like there is an error. Please try again!";
                }
            }
        } else {
            $profileImage_error = "Field is required!";
        }
    }
}

?>

<!-- Header Template -->
<?= header_template('AGENT | REGISTER'); ?>

<!-- TopBar Script -->
<?php include_once "includes/loginTopBar.inc.php" ?>


<!-- Back Link -->
<div class="back_link">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="index.php?page=agent/account/login"><i class="bi bi-arrow-left-circle"></i> Login</a>
            </div>
        </div>
    </div>
</div>

<!-- Agent Register Form Script -->
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="title">
                    <h5>Real Estate Agent Register</h5>
                    <hr>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <form action="index.php?page=agent/account/register" method="post" enctype="multipart/form-data" class="login-form">
                    <div class="row">
                        <!-- Prefix -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="Prefix">Prefix</label>
                                <select name="prefix" class="form-control">
                                    <option value="Select Prefix" disabled>Select prefix</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Other">Other</option>
                                </select>
                                <span class="errors text-danger"><?php echo $prefix_error; ?></span>
                            </div>
                        </div>

                        <!-- FirstName -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" name="firstName" class="form-control 
                                <?php echo (!empty($firstName_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $firstName_error ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- LastName -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" name="lastName" class="form-control 
                                <?php echo (!empty($lastName_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $lastName_error; ?></span>
                            </div>
                        </div>

                        <!-- EmailAddress -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="EmailAddress">Email Address</label>
                                <input type="email" name="email" class="form-control 
                                <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $email_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- PhoneNumber -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number</label>
                                <input type="tel" name="phoneNumber" class="form-control 
                                <?php echo (!empty($phoneNumber_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $phoneNumber_error; ?></span>
                            </div>
                        </div>

                        <!-- Years of experience -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="Years of Experience">Years of Experience</label>
                                <select name="experience" class="form-control">
                                    <option value="Select your years of experience" disabled>Select your years of experience</option>
                                    <option value="Less than 1 year">Less than 1 year</option>
                                    <option value="5 - 10 years">5 - 10 years</option>
                                    <option value="11 - 30 years">11 - 20 years</option>
                                    <option value="More than 31 years">More than 21 years</option>
                                </select>
                                <span class="errors text-danger"><?php echo $experience_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Rate Card -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="Rate Card">Rate Card <small>(in ksh)</small></label>
                                <input type="text" name="rateCard" class="form-control 
                                <?php echo (!empty($rateCard_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $rateCard_error; ?></span>
                            </div>
                        </div>

                        <!-- ProfileImage -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="profileImage">Profile Image</label>
                                <input type="file" name="profileImage" accept="'.jpg', '.png', '.jpeg'" class="form-control 
                                <?php echo (!empty($profileImage_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $profileImage_error; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="Password">Password</label>
                                <input type="password" name="password" class="form-control 
                                <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>">
                                <span class="errors text-danger"><?php echo $password_error; ?></span>
                            </div>
                        </div>

                        <!-- Password Confirm -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="Confirm Password">Confirm Password</label>
                                <input type="password" name="confirmPassword" class="form-control 
                                <?php echo (!empty($confirmPassword_error)) ? 'is-invalid' : ''; ?> ?>">
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