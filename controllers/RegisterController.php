<?php
require_once "../config/database.php";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users (name, email, password, role)
        VALUES (?, ?, ?, 'customer')
    ");

    $stmt->execute([$name, $email, $password]);

    header("Location: /ISDN/views/auth/login.php");
    exit();
}