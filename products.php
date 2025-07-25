<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
include 'config.php';
$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk - 3 Putri Gorden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
    <a href="contact.php" title="Hubungi Kami" style="position:absolute;right:32px;top:32px;font-size:1.1rem;text-decoration:none;color:#ffd700;display:flex;align-items:center;gap:6px;">
        <span style="font-size:1.5rem;">✉️</span> <span>Hubungi Kami</span>
    </a>
</div>
<div class="container">
    <h2>Daftar Produk</h2>
    <table border="1" cellpadding="5">
        <tr><th>Nama</th><th>Deskripsi</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
        <?php while($row = $products->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['price'] ?></td>
            <td><?= $row['stock'] ?></td>
            <td>
                <?php if($row['stock']>0): ?>
                <form method="post" action="order.php">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <input type="number" name="quantity" value="1" min="1" max="<?= $row['stock'] ?>" required>
                    <button type="submit">Pesan</button>
                </form>
                <?php else: ?>
                Habis
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br><a href="user_dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html> 