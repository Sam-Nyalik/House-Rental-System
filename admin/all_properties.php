<?php

// Start session
session_start();

// Check if the admin is logged in or not
include_once "./admin/admin_includes/check_login.inc.php";

// Database Connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Define variables and assign them empty values
$propertyType = $propertySize = $propertyLocation = $propertyAddress = $propertyName = $propertyPrice = $numberOfRooms = $numberOfKitchens = $numberOfBathrooms = $propertyWaterSource = $propertyTotalNumber = $propertyAvailability = $propertyImage1 = $propertyImage2 = $propertyImage3 = $propertyImage4 = $propertyImage5 = $propertyImage6 = $propertyCategory = $agentPrefix = $agentFullName = $agentEmailAddress = $agentProfileImage = $success = "";
$propertyType_error = $propertySize_error = $propertyLocation_error = $propertyAddress_error = $propertyName_error = $propertyPrice_error = $numberOfRooms_error = $numberOfKitchens_error = $numberOfBathrooms_error = $propertyWaterSource_error = $propertyTotalNumber_error = $propertyAvailability_error = $propertyImage1_error = $propertyImage2_error = $propertyImage3_error = $propertyImage4_error = $propertyImage5_error = $propertyImage6_error = $propertyCategory_error = $error = "";

// Process user input when the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate Property Type
    if (empty($_POST['propertyType'])) {
        $propertyType_error = "Field is required!";
    } else {
        $propertyType = $_POST['propertyType'];
    }

    // Validate Property Size
    if (empty(trim($_POST['propertySize']))) {
        $propertySize_error = "Field is required!";
    } else {
        $propertySize = trim($_POST['propertySize']);
    }

    // Validate Property Location
    if (empty(trim($_POST['propertyLocation']))) {
        $propertyLocation_error = "Field is required!";
    } else {
        $propertyLocation = trim($_POST['propertyLocation']);
    }

    // Validate Property Address
    if (empty(trim($_POST['propertyAddress']))) {
        $propertyAddress_error = "Field is required!";
    } else {
        $propertyAddress = trim($_POST['propertyAddress']);
    }

    // Validate Property Name
    if (empty(trim($_POST['propertyName']))) {
        $propertyName_error = "Field is required!";
    } else {
        $propertyName = trim($_POST['propertyName']);
    }

    // Validate Property Price
    if (empty(trim($_POST['propertyPrice']))) {
        $propertyPrice_error = "Field is required!";
    } else {
        $propertyPrice = trim($_POST['propertyPrice']);
    }

    // Validate Number Of Rooms
    if (empty(trim($_POST['numberOfRooms']))) {
        $numberOfRooms_error = "Field is required!";
    } else {
        $numberOfRooms = trim($_POST['numberOfRooms']);
    }

    // Validate Number Of Kitchens
    if (empty(trim($_POST['numberOfKitchens']))) {
        $numberOfKitchens_error = "Field is required!";
    } else {
        $numberOfKitchens = trim($_POST['numberOfkitchens']);
    }

    // Validate Number Of Bathrooms
    if (empty(trim($_POST['numberOfBathrooms']))) {
        $numberOfBathrooms_error = "Field is required!";
    } else {
        $numberOfBathrooms = trim($_POST['numberOfBathrooms']);
    }

    // Validate Property Water Source
    if (empty(trim($_POST['propertyWaterSource']))) {
        $propertyWaterSource_error = "Field is required!";
    } else {
        $propertyWaterSource = trim($_POST['propertyWaterSource']);
    }

    //  Validate Property Total Number
    if (empty(trim($_POST['propertyTotalNumber']))) {
        $propertyTotalNumber_error = "Field is required!";
    } else {
        $propertyTotalNumber = trim($_POST['propertyTotalNumber']);
    }

    // Validate Property Availability
    if (empty($_POST['propertyAvailability'])) {
        $propertyAvailability_error = "Field is required!";
    } else {
        $propertyAvailability = $_POST['propertyAvailability'];
    }

    // Validate Property Category
    if (empty($_POST['propertyCategory'])) {
        $propertyCategory_error = "Field is required!";
    } else {
        $propertyCategory = $_POST['propertyCategory'];
    }

    // Validate Agent Prefix
    $allowedToAgentPrefix = false;

    if (isset($_POST['agentPrefix']) && !empty($_POST['agentPrefix'])) {
        // Array of allowed input
        $allowed_answers = array('Mr.', 'Mrs.', 'Ms.', 'Other');

        // User Input
        $agentPrefix = $_POST['agentPrefix'];

        if (in_array($agentPrefix, $allowed_answers)) {
            // Check if the user chose Mr.
            if (strcasecmp($agentPrefix, 'Mr.') == 0) {
                $allowedToAgentPrefix = True;
            }
        }
    }

    // Validate Agent Full Name
    if (!empty(trim($_POST['agentFullName']))) {
        $agentFullName = trim($_POST['agentFullName']);
    }

    // Validate Agent Email Address
    if (!empty(trim($_POST['agentEmailAddress']))) {
        $agentEmailAddress = trim($_POST['agentEmailAddress']);
    }

    // Check for errors before dealing with the database
    if(empty($propertyType_error) && empty($propertySize_error) && empty($propertyLocation_error) && empty($propertyAddress_error) && empty($propertyName_error) && empty($propertyPrice_error) && empty($numberOfRooms_error) && empty($numberOfKitchens_error) && empty($numberOfBathrooms_error) && empty($propertyWaterSource_error) && empty($propertyTotalNumber_error) && empty($propertyAvailability_error) && empty($propertyCategory_error)){
        // Process Property Image 1
        if(!empty($_FILES['propertyImage1'])){
            move_uploaded_file($_FILES['propertyImage1']['tmp_name'], "./");
        }

    }
}

