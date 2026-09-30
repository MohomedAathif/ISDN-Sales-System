<?php
session_start();

$id = $_GET['id'];

unset($_SESSION['cart'][$id]);

header("Location: /ISDN/views/orders/create.php");
exit();