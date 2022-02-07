<?php

// Start session
session_start();

// Check if the admin is logged, if not redirect to the login page
include_once "admin_includes/check_login.inc.php";

include_once "functions/functions.php";
$pdo = databaseConnect();

?>

<!-- Header Template -->
<?= header_template('ADMIN | DASHBOARD'); ?>

<div id="body">
    <!-- Admin Navbar Script -->
    <?php include_once "admin_includes/admin_navbar.inc.php"; ?>

    <!-- Welcome Back Message -->
    <div class="container-fluid">
        <div class="welcome_message">
            <div class="row">
                <div class="col-md-6">
                    <span class="right_side">
                        <h5>Hi, welcome back!</h5>
                        <p>The company monitoring dashboard.</p>
                    </span>
                </div>

                <div class="col-md-6">
                    <span class="left_side">
                        <h6>Customer Ratings</h6>
                    </span>
                </div>
            </div>

            <!-- Add a few other things -->
        </div>
    </div>

    <!-- Totals -->
    <div class="container-fluid">
        <div id="property_totals">
            <div class="row">
                <!-- Properties on sale -->
                <div class="col-md-3">
                    <h5>Property Categories</h5>
                    <div class="row">
                        <div class="col-8">
                            <a href="index.php?page=admin/property_category">
                                <h6>View Categories <i class="bi bi-arrow-right-circle"></i></h6>
                            </a>
                        </div>
                        <!-- Fetch Total Property Categories from the database -->
                        <?php
                        $sql = $pdo->prepare("SELECT * FROM property_category");
                        $sql->execute();
                        $database_all_product_categories = $sql->rowCount();
                        ?>
                        <div class="col-4">
                            <h6>Total: <?php echo $database_all_product_categories; ?></h6>
                        </div>
                    </div>
                </div>

                <!-- Properties on rent -->
                <div class="col-md-3">
                    <h5>Property Types</h5>
                    <div class="row">
                        <div class="col-8">
                            <a href="index.php?page=admin/property_type">
                                <h6>View Property Types <i class="bi bi-arrow-right-circle"></i></h6>
                            </a>
                        </div>
                        <!-- Fetch Total Property Types from the database -->
                        <?php
                        $sql = $pdo->prepare("SELECT * FROM property_type");
                        $sql->execute();
                        $database_property_types = $sql->rowCount();
                        ?>
                        <div class="col-4">
                            <h6>Total: <?php echo $database_property_types; ?></h6>
                        </div>
                    </div>
                </div>

                <!-- Properties on sale -->
                <div class="col-md-3">
                    <h5>Properties on rent</h5>
                    <div class="row">
                        <div class="col-8">
                            <a href="#">
                                <h6>View Properties on rent</h6>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Properties on rent -->
                <div class="col-md-3">
                    <h5>Properties on Sale</h5>
                    <div class="row">
                        <div class="col-8">
                            <a href="#">
                                <h6>View Properties on sale</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Users -->
    <div class="container-fluid">
        <div id="profile">
            <div class="row">

                <!-- Agents -->
                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5><i class="bi bi-user"></i>Real Estate Agents</h5>
                            <hr>
                        </div>

                        <!-- Prepare a SELECT statement to fetch all the agents from the database -->
                        <?php
                        $sql = $pdo->prepare("SELECT * FROM agent");
                        $sql->execute();
                        $count = 1;
                        $database_total_agents = $sql->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Profile Image</th>
                                            <th>Name</th>
                                            <th>Creation Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php foreach ($database_total_agents as $total_agents) : ?>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><img src="<?= $total_agents['profileImage']; ?>" alt="<?= $total_agents['firstName']; ?>" class="img-fluid" style="height: 85px; width: 85px; border-radius: 50%"></td>
                                                <td><?= $total_agents['prefix']; ?> <?= $total_agents['firstName'] ?> <?= $total_agents['lastName']; ?></td>
                                                <td><?= $total_agents['dateAdded']; ?></td>
                                                <td class="text-center"><a href="index.php?page=admin/agents/agent_profile&id=<?= $total_agents['id']; ?>"><i class="bi bi-eye"></i></a> | <a href="index.php?page=admin/agents/delete_agent&id=<?= $total_agents['id']; ?>" class="text-danger" onclick="return confirm('Delete <?= $total_agents['firstName']; ?> <?= $total_agents['lastName']; ?> as an agent?')"><i class="bi bi-trash"></i></a></td>
                                            </tr>
                                        </tbody>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="col-md-6">
                    <div class="profile_side1">
                        <div class="title">
                            <h5><i class="bi bi-user"></i>Users</h5>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Footer Template -->
<?= footer_template(); ?>