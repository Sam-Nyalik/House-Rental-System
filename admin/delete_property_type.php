<?php

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Prepare a DELETE statement
$sql = "DELETE FROM property_type WHERE id = :id";
if ($stmt = $pdo->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
    // Set parameters
    $param_id = $_GET['id'];
    // Attempt to execute
    if ($stmt->execute()) {
        header("location: index.php?page=admin/property_type");
    } else {
        echo "There is an error. Hang tight, our developers are on it!";
    }

    // Close the statement
    unset($stmt);
}
