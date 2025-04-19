<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
  header('Location: /ADMSSYSTEM/logout.php'); // Or wherever you want
  exit();
}
$database = new Database(); 
$db = $database->getConnect();
$skills = new Skills($db);

$user_id = $_SESSION["id"];

if (isset($_POST['id'])) {
    $skill_id = $_POST['id'];
    echo $skill_id;
    echo $user_id;

    try {
        if ($skills->deleteSkill($user_id, $skill_id)) {
            echo "✅ Skill deleted successfully.";
            header("Location: profile.php?tab=skills");
            exit;            
        } else {
            echo "❌ Skill not found or not owned by the user.";
            exit;
        }
        // header("Location: profile.php");
        // exit;
    } catch (PDOException $e) {
        echo "❌ Error deleting skill: " . $e->getMessage();
    }
} else {
    echo "❌ Invalid request.";
}