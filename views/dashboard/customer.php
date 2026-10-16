<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();
checkRole(['customer']);

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h1 class="text-2xl font-bold mb-4">Customer Dashboard</h1>

<?php
$stmt = $conn->prepare("
    SELECT * FROM promotions
    WHERE CURDATE() BETWEEN start_date AND end_date
");
$stmt->execute();
$activePromos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if ($activePromos): ?>
<div class="bg-emerald-500 text-white p-4 rounded mb-4">
    <h2 class="font-bold mb-2">Active Promotions</h2>
    <?php foreach ($activePromos as $promo): ?>
        <p><?= $promo['title'] ?> - <?= $promo['discount_percent'] ?>% OFF</p>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="bg-blue-500 text-white p-4 rounded mb-4">
    <h2>Total Orders</h2>
    <p class="text-2xl font-bold"><?= $totalOrders ?></p>
</div>

</div>

<?php include "../layouts/footer.php"; ?>