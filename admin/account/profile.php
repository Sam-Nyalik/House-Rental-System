<?php
$id = false;
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if the admin is loggedin, if not(Redirect to the login page)
include_once "./admin/admin_includes/check_login.inc.php";

// Conncet to the database
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$prefix = $firstName = $lastName = $success = "";
$prefix_error = $firstName_error = $lastName_error = $error = "";

// Process user input when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate prefix
    $agreedPrefix = false;

    if (isset($_POST['prefix']) && !empty($_POST['prefix'])) {
        // Create an array containing the input values that are allowed
        $allowedAnswers = array('Mr.', 'Mrs.', 'Ms.', 'Other');
        // Create a variable containing what the user input
        $prefix = $_POST['prefix'];
        // Check if what the user input is in our array of allowed answers
        if (in_array($prefix, $allowedAnswers)) {
            // Check if the user selected Mr.
            if (strcasecmp($prefix, 'Mr.') == 0) {
                $agreedPrefix = true;
            }
        }
    } else {
        $prefix_error = "Field is required!";
    }

    // Validate FirstName
    if (empty($_POST['firstName'])) {
        $firstName_error = "Field is required!";
    } else {
        $firstName = trim($_POST['firstName']);
    }

    // Validate LastName
    if (empty(trim($_POST['lastName']))) {
        $lastName_error = "Field is required!";
    } else {
        $lastName = trim($_POST['lastName']);
    }

    // Check for errors before dealing with the database
    if (empty($prefix_error) && empty($firstName_error) && empty($lastName_error)) {
        // Prepare an UPDATE statement
        $sql = "UPDATE admin SET firstName = :firstName, lastName = :lastName, prefix = :prefix WHERE id = :id";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":firstName", $param_firstName, PDO::PARAM_STR);
            $stmt->bindParam(":lastName", $param_lastName, PDO::PARAM_STR);
            $stmt->bindParam(":prefix", $param_prefix, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

            // Set parameters
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_prefix = $prefix;
            $param_id = $_SESSION['id'];

            // Attempt to execute
            if ($stmt->execute()) {
                $success = "Profile has been updated successfully!";
            } else {
                $error = "There was an error, please try again!";
            }
        }
    }
}

?>

<!-- Header Template -->
<?= header_template("ADMIN | PROFILE"); ?>

<!-- Main Navbar -->
<?php include_once "./admin/admin_includes/admin_navbar.inc.php"; ?>


<!-- Page Title & BreadCrumb -->
<div class="container-fluid">
    <div class="page_title">
        <div class="row">
            <h5>Admin Profile</h5>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin profile</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Admin profile -->
<div class="container-fluid">
    <div id="profile">
        <div class="row">
            <div class="col-md-6">
                <div class="profile_side1">
                    <div class="title">
                        <h5><i class="bi bi-person-circle"></i> Edit Profile</h5>
                        <hr>
                    </div>
                    <!-- Prepare a SELECT statement to fetch admin details from the database -->
                    <?php
                    $sql = $pdo->prepare("SELECT * FROM admin WHERE id = '$id'");
                    $sql->execute();
                    $database_admin_details = $sql->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php foreach ($database_admin_details as $admin_details) : ?>
                        <form action="index.php?page=admin/account/profile" method="post" class="profile_edit">
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
                            <!-- Prefix -->
                            <div class="form-group">
                                <label for="Prefix">Prefix</label>
                                <select name="prefix" class="form-control">
                                    <option value="Select Prefix" disabled>Select prefix</option>
                                    <option value="<?= $admin_details['prefix']; ?>" disabled><?= $admin_details['prefix']; ?></option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Other">Other</option>
                                </select>
                                <span class="errors text-danger"><?php echo $prefix_error; ?></span>
                            </div>

                            <div class="row">
                                <!-- FirstName -->
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="FirstName">First Name</label>
                                        <input type="text" name="firstName" value="<?= $admin_details['firstName']; ?>" class="form-control 
                                <?php echo (!empty($firstName_error)) ? 'is-invalid' : ''; ?>">
                                        <span class="errors text-danger"><?php echo $firstName_error; ?></span>
                                    </div>
                                </div>
                                <!-- LastName -->
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="LastName">Last Name</label>
                                        <input type="text" name="lastName" value="<?= $admin_details['lastName']; ?>" class="form-control 
                                    <?php echo (!empty($lastName_error)) ? 'is-invalid' : ''; ?>">
                                        <span class="errors text-danger"><?php echo $lastName_error; ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Btn -->
                            <div class="form-group my-3">
                                <input type="submit" class="btn btn-warning text-light w-100" value="Update Profile">
                                <div class="change_password">
                                    <a href="index.php?page=admin/account/change_password">Change Password</a>
                                </div>
                            </div>
                        </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="profile_side1">
                    <div class="title">
                        <h5><i class="bi bi-person"></i> Profile</h5>
                        <hr>
                    </div>
                    <img src="<?= $admin_details['profileImage']; ?>" alt="<?= $admin_details['firstName']; ?>" class="img-fluid profileImage">

                    <form class="profile_edit">
                        <div class="form-group">
                            <label for="Prefix">Prefix</label>
                            <span class="profile_details">
                                <h5><?= $admin_details['prefix']; ?></h5>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="Name">Name</label>
                            <span class="profile_details">
                                <h5><?= $admin_details['firstName']; ?> <?= $admin_details['lastName']; ?></h5>
                            </span>
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="Email">E-mail</label>
                            <span class="profile_details">
                                <h5><?= $admin_details['emailAddress']; ?></h5>
                            </span>
                        </div>
                    </form>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- Footer Template -->
<?= footer_template(); ?>