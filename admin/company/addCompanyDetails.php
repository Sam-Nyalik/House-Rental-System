<?php 
//Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if the admin is logged in or not
include_once "./admin/admin_includes/check_login.inc.php";

include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$companyName = $companyEmail = "";
$companyName_error = $companyEmail_error = "";

// Process user input when the form has been submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Validate Company Name
    if(empty(trim($_POST['companyName']))){
        $companyName_error = "Field is required!";
    } else {
        $companyName = trim($_POST['companyName']);
    }

    // Validate Company Email Address
    if(empty(trim($_POST['companyEmail']))){
        $companyEmail_error = "Field is required!";
    } else {
        $companyEmail = trim($_POST['companyEmail']);
    }

    // Check for errors before dealing with the database
    if(empty($companyName_error) && empty($companyEmail_error)){
            // Prepare an INSERT statement
            $sql = "INSERT INTO company_details(companyName, companyEmail) VALUES(:companyName, :companyEmail)";
            if($stmt = $pdo->prepare($sql)){
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":companyName", $param_companyName, PDO::PARAM_STR);
                    $stmt->bindParam("companyEmail", $param_companyEmail, PDO::PARAM_STR);

                    // Set parameters
                    $param_companyName = $companyName;
                    $param_companyEmail = $companyEmail;

                    // Attempt to execute
                    if($stmt->execute()){
                        header("location: index.php?page=admin/company/company_details");
                    } else {
                        echo "There was an error. Please try again!";
                    }

            }
    }
}

?>

<!-- Header Template -->
<?=header_template('ADMIN | ADD cOMPANY DETAILS'); ?>

<!-- Navbar -->
<?php include_once "./admin/admin_includes/admin_navbar.inc.php"; ?>

<div class="container">
    <div id="profile">
        <div class="row">
           <div class="col-md-6">
           <form action="index.php?page=admin/company/addCompanyDetails" method="post" class="profile_edit" enctype="multipart/form-data" >
                <div class="row">
                    <!-- Company Email-->
                    <div class="col-6">
                <div class="form-group">
                    <label for="companyEmail">Company Email Address</label>
                    <input type="email" name="companyEmail" class="form-control 
                    <?php echo (!empty($companyEmail_error)) ? 'is-invalid' : ''; ?>">
                    <span class="errors text-danger"><?php echo $companyEmail_error; ?></span>
                </div>
                    </div>

                    <!-- Company Name -->
                    <div class="col-6">
                        <div class="form-group">
                            <label for="CompanyName">Company Name</label>
                            <input type="text" name="companyName" class="form-control 
                            <?php echo (!empty($companyName_error)) ? 'is-invalid' : ''; ?>">
                            <span class="errors text-danger"><?php echo $companyName_error; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Submit Btn -->
                <div class="form-group my-3">
                    <input type="submit" value="ADD" class="btn w-100">
                </div>
            </form>
           </div>
        </div>
    </div>
</div>

<!-- Footer Template -->
<?=footer_template(); ?>