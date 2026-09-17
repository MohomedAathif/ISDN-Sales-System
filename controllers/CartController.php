<?php

session_start();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];

    if(isset($_SESSION['cart'][$product_id])){
        $_SESSION['cart'][$product_id] += 1;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    header("Location: /ISDN/views/products/list.php");
    exit();
}