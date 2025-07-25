<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - 3 Putri Gorden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Dashboard Pengguna</h2>
    <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <ul>
        <li><a href="products.php">Lihat Produk</a></li>
        <li><a href="my_orders.php">Pesanan Saya</a></li>
    </ul>
    <a href="logout.php">Logout</a>
</div>
</body>
</html> 