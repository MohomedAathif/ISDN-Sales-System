<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";
require_once "../../models/Order.php";

checkAuth();

$orderModel = new Order($conn);
$orders = $orderModel->getUserOrders($_SESSION['user_id']);
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h2 class="text-2xl font-bold mb-4">My Orders</h2>

<table class="w-full border text-center">
<tr class="bg-indigo-600 text-white">
    <th class="p-3">Order ID</th>
    <th class="p-3">Amount</th>
    <th class="p-3">Payment</th>
    <th class="p-3">Status</th>
    <th class="p-3">Delivery</th>
    <th class="p-3">Date</th>
    <th class="p-3">Invoice</th>
</tr>

<?php foreach ($orders as $order): ?>
<tr class="hover:bg-gray-50 transition">

    <td class="p-3 border-b"><?= $order['id'] ?></td>

    <td class="p-3 border-b">
        <?php if ($order['discount_amount'] > 0): ?>
            <div class="text-sm text-gray-500">
                Original: Rs <?= number_format($order['original_amount'],2) ?>
            </div>
            <div class="font-bold text-emerald-700">
                Final: Rs <?= number_format($order['total_amount'],2) ?>
            </div>
        <?php else: ?>
            Rs <?= number_format($order['total_amount'],2) ?>
        <?php endif; ?>
    </td>

    <td class="p-3 border-b">
        <?php if ($order['payment_status'] === 'paid'): ?>
            <span class="bg-emerald-600 text-white px-3 py-1 rounded-full text-xs">
                Paid
            </span>
        <?php else: ?>
            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">
                Unpaid
            </span>
        <?php endif; ?>
    </td>

    <td class="p-3 border-b">
        <?php
        $status = $order['status'];

        $colors = [
            'pending' => 'bg-gray-500',
            'processing' => 'bg-yellow-500',
            'shipped' => 'bg-blue-500',
            'delivered' => 'bg-emerald-600'
        ];

        $color = $colors[$status] ?? 'bg-gray-500';
        ?>

        <span class="<?= $color ?> text-white px-3 py-1 rounded-full text-xs">
            <?= ucfirst(str_replace('_',' ', $status)) ?>
        </span>
    </td>

    <td class="p-3 border-b">
        <?= $order['estimated_delivery'] ?? '-' ?>
    </td>

    <td class="p-3 border-b">
        <?= $order['created_at'] ?>
    </td>

    <td class="p-3 border-b">
        <a href="/ISDN/views/orders/invoice.php?id=<?= $order['id'] ?>"
        class="bg-indigo-600 text-white px-3 py-1 rounded text-sm">
            View
        </a>
    </td>

</tr>
<?php endforeach; ?>

</table>

</div>

<?php include "../layouts/footer.php"; ?>