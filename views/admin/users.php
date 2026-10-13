<?php
session_start();
require_once "../../core/AuthMiddleware.php";
require_once "../../config/database.php";

checkAuth();
checkRole(['admin']);

$stmt = $conn->query("SELECT id, name, email, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../layouts/header.php"; ?>

<div class="p-6">
<h1 class="text-2xl font-bold mb-4">User Management</h1>

<table class="w-full border text-center">
<tr class="bg-gray-200">
    <th class="p-2">ID</th>
    <th class="p-2">Name</th>
    <th class="p-2">Email</th>
    <th class="p-2">Role</th>
    <th class="p-2">Actions</th>
</tr>

<?php foreach ($users as $user): ?>
<tr>
    <td class="p-2"><?= $user['id'] ?></td>
    <td class="p-2"><?= htmlspecialchars($user['name']) ?></td>
    <td class="p-2"><?= htmlspecialchars($user['email']) ?></td>
    <td class="p-2"><?= $user['role'] ?></td>

    <td class="p-2">
        <form method="POST" action="/ISDN/controllers/UserController.php" class="flex gap-2">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

            <select name="role" class="border p-1">
                <option value="customer" <?= $user['role']=='customer'?'selected':'' ?>>Customer</option>
                <option value="rdc_staff" <?= $user['role']=='rdc_staff'?'selected':'' ?>>RDC Staff</option>
                <option value="logistics" <?= $user['role']=='logistics'?'selected':'' ?>>Logistics</option>
                <option value="manager" <?= $user['role']=='manager'?'selected':'' ?>>Manager</option>
                <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
            </select>

            <button type="submit" name="update_role"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow transition duration-200">                Update
            </button>
        </form>
    </td>

    <td class="p-2">
    <?php if ($user['id'] != $_SESSION['user_id']): ?>
        <form method="POST" action="/ISDN/controllers/UserController.php"
              onsubmit="return confirm('Are you sure you want to delete this user?');">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <button type="submit" name="delete_user"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow transition duration-200">
                Delete
            </button>
        </form>
    <?php else: ?>
        <span class="text-gray-500">Current User</span>
    <?php endif; ?>
    </td>
</tr>

<?php endforeach; ?>

</table>
</div>

<?php include "../layouts/footer.php"; ?>