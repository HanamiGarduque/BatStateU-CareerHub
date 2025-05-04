<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';
if (!isJobseeker()) {
    header('Location: ../logout.php');
    exit();
}
header('Content-Type: application/json');

$response = ['success' => false];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saved_job_id'])) {
    $database = new Database();
    $conn = $database->getConnect();
    $bookmark = new Bookmarks($conn);

    $saved_job = $_POST['saved_job_id'];
    $user_id = $_SESSION['id'];

    $removed = $bookmark->removeBySavedJob($user_id, $saved_job);

    if ($removed) {
        header("Location: saved_jobs.php");

        echo "removed";
    } else {
        header("Location: saved_jobs.php?error=remove_failed");
        exit;
    }
}
