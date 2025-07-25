<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - 3 Putri Gorden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)</p>
    <ul>
        <li><a href="admin_users.php">Data Pengguna</a></li>
        <li><a href="admin_products.php">Daftar Produk</a></li>
        <li><a href="admin_orders.php">Daftar Pesanan</a></li>
    </ul>
    <a href="logout.php">Logout</a>
</div>
</body>
</html> 