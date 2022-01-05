<?php

// Start session
session_start();

// Database Connection
include_once "functions/functions.php";
$pdo = databaseConnect();

// Prepare a DELETE statement
$sql = "DELETE FROM agent WHERE id = :id";
if ($stmt = $pdo->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
    // Set parameters
    $param_id = $_GET['id'];
    // Attempt to execute
    if ($stmt->execute()) {
        header("location: index.php?page=admin/dashboard");
    } else {
        echo "There was an error. Our developers are on it!";
    }

    // Close the statement
    unset($stmt);
}
