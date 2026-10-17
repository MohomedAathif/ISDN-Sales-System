<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();

// Restrict dashboard to admin and manager
checkRole(['admin','manager']);


// Total Sales
$stmt = $conn->query("SELECT SUM(total_amount) as total_sales FROM orders");
$totalSales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales'] ?? 0;

// Total Orders
$stmt = $conn->query("SELECT COUNT(*) as total_orders FROM orders");
$totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

//Delivery KPI
$stmt = $conn->query("SELECT COUNT(*) as delivered FROM orders WHERE status='delivered'");
$deliveredOrders = $stmt->fetch(PDO::FETCH_ASSOC)['delivered'];

$deliveryRate = 0;
if ($totalOrders > 0) {
    $deliveryRate = ($deliveredOrders / $totalOrders) * 100;
}

// Total Products
$stmt = $conn->query("SELECT COUNT(*) as total_products FROM products");
$totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

// Low Stock (less than 10)
$stmt = $conn->query("
    SELECT products.name, inventory.quantity
    FROM inventory
    JOIN products ON inventory.product_id = products.id
    WHERE inventory.quantity < 10
");
$lowStock = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recent Orders
$stmt = $conn->query("
    SELECT * FROM orders
    ORDER BY created_at DESC
    LIMIT 5
");
$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Monthly Revenue
$stmt = $conn->query("
    SELECT SUM(total_amount) as monthly_sales
    FROM orders
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
    AND YEAR(created_at) = YEAR(CURRENT_DATE())
");
$monthlySales = $stmt->fetch(PDO::FETCH_ASSOC)['monthly_sales'] ?? 0;

// Paid Revenue
$stmt = $conn->query("
    SELECT SUM(total_amount) as paid_sales
    FROM orders
    WHERE payment_status = 'paid'
");
$paidSales = $stmt->fetch(PDO::FETCH_ASSOC)['paid_sales'] ?? 0;

// Unpaid Revenue
$stmt = $conn->query("
    SELECT SUM(total_amount) as unpaid_sales
    FROM orders
    WHERE payment_status = 'unpaid'
");
$unpaidSales = $stmt->fetch(PDO::FETCH_ASSOC)['unpaid_sales'] ?? 0;
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">

<h1 class="text-3xl font-bold mb-6">Management Dashboard</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6 mb-6">
    
    <div class="bg-white shadow-xl rounded-xl p-6 border-l-8 border-indigo-600">
        <h2 class="text-gray-600 font-semibold">Total Sales</h2>
        <p class="text-3xl font-bold text-indigo-700 mt-2">Rs <?= $totalSales ?></p>
    </div>

    <div class="bg-white shadow-xl rounded-xl p-6 border-l-8 border-emerald-600">
        <h2 class="text-gray-600 font-semibold">Total Orders</h2>
        <p class="text-2xl font-bold text-emerald-700 mt-2"><?= $totalOrders ?></p>
    </div>

    <div class="bg-white shadow-xl rounded-xl p-6 border-l-8 border-purple-600">
        <h2 class="text-gray-600 font-semibold">Total Products</h2>
        <p class="text-2xl font-bold text-purple-700 mt-2"><?= $totalProducts ?></p>
    </div>

    <div class="bg-white shadow-xl rounded-xl p-6 border-l-8 border-red-600">
        <h2 class="text-gray-600 font-semibold">Low Stock Items</h2>
        <p class="text-2xl font-bold text-red-700 mt-2"><?= count($lowStock) ?></p>
    </div>

    <div class="bg-white shadow-xl rounded-xl p-6 border-l-8 border-orange-600">
        <h2 class="text-gray-600 font-semibold">Delivery Success Rate</h2>
        <p class="text-2xl font-bold text-blue-700 mt-2">
            <?= number_format($deliveryRate,1) ?>%
        </p>
    </div>

    <div class="bg-white shadow-xl rounded-xl p-6 border-l-8 border-yellow-600">
        <h2 class="text-gray-600 font-semibold">Monthly Revenue</h2>
        <p class="text-2xl font-bold text-purple-700 mt-2">
            Rs <?= number_format($monthlySales,2) ?>
        </p>
    </div>

    <div class="bg-white shadow-xl rounded-xl p-6 border-l-8 border-gray-600">
        <h2 class="text-gray-600 font-semibold">Paid Revenue</h2>
        <p class="text-2xl font-bold text-emerald-700 mt-2">
            Rs <?= number_format($paidSales,2) ?>
        </p>
    </div>

    <div class="bg-white shadow-xl rounded-xl p-6 border-l-8 border-pink-600">
        <h2 class="text-gray-600 font-semibold">Outstanding Revenue</h2>
        <p class="text-2xl font-bold text-red-700 mt-2">
            Rs <?= number_format($unpaidSales,2) ?>
        </p>
    </div>
</div>


<h2 class="text-xl font-bold mb-2">Low Stock Details</h2>
<table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden text-center">
<tr class="bg-indigo-600 text-white">
    <th class="p-2">Product</th>
    <th class="p-2">Quantity</th>
</tr>
<?php foreach ($lowStock as $item): ?>
<tr>
    <td class="p-3 border-b border-gray-200"><?= $item['name'] ?></td>
    <td class="p-3 border-b border-gray-200"><?= $item['quantity'] ?></td>
</tr>
<?php endforeach; ?>
</table>


<h2 class="text-xl font-bold mb-2">Recent Orders</h2>
<table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden text-center">
<tr class="bg-indigo-600 text-white">
    <th class="p-2">Order ID</th>
    <th class="p-2">Total</th>
    <th class="p-2">Status</th>
    <th class="p-2">Date</th>
</tr>
<?php foreach ($recentOrders as $order): ?>
<tr>
    <td class="p-3 border-b border-gray-200"><?= $order['id'] ?></td>
    <td class="p-3 border-b border-gray-200">Rs <?= $order['total_amount'] ?></td>
    <td class="p-3 border-b border-gray-200"><?= $order['status'] ?></td>
    <td class="p-3 border-b border-gray-200"><?= $order['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>

</div>

<?php include "../layouts/footer.php"; ?>