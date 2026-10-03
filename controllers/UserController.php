<?php
session_start();
require_once "../config/database.php";

// Only admin can manage users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}


// UPDATE USER ROLE

if (isset($_POST['update_role'])) {

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([
        $_POST['role'],
        $_POST['user_id']
    ]);

    header("Location: /ISDN/views/admin/users.php");
    exit();
}


// DELETE USER

if (isset($_POST['delete_user'])) {

    // Prevent admin from deleting themselves
    if ($_POST['user_id'] == $_SESSION['user_id']) {
        die("You cannot delete your own account.");
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['user_id']]);

    header("Location: /ISDN/views/admin/users.php");
    exit();
}