<?php
session_start();
require_once "../config/database.php";

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'logistics') {
    die("Access Denied");
}

if (isset($_POST['update_status'])) {

    $stmt = $conn->prepare(
        "UPDATE orders SET status = ? WHERE id = ?"
    );

    $stmt->execute([
        $_POST['status'],
        $_POST['order_id']
    ]);

    header("Location: /ISDN/views/orders/manage.php");
    exit();
}