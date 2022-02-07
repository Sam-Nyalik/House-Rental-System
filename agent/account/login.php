<?php

// Start session
session_start();

// Check if the agent is logged in or not

// Database connection
include_once "functions/functions.php";
$pdo = databaseConnect();

?>

<!-- Header Template -->
<?= header_template('AGENT | LOGIN'); ?>

<!-- Login TopBar Script -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="login-topbar">
            </div>
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

<!-- Agent Login Form Script -->
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="title">
                    <h5>Real Estate Agent Sign In</h5>
                    <hr>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <form action="index.php?page=agent/account/login" method="post" class="login-form">
                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="emailAddress">Email Address</label>
                        <input type="email" name="email" class="form-control 
                        <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>">
                        <span class="errors text-danger"><?php echo $email_error; ?></span>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control 
                        <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>">
                        <span class="errors text-danger"><?php echo $password_error; ?></span>
                    </div>

                    <!-- Forgot Password -->
                    <div class="form-group" style="float: right;">
                        <a href="#">
                            <h6 style="font-size: 15px;">Forgot Password?</h6>
                        </a>
                    </div>

                    <!-- Login Btn -->
                    <div class="form-group my-3">
                        <input type="submit" value="Login" class="btn w-100">
                    </div>
                </form>
            </div>

            <div class="col-md-6" style="padding: 0 20px">
                <div class="register_redirect">
                    <h5>Don't have an account yet?</h5>
                    <hr>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil ducimus nulla aperiam possimus totam incidunt iusto obcaecati perferendis tempore pariatur.</p>
                    <a href="index.php?page=agent/account/register" class="btn w-100">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>
