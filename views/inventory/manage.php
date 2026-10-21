<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";
require_once "../../models/Product.php";
require_once "../../models/Inventory.php";

checkAuth();
checkRole(['admin','rdc_staff']);

$productModel = new Product($conn);
$inventoryModel = new Inventory($conn);

$products = $productModel->getAll();
$inventory = $inventoryModel->getAll();
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">

<h2 class="text-2xl font-bold mb-4">Manage Inventory</h2>

<form method="POST" action="/ISDN/controllers/InventoryController.php" class="mb-6">
    <select name="product_id" required class="p-2 border">
        <option value="">Select Product</option>
        <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>">
                <?= $p['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="rdc_location" placeholder="RDC Location" required class="p-2 border">
    <input type="number" name="quantity" placeholder="Quantity" required class="p-2 border">

    <button type="submit" name="set_stock" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg shadow transition duration-200">
        Save Stock
    </button>
</form>

<h3 class="text-xl font-bold mb-2">Current Inventory</h3>

<table class="w-full border text-center">
    <tr class="bg-gray-200">
        <th class="p-2">Product</th>
        <th class="p-2">RDC</th>
        <th class="p-2">Quantity</th>
    </tr>

    <?php foreach ($inventory as $item): ?>
    <tr>
        <td class="p-2"><?= $item['name'] ?></td>
        <td class="p-2"><?= $item['rdc_location'] ?></td>
        <td class="p-2"><?= $item['quantity'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</div>

<?php include "../layouts/footer.php"; ?>