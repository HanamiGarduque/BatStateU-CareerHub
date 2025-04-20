<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'batstateu_career_hub';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

class JobApplication {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function createApplication($name, $email, $position, $cover_letter, $resume_path) {
        try {
            $stmt = $this->conn->prepare("CALL CreateApplication(:name, :email, :position, :cover_letter, :resume_path)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':position' => $position,
                ':cover_letter' => $cover_letter,
                ':resume_path' => $resume_path
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}

$success = false;
$error = '';
$uploadDir = 'uploads/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $resumeName = basename($_FILES['resume']['name']);
    $resumePath = $uploadDir . uniqid() . '_' . $resumeName;
    
    if ($_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
        $error = "File upload error: " . $_FILES['resume']['error'];
    } elseif ($_FILES['resume']['type'] !== 'application/pdf') {
        $error = "Only PDF files are allowed";
    } elseif (move_uploaded_file($_FILES['resume']['tmp_name'], $resumePath)) {
        $application = new JobApplication();
        if ($application->createApplication(
            $_POST['name'],
            $_POST['email'],
            $_POST['position'],
            $_POST['cover_letter'],
            $resumePath
        )) {
            $success = true;
        } else {
            $error = "Failed to save application to database";
            unlink($resumePath); // Remove uploaded file if DB save failed
        }
    } else {
        $error = "File upload failed";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Application Form</title>
    <style>
        body { margin: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], 
        input[type="email"], 
        textarea, 
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .success { color: green; padding: 10px; margin-bottom: 15px; border: 1px solid green; }
        .error { color: red; padding: 10px; margin-bottom: 15px; border: 1px solid red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Job Application Form</h2>
        
        <?php if ($success): ?>
            <div class="success">
                Application submitted successfully!<br>
                Name: <?= htmlspecialchars($_POST['name']) ?><br>
                Email: <?= htmlspecialchars($_POST['email']) ?><br>
                Position: <?= htmlspecialchars($_POST['position']) ?>
            </div>
        <?php elseif ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" required>
            </div>

            <div class="form-group">
                <label for="resume">Resume (PDF only):</label>
                <input type="file" id="resume" name="resume" accept=".pdf" required>
            </div>

            <div class="form-group">
                <label for="cover_letter">Cover Letter:</label>
                <textarea id="cover_letter" name="cover_letter" rows="5"></textarea>
            </div>

            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>
</html>