<?php
require_once '../check_session.php';
require_once '../Database/crud_functions.php';
require_once '../Database/db_connections.php';

if (!isJobseeker()) {
    header('Location: ../login.php'); // Or wherever you want
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BatStateU Career Hub - Job Application</title>
    <link rel="stylesheet" href="../Layouts/jobseeker.css">
    <link rel="stylesheet" href="../Layouts/application.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="dashboard-container">
        <main class="main-content">
            <div class="dashboard-content">
                <div class="application-wrapper">
                    <?php

                    $database = new Database();
                    $db = $database->getConnect();
                    $application = new JobApplication($db);
                    $jobseeker = new Users($db);
                    $job = new Jobs($db);

                    $job_id = isset($_GET['job_id']) ? $_GET['job_id'] : die('ERROR: Job ID not found.');
                    $user_id = $_SESSION['id'];
                    $userResumeDir = "resumes/user_" . $user_id;

                    // Create directory if it doesn't exist
                    if (!file_exists($userResumeDir) && !is_dir($userResumeDir)) {
                        mkdir($userResumeDir, 0755, true);
                    }

                    $success = false;
                    $error = '';
                    $alreadyApplied = $application->hasAlreadyApplied($job_id, $user_id);


                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyApplied) {
                        $cover_letter = isset($_POST['cover_letter']) ? $_POST['cover_letter'] : '';

                        // Handle file upload
                        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
                            $file = $_FILES['resume'];

                            // Validate file type
                            $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                            $file_type = mime_content_type($file['tmp_name']);

                            if (!in_array($file_type, $allowed_types)) {
                                $error = "Invalid file type. Only PDF and Word documents are allowed.";
                            } else {
                                // Get file extension
                                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

                                // Generate a unique filename
                                $timestamp = time();
                                $filename = "resume_" . $timestamp . "." . $extension;
                                $target_path = $userResumeDir . "/" . $filename;


                                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                                    // Save application to database
                                    if ($application->createApplication($job_id, $user_id, $cover_letter, $target_path)) {
                                        $success = true;
                                    } else {
                                        $error = "Failed to save application to database.";
                                    }
                                } else {
                                    $error = "Failed to move uploaded file.";
                                }
                            }
                        } else {
                            $error = "No file uploaded or an error occurred during upload.";
                        }
                    }

                    // Get job details

                    $stmt = $job->retrieveJobById($job_id);
                    if ($stmt && $stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $jobTitle = htmlspecialchars($row['title']) ?? 'Unknown Job Title';
                            $companyName = htmlspecialchars($row['company_name']) ?? 'Unknown Company';
                        }
                        $stmt->closeCursor(); // Close the cursor to free up resources

                    } else {
                        echo "<p>Error retrieving job details.</p>";
                        exit();
                    }

                    // Get user details
                    $stmt = $jobseeker->retrieveProfileById($user_id);
                    if ($stmt && $stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Extract variables
                            $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                            $email = htmlspecialchars($row['email']);
                            $phone = htmlspecialchars($row['phone_number']);
                        }
                    } else {
                        echo "<p>Error retrieving user profile.</p>";
                        exit();
                    }
                    ?>

                    <div class="application-header">
                        <h1><i class="fas fa-file-alt"></i> Job Application</h1>
                        <p class="job-title"> Applying for: <strong><?= $jobTitle ?></strong> at <strong><?= $companyName ?></strong></p>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <h4>Application Submitted!</h4>
                                <p>Your application has been successfully submitted. You can track its status in your dashboard.</p>
                            </div>
                            <a href="homepage.php" class="btn btn-primary">Return to Homepage</a>
                        </div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <h4>Error</h4>
                                <p><?= htmlspecialchars($error) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($alreadyApplied): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <h4>Already Applied</h4>
                                <p>You have already submitted an application for this job.</p>
                            </div>
                            <a href="homepage.php" class="btn btn-secondary">Return to Homepage</a>
                        </div>
                    <?php endif; ?>


                    <?php if (!$success && !$alreadyApplied): ?>
                        <div class="application-form-container">
                            <div class="application-section">
                                <div class="section-header">
                                    <h2><i class="fas fa-user"></i> Personal Information</h2>
                                </div>
                                <div class="section-content">
                                    <div class="info-row">
                                        <div class="info-label">Full Name:</div>
                                        <div class="info-value"><?= $full_name ?></div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-label">Email:</div>
                                        <div class="info-value"><?= $email ?></div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-label">Phone:</div>
                                        <div class="info-value"><?= $phone ?></div>
                                    </div>
                                </div>
                            </div>

                            <form action="" method="POST" enctype="multipart/form-data" class="application-form">
                                <div class="application-section">
                                    <div class="section-header">
                                        <h2><i class="fas fa-file-pdf"></i> Resume</h2>
                                    </div>
                                    <div class="section-content">
                                        <div class="form-group">
                                            <label for="resumeFiles">Upload Your Resume</label>
                                            <p class="form-hint">Accepted file formats: PDF, DOC, DOCX (Max size: 5MB)</p>
                                            <div class="file-upload-container">
                                                <input type="file" name="resume" id="resumeFiles" class="file-upload-input" accept=".pdf,.doc,.docx" required>
                                                <label for="resumeFiles" class="file-upload-label">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span class="file-name">Choose a file</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="application-section">
                                    <div class="section-header">
                                        <h2><i class="fas fa-envelope-open-text"></i> Cover Letter</h2>
                                    </div>
                                    <div class="section-content">
                                        <div class="form-group">
                                            <label for="cover_letter">Tell us why you're a good fit for this position</label>
                                            <textarea id="cover_letter" name="cover_letter" rows="8" placeholder="Write your cover letter here..."></textarea>
                                            <p class="form-hint">A well-written cover letter can help you stand out from other applicants.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <a href="homepage.php" class="btn btn-secondary">
                                        <!-- <a href="job-details.php?id=<?= $job_id ?>" class="btn btn-secondary"> -->
                                        <i class="fas fa-arrow-left"></i> Back to Job
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Submit Application
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File upload preview
            const fileInput = document.getElementById('resumeFiles');
            const fileLabel = document.querySelector('.file-name');

            if (fileInput && fileLabel) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        fileLabel.textContent = this.files[0].name;
                    } else {
                        fileLabel.textContent = 'Choose a file';
                    }
                });
            }

            // Success message handling with SweetAlert
            <?php if ($success): ?>
                Swal.fire({
                    title: 'Application Submitted!',
                    text: 'Your application has been successfully submitted.',
                    icon: 'success',
                    confirmButtonText: 'Return to Homepage',
                    confirmButtonColor: '#c41e3a'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'homepage.php';
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>