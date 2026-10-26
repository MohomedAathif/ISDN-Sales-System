<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";
require_once "../../models/Product.php";

checkAuth();

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    echo "<p class='p-6 text-red-600'>Your cart is empty.</p>";
    exit;
}

$ids = implode(",", array_keys($cart));

$stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h2 class="text-2xl font-bold mb-4">Place Order</h2>

<form method="GET" action="/ISDN/views/orders/checkout.php">

    <?php foreach ($products as $p): ?>
        <div class="border p-4 mb-4">

            <p class="font-bold"><?= $p['name'] ?> - Rs <?= $p['price'] ?></p>

            <input type="hidden" name="product_id[]" value="<?= $p['id'] ?>">
            <input type="hidden" name="price[]" value="<?= $p['price'] ?>">

            <input type="number"
            name="quantity[<?= $p['id'] ?>]"
            value="<?= $cart[$p['id']] ?>"
            min="1"
            class="p-2 border mt-2">

            <a href="/ISDN/controllers/remove_cart.php?id=<?= $p['id'] ?>"
            class="text-red-600 ml-4">
            Remove
            </a>

        </div>
    <?php endforeach; ?>

    <button type="submit" name="place_order"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg shadow transition duration-200">
        Proceed to Checkout
    </button>
</form>
</div>

<?php include "../layouts/footer.php"; ?>