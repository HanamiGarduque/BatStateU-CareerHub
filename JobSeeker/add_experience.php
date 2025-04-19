<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
    header('Location: /ADMSSYSTEM/logout.php');
    exit();
}

$database = new Database();
$db = $database->getConnect();

$experience = new Experiences($db);
$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = isset($_POST['company_name']) ? $_POST['company_name'] : '';
    $job_title = isset($_POST['job_title']) ? $_POST['job_title'] : '';
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    try {
        $experience->addExperiences($user_id, $job_title, $company_name, $start_date, $end_date, $description);
        header("Location: profile.php?tab=skills");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
