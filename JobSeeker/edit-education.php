<?php
session_start();

// Check if user is logged in (using user_id from session set in login.php)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Database connection using PDO for consistency
try {
    $host = "localhost";
    $db = "sheree";
    $user = "root";
    $pass = "";

    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the education record to edit
    if (isset($_POST['id'])) {
        $eduId = $_POST['id'];

        $stmt = $conn->prepare("SELECT * FROM education WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $eduId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $education = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if education record exists
        if (!$education) {
            echo "Education record not found.";
            exit();
        }
    } else {
        echo "Invalid request.";
        exit();
    }

    // Update education record after form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $degree = $_POST['degree'];
        $institution = $_POST['institution'];
        $startYear = $_POST['start_year'];
        $endYear = $_POST['end_year'];
        $description = $_POST['description'];

        // Update the education record in the database
        $updateStmt = $conn->prepare("UPDATE education SET degree = :degree, institution = :institution, start_date = :start_date, end_date = :end_date, description = :description WHERE id = :id AND user_id = :user_id");
        $updateStmt->bindParam(':degree', $degree);
        $updateStmt->bindParam(':institution', $institution);
        $updateStmt->bindParam(':start_date', $startYear);
        $updateStmt->bindParam(':end_date', $endYear);
        $updateStmt->bindParam(':description', $description);
        $updateStmt->bindParam(':id', $eduId);
        $updateStmt->bindParam(':user_id', $userId);
        $updateStmt->execute();

        // Redirect back to the profile page with a success message
        header("Location: profile.php?update=education_success");
        exit();
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Education - BatStateU Career Hub</title>
    <link rel="stylesheet" href="profile.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="dashboard-container">
        <main class="main-content">
            <div class="dashboard-content">
                <h1>Edit Education</h1>

                <form method="POST" action="edit-education.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($education['id']); ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label>Degree or Program</label>
                            <input type="text" name="degree" value="<?php echo htmlspecialchars($education['degree']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Institution</label>
                            <input type="text" name="institution" value="<?php echo htmlspecialchars($education['institution']); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Start Year</label>
                            <input type="text" name="start_year" value="<?php echo htmlspecialchars($education['start_date']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>End Year</label>
                            <input type="text" name="end_year" value="<?php echo htmlspecialchars($education['end_date']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4" required><?php echo htmlspecialchars($education['description']); ?></textarea>
                    </div>

                    <button type="submit" name="update" class="save-btn">Update Education</button>
                </form>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // If the update was successful, show a success message
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('update') === 'education_success') {
                Swal.fire({
                    title: 'Success!',
                    text: 'Your education record has been updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
</body>
</html>
