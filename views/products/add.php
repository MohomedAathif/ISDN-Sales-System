<?php
session_start();
require_once "../../core/AuthMiddleware.php";

checkAuth();
checkRole(['admin']);
?>

<?php include "../layouts/header.php"; ?>

<div class="max-w-lg mx-auto mt-10 p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Add Product</h2>

    <form method="POST" action="../../controllers/ProductController.php">
        <input type="text" name="name" placeholder="Product Name" required class="w-full p-2 border mb-3">
        <textarea name="description" placeholder="Description" class="w-full p-2 border mb-3"></textarea>
        <input type="number" step="0.01" min="0" name="price" placeholder="Price" required class="w-full p-2 border mb-3 rounded">
        <input type="text" name="category" placeholder="Category" class="w-full p-2 border mb-3">

        <button type="submit" name="add_product"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg shadow transition duration-200">
            Add Product
        </button>
    </form>
</div>

<?php include "../layouts/footer.php"; ?>