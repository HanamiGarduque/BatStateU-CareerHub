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

$skills = new Skills($db);
$user_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["skill"])) {
    $skill = trim($_POST["skill"]);

    if (!empty($skill)) {
        try {

            if ($skills->checkduplicateSkill($user_id, $skill) == 0) {
                $skills->addSkill($user_id, $skill);
                header("Location: profile.php?tab=skills");
                exit;
            }
        } catch (PDOException $e) {
            echo "❌ Error: " . $e->getMessage();
        }
    }
}
