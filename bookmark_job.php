<?php

$user_id = $_SESSION['user_id'] ?? 1; // ← this should use session, fallback only for testing
$job_id = $_POST['job_id'] ?? null;

if ($user_id && $job_id) {
    $bookmark = new Bookmarks($db); // Or however you handle DB
    if ($bookmark->isBookmarked($user_id, $job_id)) {
        $bookmark->remove($user_id, $job_id);
        echo 'unsaved';
    } else {
        $bookmark->add($user_id, $job_id);
        echo 'saved';
    }
}


$database = new Database();
$db = $database->getConnect();
$bookmark = new Bookmarks($db);

if ($bookmark->isBookmarked($user_id, $job_id)) {
    $bookmark->remove($user_id, $job_id);
    echo 'unsaved';
} else {
    $bookmark->add($user_id, $job_id);
    echo 'saved';
}

?>