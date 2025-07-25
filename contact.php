<?php
include 'config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $pesan = trim($_POST['pesan']);
    $stmt = $conn->prepare('INSERT INTO contact_messages (nama, email, pesan) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $nama, $email, $pesan);
    if ($stmt->execute()) {
        $message = 'Pesan Anda berhasil dikirim. Admin akan segera menghubungi Anda.';
    } else {
        $message = 'Gagal mengirim pesan. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hubungi Kami - 3 Putri Gorden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Hubungi Kami</h2>
    <?php if ($message) echo '<div style="background:#d4edda;color:#155724;padding:12px;border-radius:8px;margin-bottom:16px;">'.$message.'</div>'; ?>
    <form method="post">
        <label>Nama:</label>
        <input type="text" name="nama" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Pesan:</label>
        <textarea name="pesan" rows="5" style="width:100%;border-radius:6px;padding:10px;resize:vertical;" required></textarea>
        <button type="submit">Kirim Pesan</button>
    </form>
    <br><a href="products.php">Kembali ke Produk</a>
</div>
</body>
</html> 