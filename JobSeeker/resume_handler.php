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

$resume = new Resumes($db);
$userId = $_SESSION['id'];
$userResumeDir = "resumes/user_" . $userId;

// Create directory if it doesn't exist
if (!file_exists($userResumeDir) && !is_dir($userResumeDir)) {
    mkdir($userResumeDir, 0755, true);
}

// Handle resume upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload') {
        // Handle file upload
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['resume'];

            // Validate file type
            $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $file_type = mime_content_type($file['tmp_name']);

            if (!in_array($file_type, $allowed_types)) {
                header("Location: profile.php?resume_error=invalid_type");
                exit();
            }

            // Get file extension
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            // Generate a unique filename
            $timestamp = time();
            $filename = "resume_" . $timestamp . "." . $extension;
            $target_path = $userResumeDir . "/" . $filename;

            // Move the uploaded file
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                try {
                    echo $userId;
                    echo $filename;
                    echo $target_path;
                    echo $extension;
                    if ($resume->uploadResume($userId, $filename, $target_path, $extension)) {
                        header("Location: profile.php?resume_upload=success");
                        exit();
                    } else {
                        header("Location: profile.php?resume_error=upload_failed_db");
                        exit();
                    }
                } catch (PDOException $e) {
                    header("Location: profile.php?resume_error=db_error");
                    exit();
                }
            } else {
                header("Location: profile.php?resume_error=upload_failed");
                exit();
            }
        } else {
            header("Location: profile.php?resume_error=no_file");
            exit();
        }
    } elseif ($action === 'delete') {
        if (isset($_POST['file_name'])) {
            $filename = $_POST['file_name'];
            $file_path = $userResumeDir . "/" . $filename;

            // Make sure the file exists and is inside the user's directory
            if (file_exists($file_path) && is_file($file_path) && strpos($file_path, $userResumeDir) === 0) {
                try {

                    if ($resume->deleteResume($userId)) {
                        if (unlink($file_path)) {
                            header("Location: profile.php?tab=resume");
                            exit();
                        } else {
                            header("Location: profile.php?resume_error=delete_failed_file");
                            exit();
                        }
                    } else {
                        header("Location: profile.php?resume_error=delete_failed_db");
                        exit();
                    }
                } catch (PDOException $e) {
                    error_log("Error deleting resume: " . $e->getMessage());
                    header("Location: profile.php?resume_error=delete_db_error");
                    exit();
                }
            } else {
                header("Location: profile.php?resume_error=file_not_found");
                exit();
            }
        } else {
            header("Location: profile.php?resume_error=no_file_specified");
            exit();
        }
    }
    $stmt->closeCursor(); 

}
