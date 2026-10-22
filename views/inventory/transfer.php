<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";
require_once "../../models/Product.php";
require_once "../../models/Inventory.php";

checkAuth();
checkRole(['admin','rdc_staff']);

$productModel = new Product($conn);
$products = $productModel->getAll();

$inventoryModel = new Inventory($conn);
$stocks = $inventoryModel->getAll();
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h2 class="text-2xl font-bold mb-4">Inter-Branch Stock Transfer</h2>

<form method="POST" action="/ISDN/controllers/TransferController.php">

    <select name="product_id" required class="p-2 border">
        <option value="">Select Product</option>
        <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>">
                <?= $p['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="from_rdc" placeholder="From RDC" required class="p-2 border">
    <input type="text" name="to_rdc" placeholder="To RDC" required class="p-2 border">
    <input type="number" name="quantity" placeholder="Quantity" required class="p-2 border">

    <button type="submit" name="transfer_stock"
            class="bg-purple-600 text-white px-4 py-2 rounded">
        Transfer
    </button>
</form>

<h3 class="text-xl font-bold mt-8 mb-4">Current Stock in RDCs</h3>

<table class="min-w-full bg-white shadow-md rounded-lg text-center">

<tr class="bg-purple-600 text-white">
    <th class="p-2">Product</th>
    <th class="p-2">RDC Location</th>
    <th class="p-2">Available Quantity</th>
</tr>

<?php foreach ($stocks as $stock): ?>

<tr class="border-b hover:bg-gray-100">
    <td class="p-2"><?= htmlspecialchars($stock['name']) ?></td>
    <td class="p-2"><?= htmlspecialchars($stock['rdc_location']) ?></td>
    <?php if ($stock['quantity'] <= 5): ?>
    <td class="p-2 text-red-600 font-bold">
        <?= $stock['quantity'] ?> (Low Stock)
    </td>

    <?php else: ?>

    <td class="p-2 text-green-600 font-semibold">
        <?= $stock['quantity'] ?>
    </td>

    <?php endif; ?>
</tr>

<?php endforeach; ?>

</table>

</div>

<?php include "../layouts/footer.php"; ?>