?>

<!-- Header Template -->
<?= header_template('ADMIN | ALL_PROPERTIES'); ?>

<div id="body">
    <!-- Admin Navbar -->
    <?php include_once "./admin/admin_includes/admin_navbar.inc.php"; ?>

    <!-- Page Title & Breadcrumb -->
    <div class="container-fluid">
        <div class="page_title">
            <div class="row">
                <h5>Admin All Properties</h5>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item acive">All Properties</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Add Properties -->
    <div class="container-fluid">
        <div id="profile">
            <div class="row">
                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5><i class="bi bi-plus lg"></i> Add Property</h5>
                            <hr>

                            <form action="index.php?page=admin/all_properties" method="post" class="profile_edit" enctype="multipart/form-data">
                                <?php
                                //Prepare a SELECT statement to fetch all the property types from the database
                                $sql = $pdo->prepare("SELECT * FROM property_type");
                                $sql->execute();
                                $database_property_types = $sql->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <div class="row">
                                    <!-- Propert Type -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyType">Property Type</label>
                                            <select name="propertyType" class="form-control <?php echo (!empty($propertyType_error)) ? 'is-invalid' : ''; ?>">
                                                <option value="Select Property Type" disabled>Select Property Type</option>
                                                <?php foreach ($database_property_types as $property_type) : ?>
                                                    <option value="<?= $property_type['name']; ?>"><?= $property_type['name']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <span class="errors text-danger"><?php echo $propertyType_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Size -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertySize">Property Size</label>
                                            <input type="text" name="propertySize" class="form-control 
                                            <?php echo (!empty($propertySize_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertySize_error ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Property Location -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyLocation">Property Location</label>
                                            <input type="text" name="propertyLocation" class="form-control 
                                            <?php echo (!empty($propertyLocation_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyLocation_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Address -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="propertyAddress">Property Address</label>
                                            <input type="text" name="propertyAddress" class="form-control 
                                            <?php echo (!empty($propertyAddress_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyAddress_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Property Name -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="propertyName">Property Name</label>
                                            <input type="text" name="propertyName" class="form-control 
                                        <?php echo (!empty($propertyName_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyName_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Price -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="propertyPrice">Property Price <span>( <small>in Ksh.</small><span class="text-danger">* </span></span>)</label>
                                            <input type="text" name="propertyPrice" class="form-control 
                                            <?php echo (!empty($propertyPrice_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyPrice_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Number Of Rooms -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="NumberOfRooms">Number Of Rooms</label>
                                            <input type="text" name="numberOfRooms" class="form-control 
                                        <?php echo (!empty($numberOfRooms_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $numberOfRooms_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Number Of Kitchens -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="numberOfKitchens">Number Of Kitchens</label>
                                            <input type="text" name="numberOfKitchens" class="form-control 
                                            <?php echo (!empty($numberOfKitchens_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $numberOfKitchens_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Number Of Bathrooms -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="NumberOfBathrooms">Number Of Bathrooms</label>
                                            <input type="text" name="numberOfBathrooms" class="form-control 
                                        <?php echo (!empty($numberOfBathrooms_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $numberOfBathrooms_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Water Source -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyWaterSource">Property Water Source</label>
                                            <input type="text" name="propertyWaterSource" class="form-control 
                                            <?php echo (!empty($propertyWaterSource_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyWaterSource_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Property Total Number -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyTotalNumber">Property Total Number</label>
                                            <input type="text" name="propertyTotalNumber" class="form-control 
                                            <?php echo (!empty($propertyTotalNumber_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyTotalNumber_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Availability -->
                                    <div class="col-6">
                                        <div class="form-froup">
                                            <label for="PropertyAvailability">Property Availability</label>
                                            <select name="propertyAvailability" class="form-control <?php echo (!empty($propertyAvailability_error)) ? 'is-invalid' : ''; ?>">
                                                <option value="Select Availability" disabled>Select Property Availability</option>
                                                <option value="Full">Full</option>
                                                <option value="Empty">Empty</option>
                                            </select>
                                            <span class="errors text-danger"><?php echo $propertyAvailability_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Property Image 1 -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyImage1">Property Image 1</label>
                                            <input type="file" name="propertyImage1" class="form-control 
                                            <?php echo (!empty($propertyImage1_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyImage1_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Image 2 -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="propertyImage2">Property Image 2</label>
                                            <input type="file" name="propertyImage2" class="form-control 
                                            <?php echo (!empty($propertyImage2_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyImage2_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Property Image 3 -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyImage3">Property Image 3</label>
                                            <input type="file" name="propertyImage3" class="form-control 
                                            <?php echo (!empty($propertyImage3_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyImage3_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Image 4 -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="propertyImage2">Property Image 4</label>
                                            <input type="file" name="propertyImage4" class="form-control 
                                            <?php echo (!empty($propertyImage4_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyImage4_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Property Image 5 -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyImage5">Property Image 5</label>
                                            <input type="file" name="propertyImage5" class="form-control 
                                            <?php echo (!empty($propertyImage5_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyImage5_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Image 6 -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="propertyImage6">Property Image 6</label>
                                            <input type="file" name="propertyImage6" class="form-control 
                                            <?php echo (!empty($propertyImage6_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyImage6_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Property Category -->
                                <?php
                                // Fetch property category names from the property_category table in the database
                                $sql = $pdo->prepare("SELECT * FROM property_category");
                                $sql->execute();
                                $database_property_category = $sql->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <div class="form-group">
                                    <label for="PropertyCategory">Property Category</label>
                                    <select name="propertyCategory" class="form-control">
                                        <option value="Select Property Category" disabled>Select Property Category</option>
                                        <?php foreach ($database_property_category as $property_category) : ?>
                                            <option value="<?= $property_category['name']; ?>"><?= $property_category['name']; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <!-- PROPERTY AGENT PROFILE SECTION -->
                                <h5 class="text-center text-uppercase text-dark my-3">
                                    Property Agent profile(If Any)
                                </h5>
                                <hr>
                                <div class="row">
                                    <!-- Agent Prefix -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="Prefix">Agent Prefix</label>
                                            <select name="agentPrefix" class="form-control">
                                                <option value="Select Agent Prefix" disabled>Select Agent Prefix</option>
                                                <option value="Mr.">Mr.</option>
                                                <option value="Mrs.">Mrs.</option>
                                                <option value="Ms.">Ms.</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Agent FullName -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="Agent FullName">Agent Full Name</label>
                                            <input type="text" name="agentFullName" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Agent EmailAddress -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="AgentEmailAddress">Agent Email Address</label>
                                            <input type="email" name="agentEmailAddress" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Agent Profile Image -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="AgentProfileImage">Agent Profile Image</label>
                                            <input type="file" name="agentProfileImage" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group my-3">
                                    <input type="submit" value="Add Property" class="btn w-100">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Template -->
<?= footer_template(); ?>