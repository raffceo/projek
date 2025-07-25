<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
include 'config.php';
// Hapus user jika ada request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id AND role='user'");
    header('Location: admin_users.php');
    exit();
}
$result = $conn->query("SELECT id, username, email, role FROM users");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Pengguna - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Data Pengguna</h2>
    <table border="1" cellpadding="5">
        <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['role'] ?></td>
            <td><?php if($row['role']!='admin'): ?><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus user ini?')">Hapus</a><?php endif; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br><a href="admin_dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html> 