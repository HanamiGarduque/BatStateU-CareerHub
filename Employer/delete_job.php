<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isEmployer()) {
    header('Location: ../logout.php');
    exit();
}
header('Content-Type: application/json');

$response = ['success' => false];


if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    $database = new Database();
    $conn = $database->getConnect();
    $job = new Jobs($conn);

    $removed = $job->deleteJob($job_id);

    if ($removed) {
        header("Location: job_postings.php");

        echo "removed";
    } else {
        header("Location: job_postings.php?error=remove_failed");
        exit;
    }
}
