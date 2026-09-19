<?php
session_start();
require_once "../config/database.php";
require_once "../models/Inventory.php";

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'rdc_staff') {
    die("Access Denied");
}

$inventory = new Inventory($conn);

if (isset($_POST['set_stock'])) {

    $product_id = $_POST['product_id'];
    $rdc = $_POST['rdc_location'];
    $quantity = $_POST['quantity'];

    $inventory->setStock($product_id, $rdc, $quantity);

    header("Location: /ISDN/views/inventory/manage.php");
    exit();
}