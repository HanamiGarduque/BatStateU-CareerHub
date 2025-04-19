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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
   try {
        if ($education->deleteEducation($id)) {
            header("Location: profile.php?tab=education");
        } else {
            $_SESSION['delete_message'] = "No matching education entry found for deletion.";
        }
        header("Location: profile.php?tab=education");
        exit();
    } catch (PDOException $e) {
        die("Error deleting education: " . $e->getMessage());
    }
}
?>