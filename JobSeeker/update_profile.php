<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
    header('Location: /ADMSSYSTEM/logout.php');
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$database = new Database();
$db = $database->getConnect();

$jobseeker = new Users($db);
$user_id = $_SESSION['id'];
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $bio = $_POST["bio"];

    // Assign values to object
    $jobseeker->user_id = $user_id;
    $jobseeker->address = $address;
    $jobseeker->phone_number = $phone;
    $jobseeker->bio = $bio;

    if ($jobseeker->updateProfile()) {
        header("Location: profile.php?tab=personal");
        exit();
    } else {
        echo "error";
    }
}
