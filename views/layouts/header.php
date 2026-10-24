<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ISDN System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<aside class="w-64 bg-gradient-to-b from-indigo-800 to-indigo-900 text-white min-h-screen p-6">

    <h2 class="text-2xl font-bold mb-8">ISDN</h2>

    <?php
    $baseClass = "block px-4 py-2 rounded-lg transition duration-200";
    $activeClass = $baseClass . " bg-indigo-600 text-white";
    $normalClass = $baseClass . " text-indigo-100 hover:bg-indigo-700";
    ?>

    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="space-y-4">

        <!-- Dashboard -->
        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager'): ?>
            <a href="/ISDN/views/dashboard/index.php"
               class="<?= $currentPage == 'index.php' ? $activeClass : $normalClass ?>">
                Dashboard
            </a>
        <?php elseif ($_SESSION['role'] === 'customer'): ?>
            <a href="/ISDN/views/dashboard/customer.php"
               class="<?= $currentPage == 'customer.php' ? $activeClass : $normalClass ?>">
                Dashboard
            </a>
        <?php elseif ($_SESSION['role'] === 'logistics'): ?>
            <a href="/ISDN/views/dashboard/logistics.php"
               class="<?= $currentPage == 'logistics.php' ? $activeClass : $normalClass ?>">
                Dashboard
            </a>
        <?php elseif ($_SESSION['role'] === 'rdc_staff'): ?>
            <a href="/ISDN/views/dashboard/rdc.php"
               class="<?= $currentPage == 'rdc.php' ? $activeClass : $normalClass ?>">
                Dashboard
            </a>
        <?php endif; ?>

        <!-- Role Based Links -->

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="/ISDN/views/products/list.php" class="<?= $currentPage == 'list.php' ? $activeClass : $normalClass ?>">Products</a>
            <a href="/ISDN/views/inventory/manage.php" class="<?= $currentPage == 'manage.php' ? $activeClass : $normalClass ?>">Inventory</a>
            <a href="/ISDN/views/admin/promotions.php" class="<?= $currentPage == 'promotions.php' ? $activeClass : $normalClass ?>">Promotions</a>
            <a href="/ISDN/views/inventory/transfer.php" class="<?= $currentPage == 'transfer.php' ? $activeClass : $normalClass ?>">Transfer Stock</a>
            <a href="/ISDN/views/orders/manage.php" class="<?= $currentPage == 'manage.php' ? $activeClass : $normalClass ?>">Orders</a>
            <a href="/ISDN/views/admin/users.php" class="<?= $currentPage == 'users.php' ? $activeClass : $normalClass ?>">Users</a>
            <a href="/ISDN/views/reports/sales.php" class="<?= $currentPage == 'sales.php' ? $activeClass : $normalClass ?>">Reports</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'customer'): ?>
            <a href="/ISDN/views/products/list.php" class="<?= $currentPage == 'list.php' ? $activeClass : $normalClass ?>">Products</a>
            <a href="/ISDN/views/orders/history.php" class="<?= $currentPage == 'history.php' ? $activeClass : $normalClass ?>">My Orders</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'rdc_staff'): ?>
            <a href="/ISDN/views/inventory/manage.php" class="<?= $currentPage == 'manage.php' ? $activeClass : $normalClass ?>">Inventory</a>
            <a href="/ISDN/views/inventory/transfer.php" class="<?= $currentPage == 'transfer.php' ? $activeClass : $normalClass ?>">Transfer</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'logistics'): ?>
            <a href="/ISDN/views/orders/manage.php" class="<?= $currentPage == 'manage.php' ? $activeClass : $normalClass ?>">Manage Orders</a>
        <?php endif; ?>

        <a href="/ISDN/controllers/logout.php"
           class="block bg-red-500 hover:bg-red-600 px-3 py-2 rounded mt-6 text-center">
            Logout
        </a>

    </nav>
    <?php endif; ?>

</aside>

<!-- MAIN CONTENT AREA -->
<div class="flex-1 flex flex-col">

    <main class="flex-grow p-8">