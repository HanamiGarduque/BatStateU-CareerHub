<?php
session_start();
require_once __DIR__ . '/../Database/db_connections.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$database = new Database();
$conn = $database->getConnect();

$input = json_decode(file_get_contents('php://input'), true);
$saved_jobs_id = $input['saved_job_id'] ?? null;
$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("DELETE FROM saved_jobs 
                          WHERE saved_jobs_id = :saved_jobs_id 
                          AND user_id = :user_id");
    $stmt->bindParam(':saved_jobs_id', $saved_jobs_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    
    echo json_encode(['success' => $success]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>