<?php 
// Start session
session_start();

// Include the database
include_once "functions/functions.php";
$pdo = databaseConnect();

//Prepare a DELETE statement from the database specifying the id in the URL
$sql = ("DELETE FROM users WHERE id = :id");
if($stmt = $pdo->prepare($sql)){
    // Bind the variables as parameters
    $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
    //Set parameters
    $param_id = $_GET['id'];
    // Attempt to execute
    if($stmt->execute()){
        header("location: index.php?page=admin/dashboard");
    } else {
        echo "<script>alert('There was an eror deleting the user. Please try again!');</script>";
    }

}
