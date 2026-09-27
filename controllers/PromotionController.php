<?php
session_start();
require_once "../config/database.php";

if (isset($_POST['create_promotion'])) {

    $stmt = $conn->prepare("
        INSERT INTO promotions (title, discount_percent, start_date, end_date)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['title'],
        $_POST['discount'],
        $_POST['start_date'],
        $_POST['end_date']
    ]);

    header("Location: /ISDN/views/admin/promotions.php");
    exit();
}

if(isset($_POST['delete_promotion'])){

    $id = $_POST['promotion_id'];

    $stmt = $conn->prepare("DELETE FROM promotions WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: /ISDN/views/admin/promotions.php");
    exit();
}