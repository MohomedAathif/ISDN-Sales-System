<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();
checkRole(['rdc_staff']);

$stmt = $conn->query("SELECT COUNT(*) as low FROM inventory WHERE quantity < 10");
$lowStock = $stmt->fetch(PDO::FETCH_ASSOC)['low'];
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h1 class="text-2xl font-bold mb-4">RDC Staff Dashboard</h1>

<div class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow transition duration-200">
    <h2>Low Stock Items</h2>
    <p class="text-2xl font-bold"><?= $lowStock ?></p>
</div>
</br>
<a href="/ISDN/views/inventory/manage.php" class="bg-purple-600 text-white px-4 py-2 rounded">
    Manage Inventory
</a>

</div>

<?php include "../layouts/footer.php"; ?>