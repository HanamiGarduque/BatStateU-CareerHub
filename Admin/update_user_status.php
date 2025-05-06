<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isAdmin()) {
    header('Location: /ADMSSYSTEM/logout.php');
    exit();
}

$database = new Database();
$db = $database->getConnect();
$jobseeker = new Users($db);

if (isset($_POST['user_id']) && isset($_POST['new_status'])) {

    $user_id = $_POST['user_id'];
    $new_status = $_POST['new_status'];

    echo $user_id;
    echo $new_status;

    if ($jobseeker->updateUserStatus($user_id, $new_status)) {
        $_SESSION['status_success'] = "User status updated successfully!";
        header("Location: homepage.php");
        exit();
    } else {
        $_SESSION['status_error'] = "Failed to update application status.";
        header("Location: homepage.php");
        exit();    }
    exit();
} else {
    $_SESSION['status_error'] = "Invalid request.";
    header("Location: homepage.php");
    exit();
}
