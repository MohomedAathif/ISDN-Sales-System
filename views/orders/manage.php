<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();
checkRole(['admin','logistics']);

$stmt = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h2 class="text-2xl font-bold mb-4">Manage Orders</h2>

<table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden text-center">
<tr class="bg-indigo-600 text-white">
    <th class="p-2">Order ID</th>
    <th class="p-2">User</th>
    <th class="p-2">Total</th>
    <th class="p-2">Status</th>
    <th class="p-2">Assigned Driver</th>
    <th class="p-2">Delivery Date</th>
    <th class="p-2">Payment</th>
    <th class="p-2">Action</th>
</tr>

<?php foreach ($orders as $order): ?>
<tr class="hover:bg-gray-50 transition">
    <td class="p-2"><?= $order['id'] ?></td>
    <td class="p-2"><?= $order['user_id'] ?></td>
    <td class="p-2">Rs <?= $order['total_amount'] ?></td>
    <td class="p-2"><?= $order['status'] ?></td>
    <td class="p-2">
        <div class="mb-2 text-sm font-semibold text-indigo-700">
            <?= $order['assigned_driver'] ?? 'Not Assigned' ?>
        </div>
        <form method="POST" action="/ISDN/controllers/OrderController.php" class="space-y-1">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

            <input type="text" name="driver"
                placeholder="Driver Name"
                value="<?= $order['assigned_driver'] ?? '' ?>"
                class="border p-1 rounded text-sm w-full">

            <input type="date" name="delivery_date"
                value="<?= $order['delivery_date'] ?? '' ?>"
                class="border p-1 rounded text-sm w-full">

            <button type="submit"
                    name="assign_delivery"
                    class="bg-indigo-600 text-white px-2 py-1 rounded text-sm w-full">
                Assign
            </button>
        </form>
    </td>
    <td class="p-2"><?= $order['delivery_date'] ?? '-' ?></td>
    <td class="p-2">
        <?php if ($order['payment_status'] === 'paid'): ?>
            <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-xs">
                Paid
            </span>
        <?php else: ?>
            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">
                Unpaid
            </span>
        <?php endif; ?>
    </td>

<td class="p-2">
    <td class="p-2">
        <form method="POST" action="/ISDN/controllers/OrderStatusController.php">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="status" class="border p-1">
                <option value="processing" <?= $order['status']=='processing'?'selected':'' ?>>Processing</option>
                <option value="shipped" <?= $order['status']=='shipped'?'selected':'' ?>>Shipped</option>
                <option value="delivered" <?= $order['status']=='delivered'?'selected':'' ?>>Delivered</option>
            </select>
            <button type="submit" name="update_status"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg shadow transition duration-200">
                Update
            </button>
        </form>
        <?php if ($_SESSION['role'] === 'admin' && $order['payment_status'] === 'unpaid'): ?>
        <form method="POST" action="/ISDN/controllers/OrderController.php" class="mt-2">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <button type="submit"
                    name="mark_paid"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded">
                Mark as Paid
            </button>
        </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

<?php include "../layouts/footer.php"; ?>