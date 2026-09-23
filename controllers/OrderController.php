<?php
session_start();
require_once "../config/database.php";
require_once "../models/Order.php";

if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}

$orderModel = new Order($conn);


//PLACE ORDER

if (isset($_POST['place_order'])) {

    $items = [];

    foreach ($_POST['product_id'] as $index => $product_id) {

        $items[] = [
            'product_id' => $product_id,
            'quantity' => $_POST['quantity'][$index],
            'price' => $_POST['price'][$index]
        ];
    }

    /* Get payment method */
    $payment_method = $_POST['payment_method'];

    if ($payment_method === "cod") {

        $payment_status = "unpaid";

    } else {

        $card_number = $_POST['card_number'] ?? '';
        $card_name   = $_POST['card_name'] ?? '';
        $expiry      = $_POST['expiry'] ?? '';
        $cvv         = $_POST['cvv'] ?? '';

        if ($card_number == "" || $card_name == "" || $expiry == "" || $cvv == "") {
            die("Card details are required");
        }

        $payment_status = "paid";
    }

    $order_id = $orderModel->createOrder($_SESSION['user_id'], $items);

    if ($order_id) {

        $stmt = $conn->prepare("
            UPDATE orders
            SET payment_method = ?, payment_status = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $payment_method,
            $payment_status,
            $order_id
        ]);

        unset($_SESSION['cart']);

        header("Location: /ISDN/views/orders/success.php?id=" . $order_id);
        exit();

    } else {

        header("Location: /ISDN/views/orders/create.php?error=1");
        exit();
    }
}



//ASSIGN DELIVERY

if (isset($_POST['assign_delivery'])) {

    $stmt = $conn->prepare("
        UPDATE orders
        SET assigned_driver = ?, 
            delivery_date = ?, 
            status = 'processing'
        WHERE id = ?
    ");

    $stmt->execute([
        $_POST['driver'],
        $_POST['delivery_date'],
        $_POST['order_id']
    ]);

    header("Location: /ISDN/views/orders/manage.php");
    exit();
}


if (isset($_POST['mark_paid'])) {

    $stmt = $conn->prepare("
        UPDATE orders
        SET payment_status = 'paid'
        WHERE id = ?
    ");

    $stmt->execute([$_POST['order_id']]);

    header("Location: /ISDN/views/orders/manage.php");
    exit();
}