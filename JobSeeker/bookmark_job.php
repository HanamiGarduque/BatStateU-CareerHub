<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $user_id = $_SESSION['id'];

    $database = new Database();
    $db = $database->getConnect();
    $bookmark = new Bookmarks($db);

    if ($bookmark->isBookmarked($user_id, $job_id)) {
        $bookmark->removeByUser($user_id, $job_id);
        $response = ['success' => true, 'isSaved' => false];
    } else {
        $bookmark->addBookmark($user_id, $job_id);
        $response = ['success' => true, 'isSaved' => true];
    }
}

echo json_encode($response);
