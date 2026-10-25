<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();

if(empty($_SESSION['cart'])){
    echo "Cart is empty";
    exit;
}

$cart = $_SESSION['cart'];

$ids = implode(",", array_keys($cart));

$stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php include "../layouts/header.php"; ?>

<div class="max-w-4xl mx-auto p-6">

    <h2 class="text-2xl font-bold mb-6">Checkout</h2>
    <form method="POST" action="/ISDN/controllers/OrderController.php">

    <?php foreach($products as $p): ?>
        <input type="hidden" name="product_id[]" value="<?= $p['id'] ?>">
        <input type="hidden" name="price[]" value="<?= $p['price'] ?>">
        <input type="hidden" name="quantity[]" value="<?= $cart[$p['id']] ?>">
    <?php endforeach; ?>

    <h3 class="font-semibold mb-3">Select Payment Method</h3>
    <label class="block mb-2">
        <input type="radio" name="payment_method" value="cod" onclick="showCOD()">
        Cash on Delivery
    </label>
    <label class="block mb-4">
        <input type="radio" name="payment_method" value="card" onclick="showCard()">
        Online Card Payment
    </label>

    <div id="cardForm" class="hidden border p-4 rounded mb-4">

        <h4 class="font-semibold mb-3">Card Details</h4>

        <input type="text" name="card_number" placeholder="Card Number"
        class="border p-2 w-full mb-3">

        <input type="text" name="card_name" placeholder="Card Holder Name"
        class="border p-2 w-full mb-3">

        <div class="flex gap-2">
            <input type="text" name="expiry" placeholder="MM/YY"
            class="border p-2 w-1/2">

            <input type="text" name="cvv" placeholder="CVV"
            class="border p-2 w-1/2">
        </div>
    </div>

    <div id="submitButton" class="hidden">
        <button type="submit" name="place_order"
            class="bg-emerald-600 text-white px-6 py-2 rounded mt-4">
            Submit Order
        </button>
    </div>
    </form>
</div>

<script>

function showCOD(){
document.getElementById("cardForm").classList.add("hidden");
document.getElementById("submitButton").classList.remove("hidden");
}

function showCard(){
document.getElementById("cardForm").classList.remove("hidden");
document.getElementById("submitButton").classList.remove("hidden");
}

</script>

<?php include "../layouts/footer.php"; ?>