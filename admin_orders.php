<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php';
// Update status pesanan
if (isset($_POST['update_status'])) {
    $id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='".addslashes($status)."' WHERE id=$id");
}
$orders = $conn->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.order_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pesanan - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Daftar Pesanan</h2>
    <table border="1" cellpadding="5">
        <tr><th>ID</th><th>User</th><th>Tanggal</th><th>Status</th><th>Total</th><th>Aksi</th></tr>
        <?php while($row = $orders->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= $row['order_date'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['total'] ?></td>
            <td>
                <form method="post" style="display:inline">
                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                    <select name="status">
                        <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Pending</option>
                        <option value="paid" <?= $row['status']=='paid'?'selected':'' ?>>Paid</option>
                        <option value="shipped" <?= $row['status']=='shipped'?'selected':'' ?>>Shipped</option>
                        <option value="completed" <?= $row['status']=='completed'?'selected':'' ?>>Completed</option>
                        <option value="cancelled" <?= $row['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status">Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br><a href="admin_dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html> 