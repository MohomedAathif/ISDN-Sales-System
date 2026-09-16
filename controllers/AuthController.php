<?php
session_start();
require_once "../config/database.php";

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if($check->rowCount() > 0){
        header("Location: /ISDN/views/auth/register.php?exists=1");
        exit();
    }

    $stmt = $conn->prepare("
        INSERT INTO users (name,email,password,role)
        VALUES (?,?,?,?)
    ");

    $stmt->execute([
        $name,
        $email,
        $password,
        'customer'
    ]);

    header("Location: /ISDN/views/auth/login.php?registered=1");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Remember Me logic
        if (isset($_POST['remember'])) {
            setcookie(
                "remember_user",
                $user['id'],
                time() + (86400 * 7),
                "/"
            );
        }

        if ($user['role'] === 'admin' || $user['role'] === 'manager') {
            header("Location: /ISDN/views/dashboard/index.php");
        } elseif ($user['role'] === 'customer') {
            header("Location: /ISDN/views/dashboard/customer.php");
        } elseif ($user['role'] === 'logistics') {
            header("Location: /ISDN/views/dashboard/logistics.php");
        } elseif ($user['role'] === 'rdc_staff') {
            header("Location: /ISDN/views/dashboard/rdc.php");
        } else {
            header("Location: /ISDN/views/home.php");
        }

        exit();

    } else {
        header("Location: /ISDN/views/auth/login.php?error=1");
        exit();
    }
}