<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();
checkRole(['logistics']);

$stmt = $conn->query("SELECT COUNT(*) as pending FROM orders WHERE status='processing'");
$pending = $stmt->fetch(PDO::FETCH_ASSOC)['pending'];
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h1 class="text-2xl font-bold mb-4">Logistics Dashboard</h1>

<div class="bg-yellow-500 text-white p-4 rounded mb-4">
    <h2>Pending Deliveries</h2>
    <p class="text-2xl font-bold"><?= $pending ?></p>
</div>

<a href="/ISDN/views/orders/manage.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow transition duration-200">
    Manage Deliveries
</a>

</div>

<?php include "../layouts/footer.php"; ?>