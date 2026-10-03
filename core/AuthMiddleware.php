<?php

// ALWAYS start session immediately
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-login using remember cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {

    require_once __DIR__ . "/../config/database.php";

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_COOKIE['remember_user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
    }
}

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /ISDN/views/auth/login.php");
        exit();
    }
}

function checkRole($allowedRoles = []) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: /ISDN/views/errors/403.php");
        exit();
    }
}