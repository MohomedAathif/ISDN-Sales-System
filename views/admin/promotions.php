<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();
checkRole(['admin']);

$stmt = $conn->query("SELECT * FROM promotions ORDER BY created_at DESC");
$promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h1 class="text-2xl font-bold mb-4">Manage Promotions</h1>

<form method="POST" action="/ISDN/controllers/PromotionController.php" class="mb-6 bg-white p-4 shadow rounded">
    <input type="text" name="title" placeholder="Promotion Title" required class="border p-2 w-full mb-2">
    <input type="number" name="discount" placeholder="Discount %" required class="border p-2 w-full mb-2">
    <input type="date" name="start_date" required class="border p-2 w-full mb-2">
    <input type="date" name="end_date" required class="border p-2 w-full mb-2">
    <button type="submit" name="create_promotion"
        class="bg-indigo-600 text-white px-4 py-2 rounded">
        Create Promotion
    </button>
</form>

<table class="min-w-full bg-white shadow-md rounded-lg text-center">
<tr class="bg-indigo-600 text-white">
    <th class="p-2">Title</th>
    <th class="p-2">Discount</th>
    <th class="p-2">Start</th>
    <th class="p-2">End</th>
</tr>

<?php foreach ($promotions as $promo): ?>
<tr>
    <td class="p-2"><?= $promo['title'] ?></td>
    <td class="p-2"><?= $promo['discount_percent'] ?>%</td>
    <td class="p-2"><?= $promo['start_date'] ?></td>
    <td class="p-2"><?= $promo['end_date'] ?></td>

    <td class="p-2">
        <form method="POST" action="/ISDN/controllers/PromotionController.php"
              onsubmit="return confirm('Are you sure you want to delete this promotion?')">

            <input type="hidden" name="promotion_id" value="<?= $promo['id'] ?>">

            <button type="submit" name="delete_promotion"
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                Delete
            </button>

        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>

<?php include "../layouts/footer.php"; ?>