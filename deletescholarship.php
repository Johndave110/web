<?php
session_start();
require_once "classes/Scholarship.php";

// Admin check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Make sure ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: scholarmanagement.php");
    exit();
}

$scholarObj = new Scholarship();
$id = intval($_GET['id']);

// Delete the scholarship
if($scholarObj->deleteScholarship($id)){
    // Redirect back to scholarship management after deletion
    header("Location: scholarmanagement.php");
    exit();
} else {
    echo "Error: Unable to delete scholarship.";
}
?>
