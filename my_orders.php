<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
include 'config.php';
$user_id = $_SESSION['user_id'];
$orders = $conn->query("SELECT o.*, p.status AS payment_status FROM orders o LEFT JOIN payments p ON o.id=p.order_id WHERE o.user_id=$user_id ORDER BY o.order_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Saya - 3 Putri Gorden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Pesanan Saya</h2>
    <table border="1" cellpadding="5">
        <tr><th>ID</th><th>Tanggal</th><th>Status Pesanan</th><th>Status Pembayaran</th><th>Total</th></tr>
        <?php while($row = $orders->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['order_date'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['payment_status'] ?? '-' ?></td>
            <td><?= $row['total'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br><a href="user_dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html> 