<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile-pic'])) {
    $uploadDir = 'uploads/';
    $fileTmpPath = $_FILES['profile-pic']['tmp_name'];
    $fileName = basename($_FILES['profile-pic']['name']);
    $fileSize = $_FILES['profile-pic']['size'];
    $fileType = $_FILES['profile-pic']['type'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($fileType, $allowedTypes) && $fileSize <= 2 * 1024 * 1024) {
        $targetPath = $uploadDir . uniqid() . "_" . $fileName;
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $targetPath)) {
            // Save path to session
            $_SESSION['profile_pic'] = $targetPath;
        }
    }
}

header("Location: profile.php"); // or whatever your profile page is named
exit();

if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == 0) {
    $uploadDir = 'uploads/';
    $filename = basename($_FILES['profile-pic']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $targetFile)) {
        // Optionally store path in session or DB
        echo "Uploaded successfully!";
    } else {
        echo "Upload failed.";
    }

    $_SESSION['profile_pic'] = 'uploads/' . $newFileName; // Or wherever you store it

}


?>