<?php
session_start();
require 'db.php'; // include your DB connection

// OPTIONAL: Debugging session (uncomment if needed)
// error_log("Session ID: " . session_id());
// error_log("Session contents: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone_number'] ?? '';
$address = $_POST['address'] ?? '';
$about_me = $_POST['about_me'] ?? ''; // optional, may go to another table if needed

// Update query
$sql = "UPDATE users SET first_name=?, last_name=?, email=?, phone_number=?, address=? WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $address, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Information updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed"]);
}
?>