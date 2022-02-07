<?php
$id = false;
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}
?>
<div id="admin_navbar">
    <div class="container-fluid">
        <div class="row pt-3 flex-nowrap justify-content-between align-items-center">
            <div class="col-4">
                <div id="sideNav" class="mySideNav">
                    <a href="javascript:void(0)" class="closeBtn" onclick="closeNav()"><i class="bi bi-x-lg"></i></a>
                    <a href="index.php?page=admin/house_type"><i class="bi bi-house"></i> House Type</a>
                    <hr>
                    <a href="#"><i class="bi bi-people"></i> Tenants</a>
                    <hr>
                    <a href="#"><i class="bi bi-person"></i> Users</a>
                    <hr>
                    <li><a href="index.php?page=admin/all_properties"><i class="bi bi-building"></i> All properties</a></li>
                    <hr>
                </div>

                <div class="nav_buttons" onclick="openNav()">
                    <div class="btn1"></div>
                    <div class="btn2"></div>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="nav_title">
                    <?php
                    // Fetch Company Name from the database(company_details table)
                    $sql = $pdo->prepare("SELECT companyName FROM company_details");
                    $sql->execute();
                    $database_company_name = $sql->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php foreach ($database_company_name as $companyName) : ?>
                        <h5><a href="index.php?page=admin/dashboard"><?= $companyName['companyName']; ?></a></h5>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">
                <!-- <p>Notifications</p> -->
                <!-- Admin profile Dropdown -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <!-- Fetch the admin profile photo from the database -->
                        <?php
                        $sql = $pdo->prepare("SELECT * FROM admin WHERE id = '$id'");
                        $sql->execute();
                        $database_admin_profile_image = $sql->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <?php foreach ($database_admin_profile_image as $admin_profile_image) : ?>
                            <img src="<?= $admin_profile_image['profileImage']; ?>" class="img-fluid profileImage" id="adminprofileDropdownMenu" alt="<?= $admin_profile_image['firstName']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                            <ul class="dropdown-menu" aria-labelledby="adminProfileDropdownMenu">
                                <div class="dropdown_header">
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="index.php?page=admin/account/profile"> <img src="<?= $admin_profile_image['profileImage']; ?>" alt="" class="img-fluid profileImage"></a>
                                        </div>
                                        <div class="col-8">
                                            <h5><?= $admin_profile_image['firstName']; ?> <?= $admin_profile_image['lastName']; ?></h5>
                                            <p>Administrator</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown_body">
                                    <li><a href="index.php?page=admin/account/profile"><i class="bi bi-person-circle"></i> Profile</a></li>
                                    <hr>
                                    <li><a href="index.php?page=admin/company/company_details"><i class="bi bi-gear"></i> Company Settings</a></li>
                                    <hr>
                                    <li><a href="index.php?page=admin/account/logout" onclick="return confirm('Are you sure you want to sign out?')"><i class="bi bi-box-arrow-left"></i> Sign Out</a></li>
                                </div>
                            </ul>
                        <?php endforeach; ?>
                    </li>
                </ul>

                <!-- Notification button -->
                <div class="notification_btn">
                    <div class="btn1"></div>
                    <div class="btn2"></div>
                    <div class="btn3"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function openNav() {
    document.getElementById("sideNav").style.width = "250px";
    document.getElementById("body").style.marginLeft = "250px";
    document.style.body.backgroundColor = "rgba(0,0,0,0.4)";
}

function closeNav(){
    document.getElementById("sideNav").style.width = "0";
    document.getElementById("body").style.marginLeft = "0";
    document.body.style.backgroundColor = "white";
}

</script>
