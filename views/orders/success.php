<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();

if (!isset($_GET['id'])) {
    die("Invalid Order");
}

$order_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found");
}
?>

<?php include "../layouts/header.php"; ?>

<div class="p-8 max-w-3xl mx-auto bg-white shadow-xl rounded-lg text-center">

    <div class="text-emerald-600 text-5xl mb-4">✔</div>

    <h1 class="text-3xl font-bold text-indigo-700 mb-4">
        Order Placed Successfully!
    </h1>

    <div id="invoicePopup"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <h3 class="text-lg font-bold mb-2">Invoice Sent</h3>
                <p class="text-gray-600 mb-4">
                    Your order was placed successfully.
                    The invoice has been sent to your email.
                </p>
            <button onclick="closePopup()"
                class="bg-emerald-600 text-white px-4 py-2 rounded">
                    OK
            </button>
        </div>
    </div>
        <script>
            function closePopup(){
            document.getElementById("invoicePopup").style.display = "none";
            }
        </script>

    <p class="text-lg mb-2">
        Your Order ID is:
        <span class="font-bold text-indigo-600">#<?= $order['id'] ?></span>
    </p>

    <p class="mb-2">
        Estimated Delivery:
        <strong><?= $order['estimated_delivery'] ?></strong>
    </p>

    <p class="mb-6">
        Final Amount:
        <strong>Rs<?= number_format($order['total_amount'],2) ?></strong>
    </p>

    <div class="flex justify-center gap-4">

        <a href="/ISDN/views/orders/invoice.php?id=<?= $order['id'] ?>"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow">
            View Invoice
        </a>

        <a href="/ISDN/views/products/list.php"
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg shadow">
            Continue Shopping
        </a>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>