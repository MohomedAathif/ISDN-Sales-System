<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id']) ||
   ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'rdc_staff')) {
    die("Access Denied");
}

if (isset($_POST['transfer_stock'])) {

    $product_id = $_POST['product_id'];
    $from = $_POST['from_rdc'];
    $to = $_POST['to_rdc'];
    $qty = $_POST['quantity'];

    try {

        $conn->beginTransaction();

        // Check source stock
        $stmt = $conn->prepare("
            SELECT quantity FROM inventory
            WHERE product_id = ? AND rdc_location = ?
        ");
        $stmt->execute([$product_id, $from]);
        $stock = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$stock || $stock['quantity'] < $qty) {
            throw new Exception("Insufficient stock in source RDC.");
        }

        // Deduct from source
        $stmt = $conn->prepare("
            UPDATE inventory
            SET quantity = quantity - ?
            WHERE product_id = ? AND rdc_location = ?
        ");
        $stmt->execute([$qty, $product_id, $from]);

        // Add to destination
        $stmt = $conn->prepare("
            INSERT INTO inventory (product_id, rdc_location, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?
        ");
        $stmt->execute([$product_id, $to, $qty, $qty]);

        // Record transfer
        $stmt = $conn->prepare("
            INSERT INTO stock_transfers (product_id, from_rdc, to_rdc, quantity)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$product_id, $from, $to, $qty]);

        $conn->commit();

        header("Location: /ISDN/views/inventory/manage.php");
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        die($e->getMessage());
    }
}