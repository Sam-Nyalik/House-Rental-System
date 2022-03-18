<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
        $numberOfKitchens = trim($_POST['numberOfKitchens']);
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
    if (empty($propertyType_error) && empty($propertySize_error) && empty($propertyLocation_error) && empty($propertyAddress_error) && empty($propertyName_error) && empty($propertyPrice_error) && empty($numberOfRooms_error) && empty($numberOfKitchens_error) && empty($numberOfBathrooms_error) && empty($propertyWaterSource_error) && empty($propertyTotalNumber_error) && empty($propertyAvailability_error) && empty($propertyCategory_error)) {
        // Process Property Image 1
        if (!empty($_FILES['propertyImage1']['name'])) {
            move_uploaded_file($_FILES['propertyImage1']['tmp_name'], "propertyImages/" . $_FILES['propertyImage1']['name']);
            $propertyImage1 = "propertyImages/" . $_FILES['propertyImage1']['name'];

            // Process Property Image 2
            if (!empty($_FILES['propertyImage2']['name'])) {
                move_uploaded_file($_FILES['propertyImage2']['tmp_name'], "propertyImages/" . $_FILES['propertyImage2']['name']);
                $propertyImage2 = "propertyImages/" . $_FILES['propertyImage2']['name'];

                // Process Property Image 3
                if (!empty($_FILES['propertyImage3']['name'])) {
                    move_uploaded_file($_FILES['propertyImage3']['tmp_name'], "propertyImages/" . $_FILES['propertyImage3']['name']);
                    $propertyImage3 = "propertyImages/" . $_FILES['propertyImage3']['name'];

                    // Process Property Image 4
                    if (!empty($_FILES['propertyImage4']['name'])) {
                        move_uploaded_file($_FILES['propertyImage4']['tmp_name'], "propertyImages/" . $_FILES['propertyImage4']['name']);
                        $propertyImage4 = "propertyImages/" . $_FILES['propertyImage4']['name'];

                        // Process Property Image 5
                        if (!empty($_FILES['propertyImage5']['name'])) {
                            move_uploaded_file($_FILES['propertyImage5']['tmp_name'], "propertyImages/" . $_FILES['propertyImage5']['name']);
                            $propertyImage5 = "propertyImages/" . $_FILES['propertyImage5']['name'];

                            // Process Property Image 6
                            if (!empty($_FILES['propertyImage6']['name'])) {
                                move_uploaded_file($_FILES['propertyImage6']['tmp_name'], "propertyImages/" . $_FILES['propertyImage6']['name']);
                                $propertyImage6 = "propertyImages/" . $_FILES['propertyImage6']['name'];

                                if (!empty($_FILES['agentProfileImage']['name'])) {
                                    move_uploaded_file($_FILES['agentProfileImage']['tmp_name'], "agentProfileImages/" . $_FILES['agentProfileImage']['name']);
                                    $agentProfileImage = "agentProfileImages/" . $_FILES['agentProfileImage']['name'];

                                    // Prepare an INSERT statement
                                    $sql = "INSERT INTO all_properties(propertyType, propertySize, propertyLocation, propertyAddress, propertyName, propertyPrice, numberOfRooms, numberOfKitchens, numberOfBathrooms, propertyWaterSource, propertyTotalNumber, propertyAvailability, propertyImage1, propertyImage2, propertyImage3, propertyImage4, propertyImage5, propertyImage6, propertyCategory, agent_name, agent_prefix, agent_emailAddress, agent_profileImage) 
                                VALUES(:propertyType, :propertySize, :propertyLocation, :propertyAddress, :propertyName, :propertyPrice, :numberOfRooms, :numberOfKitchens, :numberOfBathrooms, :propertyWaterSource, :propertyTotalNumber, :propertyAvailability, :propertyImage1, :propertyImage2, :propertyImage3, :propertyImage4, :propertyImage5, :propertyImage6, :propertyCategory, :agent_name, :agent_prefix, :agent_emailAddress, :agent_profileImage)";

                                    if ($stmt = $pdo->prepare($sql)) {
                                        // Bind variables to the prepared statement as parameters
                                        $stmt->bindParam(":propertyType", $param_propertyType, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertySize", $param_propertySize, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyLocation", $param_propertyLocation, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyAddress", $param_propertyAddress, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyName", $param_propertyName, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyPrice", $param_propertyPrice, PDO::PARAM_STR);
                                        $stmt->bindParam(":numberOfRooms", $param_numberOfRooms, PDO::PARAM_STR);
                                        $stmt->bindParam(":numberOfKitchens", $param_numberOfKitchens, PDO::PARAM_STR);
                                        $stmt->bindParam(":numberOfBathrooms", $param_numberOfBathrooms, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyWaterSource", $param_propertyWaterSource, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyTotalNumber", $param_propertyTotalNumber, PDO::PARAM_INT);
                                        $stmt->bindParam(":propertyAvailability", $param_propertyAvailability, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyImage1", $param_propertyImage1, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyImage2", $param_propertyImage2, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyImage3", $param_propertyImage3, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyImage4", $param_propertyImage4, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyImage5", $param_propertyImage5, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyImage6", $param_propertyImage6, PDO::PARAM_STR);
                                        $stmt->bindParam(":propertyCategory", $param_propertyCategory, PDO::PARAM_STR);
                                        $stmt->bindParam(":agent_name", $param_agentName, PDO::PARAM_STR);
                                        $stmt->bindParam(":agent_prefix", $param_agentPrefix, PDO::PARAM_STR);
                                        $stmt->bindParam(":agent_emailAddress", $param_agentEmailAddress, PDO::PARAM_STR);
                                        $stmt->bindParam(":agent_profileImage", $param_agentProfileImage, PDO::PARAM_STR);

                                        // Set parameters
                                        $param_propertyType = $propertyType;
                                        $param_propertySize = $propertySize;
                                        $param_propertyLocation = $propertyLocation;
                                        $param_propertyAddress = $propertyAddress;
                                        $param_propertyName = $propertyName;
                                        $param_propertyPrice = $propertyPrice;
                                        $param_numberOfRooms = $numberOfRooms;
                                        $param_numberOfKitchens = $numberOfKitchens;
                                        $param_numberOfBathrooms = $numberOfBathrooms;
                                        $param_propertyWaterSource = $propertyWaterSource;
                                        $param_propertyTotalNumber = $propertyTotalNumber;
                                        $param_propertyAvailability = $propertyAvailability;
                                        $param_propertyImage1 = $propertyImage1;
                                        $param_propertyImage2 = $propertyImage2;
                                        $param_propertyImage3 = $propertyImage3;
                                        $param_propertyImage4 = $propertyImage4;
                                        $param_propertyImage5 = $propertyImage5;
                                        $param_propertyImage6 = $propertyImage6;
                                        $param_propertyCategory = $propertyCategory;
                                        $param_agentName = $agentFullName;
                                        $param_agentPrefix = $agentPrefix;
                                        $param_agentEmailAddress = $agentEmailAddress;
                                        $param_agentProfileImage = $agentProfileImage;

                                        // Attempt to execute
                                        if ($stmt->execute()) {
                                            $success = "Property has been inserted successfully!";
                                        } else {
                                            $error = "There was an error. Please try again!";
                                        }
                                    }
                                }
                            } else {
                                $propertyImage6_error = "Field is required!";
                            }
                        } else {
                            $propertyImage5_error = "Field is required!";
                        }
                    } else {
                        $propertyImage4_error = "Field is required";
                    }
                } else {
                    $propertyImage3_error = "Field is required!";
                }
            } else {
                $propertyImage2_error = "Field is required!";
            }
        } else {
            $propertyImage1_error = "Field is required!";
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

                                <!-- Errors and Success Message -->
                                <div class="form-group">
                                    <p class="errors text-success"><?php
                                                                    if ($success) {
                                                                        echo $success;
                                                                    }
                                                                    ?></p>
                                    <p class="errors text-danger"><?php
                                                                    if ($error) {
                                                                        echo $error;
                                                                    }
                                                                    ?></p>
                                </div>

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
                                            <input type="text" name="propertySize" value="<?php echo $propertySize; ?>" class="form-control 
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
                                            <input type="text" name="propertyLocation" value="<?php echo $propertyLocation; ?>" class="form-control 
                                            <?php echo (!empty($propertyLocation_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyLocation_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Address -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="propertyAddress">Property Address</label>
                                            <input type="text" name="propertyAddress" value="<?php echo $propertyAddress; ?>" class="form-control 
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
                                            <input type="text" name="propertyName" value="<?php echo $propertyName; ?>" class="form-control 
                                        <?php echo (!empty($propertyName_error)) ? 'is-invalid' : ''; ?>">
                                            <span class="errors text-danger"><?php echo $propertyName_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Price -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="propertyPrice">Property Price <span>( <small>in Ksh.</small><span class="text-danger">* </span></span>)</label>
                                            <input type="text" name="propertyPrice" value="<?php echo $propertyPrice; ?>" class="form-control 
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
                                            <select name="numberOfRooms" class="form-control <?php echo (!empty($numberOfRooms_error)) ? 'is-invalid' : ''; ?>">
                                                <option value="Select Number Of rooms" disabled>Select Number Of Rooms</option>
                                                <option value="Studio Apartment">Studio Apartment</option>
                                                <option value="1 Bedroom">1 Bedroom</option>
                                                <option value="2 Bedroom">2 Bedrooms</option>
                                                <option value="3 Bedrooms">3 Bedrooms</option>
                                                <option value="4 Bedrooms">4 Bedrooms</option>
                                                <option value="5 bedrroms and above">5 Bedrooms and above</option>
                                            </select>
                                            <span class="errors text-danger"><?php echo $numberOfRooms_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Number Of Kitchens -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="numberOfKitchens">Number Of Kitchens</label>
                                            <select name="numberOfKitchens" class="form-control <?php echo (!empty($numberOfKitchens_error) ? 'is-invalid' : '') ?>">
                                                <option value="Select Number" disabled>Select Number</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                                <option value="4">Four</option>
                                                <option value="5 and above">Five and above</option>
                                            </select>
                                            <span class="errors text-danger"><?php echo $numberOfKitchens_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Number Of Bathrooms -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="NumberOfBathrooms">Number Of Bathrooms</label>
                                            <select name="numberOfBathrooms" class="form-control">
                                                <option value="Select Number Of Bathrooms" disabled>Select Number Of Bathrooms</option>
                                                <option value="All Ensuite">All Ensuite</option>
                                                <option value="1 Bathroom">1 Bathroom</option>
                                                <option value="2 Bathrooms">2 Bathrooms</option>
                                                <option value="3 Bathrooms">3 Bathrooms</option>
                                                <option value="4 Bathrooms">4 Bathrooms</option>
                                                <option value="5 Bathrooms and above">5 Bathrooms and above</option>
                                            </select>
                                            <span class="errors text-danger"><?php echo $numberOfBathrooms_error; ?></span>
                                        </div>
                                    </div>

                                    <!-- Property Water Source -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyWaterSource">Property Water Source</label>
                                            <select name="propertyWaterSource" class="form-control">
                                                <option value="Select Property Water Source" disabled>Select Property Water Source</option>
                                                <option value="Ground Water">Ground Water</option>
                                                <option value="Surface Water">Surface Water</option>
                                            </select>
                                            <span class="errors text-danger"><?php echo $propertyWaterSource_error; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Property Total Number -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PropertyTotalNumber">Property Total Number</label>
                                            <input type="text" name="propertyTotalNumber" value="<?php echo $propertyTotalNumber; ?>" class="form-control 
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
                                                <option value="Almost Full">Almost Full</option>
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