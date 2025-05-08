<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
function isEmployer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'employer';
}
function isJobseeker() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'jobseeker';
}

if (!isLoggedIn()) {
    header("Location: /ADMSSYSTEM/login.php");
    exit();
}
