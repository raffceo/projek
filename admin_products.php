<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php';
// Tambah produk
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $conn->query("INSERT INTO products (name, description, price, stock) VALUES ('".addslashes($name)."', '".addslashes($desc)."', $price, $stock)");
}
// Hapus produk
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
}
// Edit produk
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $conn->query("UPDATE products SET name='".addslashes($name)."', description='".addslashes($desc)."', price=$price, stock=$stock WHERE id=$id");
}
$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Daftar Produk</h2>
    <form method="post">
        <input type="hidden" name="id" id="edit_id">
        <input type="text" name="name" id="edit_name" placeholder="Nama Produk" required>
        <input type="text" name="description" id="edit_desc" placeholder="Deskripsi">
        <input type="number" name="price" id="edit_price" placeholder="Harga" required>
        <input type="number" name="stock" id="edit_stock" placeholder="Stok" required>
        <button type="submit" name="add">Tambah Produk</button>
        <button type="submit" name="edit">Simpan Edit</button>
    </form>
    <table border="1" cellpadding="5">
        <tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
        <?php while($row = $products->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['price'] ?></td>
            <td><?= $row['stock'] ?></td>
            <td>
                <button onclick="editProduk(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['name'])) ?>', '<?= htmlspecialchars(addslashes($row['description'])) ?>', <?= $row['price'] ?>, <?= $row['stock'] ?>)">Edit</button>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus produk ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <script>
    function editProduk(id, name, desc, price, stock) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_desc').value = desc;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_stock').value = stock;
    }
    </script>
    <br><a href="admin_dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html> 