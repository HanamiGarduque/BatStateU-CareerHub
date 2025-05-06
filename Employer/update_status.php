<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['application_id']) && isset($_POST['status'])) {
        $application_id = $_POST['application_id'];
        $newStatus = $_POST['status'];

        $statusOrder = [
            'Under Review' => 1,
            'Shortlisted' => 2,
            'Interview' => 3,
            'Accepted' => 4,
            'Rejected' => 5
        ];

        $database = new Database();
        $db = $database->getConnect();
        $applications = new JobApplication($db);

        $currentData = $applications->getApplicationById($application_id);
        if (!$currentData) {
            header("Location: applications.php?error=application_not_found");
            exit;
        }

        $currentStatus = $currentData['status'];

        if (
            $statusOrder[$newStatus] <= $statusOrder[$currentStatus]
        ) {
            $_SESSION['status_error'] = "Failed to update application status.";
            header("Location: applications_management.php");
            exit();
        }

            $result = $applications->updateApplicationStatus($application_id, $newStatus);
        $logResult = $applications->insertToStatusLog($application_id, $newStatus);

        if ($result && $logResult) {
            $_SESSION['status_success'] = "Application status updated successfully!";
            header("Location: applications_management.php");
            exit();
        } else {
            $_SESSION['status_error'] = "Failed to update application status.";
            header("Location: applications_management.php");
            exit();
        }
    } else {
        header("Location: applications.php?error=invalid_input");
        exit;
    }
} else {
    header("Location: applications.php?error=invalid_method");
    exit;
}
?>
