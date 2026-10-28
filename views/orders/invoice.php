<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();

if (!isset($_GET['id'])) {
    die("Invalid Order");
}

$order_id = $_GET['id'];

// Get order
$stmt = $conn->prepare("
    SELECT * FROM orders
    WHERE id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found");
}

// Only owner or admin can view invoice
if ($_SESSION['role'] !== 'admin' && $order['user_id'] != $_SESSION['user_id']) {
    die("Access Denied");
}

// Get order items
$stmt = $conn->prepare("
    SELECT order_items.*, products.name
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../layouts/header.php"; ?>

<div class="p-8 max-w-4xl mx-auto bg-white shadow-xl rounded-lg">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-indigo-700">Invoice</h1>
        <button onclick="window.print()"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            Print Invoice
        </button>
    </div>

    <div class="mb-4">
        <p><strong>Invoice ID:</strong> #<?= $order['id'] ?></p>
        <p><strong>Date:</strong> <?= $order['created_at'] ?></p>
        <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
        <p><strong>Payment:</strong> <?= ucfirst($order['payment_status']) ?></p>
        <p><strong>Estimated Delivery:</strong> <?= $order['estimated_delivery'] ?></p>
    </div>

    <table class="w-full border text-center mt-6">
        <tr class="bg-indigo-600 text-white">
            <th class="p-2">Product</th>
            <th class="p-2">Quantity</th>
            <th class="p-2">Price</th>
            <th class="p-2">Discount</th>
            <th class="p-2">Subtotal</th>
        </tr>

        <?php 
        $originalAmount = $order['original_amount'];
        $discountAmount = $order['discount_amount'];
        ; ?>

        <?php foreach ($items as $item): ?>
        
            <?php
            $itemOriginal = $item['price'] * $item['quantity'];

            // Proportional discount calculation
            $itemDiscount = 0;

            if ($originalAmount > 0 && $discountAmount > 0) {
                $itemDiscount = ($itemOriginal / $originalAmount) * $discountAmount;
            }

            $itemFinal = $itemOriginal - $itemDiscount;
            ?>
                <tr>
                    <td class="p-3 border-b"><?= $item['name'] ?></td>
                    <td class="p-3 border-b"><?= $item['quantity'] ?></td>
                    <td class="p-3 border-b">Rs <?= number_format($item['price'],2) ?></td>
                    <td class="p-3 border-b text-red-600">
                        - Rs <?= number_format($itemDiscount,2) ?>
                    </td>
                    <td class="p-3 border-b font-semibold">
                        Rs <?= number_format($itemFinal,2) ?>
                    </td>
                </tr>
        <?php endforeach; ?>
    </table>

    <div class="text-right mt-6 space-y-2">

        <div class="text-2xl font-bold text-indigo-700 border-t pt-2 mt-2">
            Total: Rs <?= number_format($order['total_amount'],2) ?>
        </div>
    </div>

</div>

<?php include "../layouts/footer.php"; ?>