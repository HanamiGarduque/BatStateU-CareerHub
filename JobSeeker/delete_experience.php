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

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $user_id = $_SESSION['id'];

    try {
        if ($experience->deleteExperience($id, $user_id))
        {
            header("Location: profile.php?tab=skills");
            exit();
        } else {
            echo "⚠️ Delete query ran but did not remove any rows.<br>";
            exit();
        }

    } catch (PDOException $e) {
        die("🛑 Error deleting experience: " . $e->getMessage());
    }
} else {
    echo "🚫 No experience ID received in POST.<br>";
    exit();
}