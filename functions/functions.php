<?php

// Database connection
function databaseConnect()
{

    $DATABASE_HOST = "localhost";
    $DATABASE_USER = "root";
    $DATABASE_PASSWORD = "";
    $DATABASE_NAME = "house-rental-system";

    try {
        return new PDO("mysql:host=" . $DATABASE_HOST . ";dbname=" . $DATABASE_NAME . ";charset=utf8", $DATABASE_USER, $DATABASE_PASSWORD);
    } catch (PDOException $exception) {
        // Stop the script and generate an error if there is a problem with the connection
        exit("Connection to the database was unsuccessfull!" . $exception->getMessage());
    }
}

// Header Template
function header_template($title)
{

    $element = "
        <!DOCTYPE html>
        <html lang=\"en\">
        <head>
        <title>$title</title>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <meta name=\"description\" content=\"A complete PHP and mySQL house rental system\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
        <meta name=\"author\" content=\"Sam Nyalik\">
        <meta name=\"robots\" content=\"index, follow\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"css/styles.css\">
        <link rel=\"stylesheet\" href=\"css/bootstrap.min.css\">
        <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css\">
        <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
        <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
        <link href=\"https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap\" rel=\"stylesheet\"> 
        <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
        <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
        <link href=\"https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap\" rel=\"stylesheet\"> 
        <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
        <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
        <link href=\"https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap\" rel=\"stylesheet\"> 
        <link rel=\"icon\" href=\"images/image1.jpg\" sizes=\"16*16\">
        </head>
    ";
    echo $element;
}

// Main Footer
function main_footer()
{
    $element = "
        <div id=\"main-footer\">
            <div class=\"container-fluid\">
                <div class=\"row\">
                    <div class=\"col-md-4\">
                        <h3>NYALIK REAL ESTATE</h3>
                        <hr>
                        <p class=\"description\">&copy;2022 | All Rights Reserved</p>
                    </div>
                    <div class=\"col-md-4\">
                        <h4>PARTNERS</h4>
                        <hr>
                    </div>
                    <div class=\"col-md-4\">
                        <h4>SPONSORS</h4>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    ";
    echo $element;
}

// Footer Template
function footer_template()
{

    $element = "
        <script src=\"js/main.js\"></script>
        <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p\" crossorigin=\"anonymous\"></script>

    ";
    echo $element;
}
