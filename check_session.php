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
    header("Location:  /PHPSYSTEM/login.php");
    exit();
}

function ensureAdminAccess() {
    if (isAdmin()) { 
        header('Location: /PHPSYSTEM/homepage.php');
        exit();
    }
}

function ensureEmployerAccess() {
    if (isEmployer()) { 
        header('Location: /PHPSYSTEM/homepage.php');
        exit();
    }
}

function ensureJobseeker() {
    if (isJobseeker()) { 
        header('Location: /PHPSYSTEM/homepage.php');
        exit();
    }
}
?>
