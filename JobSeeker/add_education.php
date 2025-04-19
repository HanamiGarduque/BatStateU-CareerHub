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
$education = new Education($db);
$user_id = $_SESSION['id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $degree = $_POST['degree'];
        $institution = $_POST['institution'];
        $start_date = $_POST['start_year']; // renamed to match your table
        $end_date = $_POST['end_year'];
        $description = $_POST['description'];
        

        $education->addEducation($user_id, $degree, $institution, $start_date, $end_date, $description);
        header("Location: profile.php?tab=education");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>