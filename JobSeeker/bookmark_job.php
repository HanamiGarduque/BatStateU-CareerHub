<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
    header('Location: ../login.php'); // Or wherever you want
    exit();
}
$user_id = $_SESSION['user_id']; // or however you store login info
$job_id = $_POST['job_id'];

$bookmark = new Bookmarks($conn);

if ($bookmark->isBookmarked($user_id, $job_id)) {
    $bookmark->remove($user_id, $job_id);
    echo 'removed';
} else {
    $bookmark->add($user_id, $job_id);
    echo 'added';
}
?>
