<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
    header('Location: ../login.php');
    exit();
}

$database = new Database();
$db = $database->getConnect();

$job = new Jobs($db);

if (isset($_GET['job_id'])) {
    $jobId = ($_GET['job_id']); 
    
    error_log("Job ID: " . $jobId);
    
    $jobDetails = $job->getJobById($jobId);

    if ($jobDetails) {
        header('Content-Type: application/json');
        echo json_encode($jobDetails);
    } else {
        echo json_encode(['error' => 'Job not found']);
    }
} else {
    echo json_encode(['error' => 'job_id is required']);
}

?>
