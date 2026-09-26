<?php
session_start();
require_once "../config/database.php";
require_once "../models/Product.php";

$product = new Product($conn);

if (isset($_POST['add_product'])) {

    if ($_SESSION['role'] !== 'admin') {
        die("Access Denied");
    }

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $product->create($name, $description, $price, $category);

    header("Location: ../views/products/list.php");
    exit();
}