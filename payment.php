<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
include 'config.php';
$order_id = intval($_GET['order_id'] ?? 0);
// Buat folder uploads jika belum ada
define('UPLOAD_DIR', __DIR__ . '/uploads/');
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
// Proses upload bukti transfer
$bukti_url = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method'])) {
    $method = $_POST['method'];
    $order_id = intval($_POST['order_id']);
    $order = $conn->query("SELECT * FROM orders WHERE id=$order_id AND user_id={$_SESSION['user_id']}")->fetch_assoc();
    if ($order) {
        $conn->query("INSERT INTO payments (order_id, amount, method, status) VALUES ($order_id, {$order['total']}, '".addslashes($method)."', 'pending')");
        $conn->query("UPDATE orders SET status='paid' WHERE id=$order_id");
        if ($method === 'Transfer Bank' && isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
            $filename = 'bukti_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $target = UPLOAD_DIR . $filename;
            if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target)) {
                $bukti_url = 'uploads/' . $filename;
                // Update kolom bukti_transfer di tabel payments (tambahkan kolom jika belum ada)
                $conn->query("UPDATE payments SET bukti_transfer='".$bukti_url."' WHERE order_id=".intval($_POST['order_id']));
            }
        }
        header('Location: my_orders.php');
        exit();
    } else {
        echo "<script>alert('Order tidak valid!');window.location='products.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran - 3 Putri Gorden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Pembayaran</h2>
    <?php
    // Tampilkan instruksi setelah pembayaran
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method'])) {
        $method = $_POST['method'];
        if ($method === 'Transfer Bank') {
            $bank = $_POST['bank'];
            $rekening = '';
            if ($bank === 'BCA') $rekening = '1234567890 a.n. 3 Putri Gorden';
            if ($bank === 'BRI') $rekening = '0987654321 a.n. 3 Putri Gorden';
            if ($bank === 'Mandiri') $rekening = '1122334455 a.n. 3 Putri Gorden';
            echo '<div style="background:#fff3cd;color:#856404;padding:16px;border-radius:8px;margin-bottom:16px;">';
            echo '<b>Pembayaran Berhasil Dibuat!</b><br>Silakan transfer ke rekening berikut:<br>';
            echo '<b>Bank: ' . htmlspecialchars($bank) . '</b><br>';
            echo '<b>No. Rekening: ' . htmlspecialchars($rekening) . '</b><br>';
            echo 'Setelah transfer, upload bukti pembayaran di bawah ini.';
            echo '</div>';
            // Form upload bukti
            echo '<form method="post" enctype="multipart/form-data">';
            echo '<input type="hidden" name="order_id" value="'.intval($_POST['order_id']).'">';
            echo '<input type="hidden" name="method" value="Transfer Bank">';
            echo '<input type="hidden" name="bank" value="'.htmlspecialchars($bank).'">';
            echo '<label>Upload Bukti Transfer (jpg/png/pdf):</label>';
            echo '<input type="file" name="bukti" accept="image/*,application/pdf" required>';
            echo '<button type="submit">Upload Bukti</button>';
            echo '</form>';
            if ($bukti_url) {
                echo '<div style="margin-top:12px;">Bukti berhasil diupload:<br>';
                if (preg_match('/\.(jpg|jpeg|png)$/i', $bukti_url)) {
                    echo '<img src="'.$bukti_url.'" style="max-width:200px;max-height:200px;">';
                } else {
                    echo '<a href="'.$bukti_url.'" target="_blank">Lihat Bukti</a>';
                }
                echo '</div>';
            }
        } else {
            echo '<div style="background:#d4edda;color:#155724;padding:16px;border-radius:8px;margin-bottom:16px;">';
            echo '<b>Pembayaran COD Berhasil!</b><br>Silakan siapkan pembayaran saat barang diterima.';
            echo '</div>';
        }
    }
    ?>
    <form method="post" id="formBayar" enctype="multipart/form-data">
        <input type="hidden" name="order_id" value="<?= $order_id ?>">
        <label>Metode Pembayaran:</label>
        <select name="method" id="method" required onchange="toggleBank()">
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="COD">COD</option>
        </select>
        <div id="bankSection">
            <label>Pilih Bank:</label>
            <select name="bank" id="bank">
                <option value="BCA">BCA</option>
                <option value="BRI">BRI</option>
                <option value="Mandiri">Mandiri</option>
            </select>
            <div id="rekeningInfo" style="margin:8px 0 16px 0;"></div>
        </div>
        <div id="buktiSection" style="display:none;">
            <label>Upload Bukti Transfer (jpg/png/pdf):</label>
            <input type="file" name="bukti" id="buktiInput" accept="image/*,application/pdf">
        </div>
        <button type="submit">Konfirmasi Pembayaran</button>
    </form>
</div>
<script>
function toggleBank() {
    var method = document.getElementById('method').value;
    var bankSection = document.getElementById('bankSection');
    var buktiSection = document.getElementById('buktiSection');
    if (method === 'Transfer Bank') {
        bankSection.style.display = 'block';
        buktiSection.style.display = 'block';
        updateRekening();
    } else {
        bankSection.style.display = 'none';
        buktiSection.style.display = 'none';
        document.getElementById('rekeningInfo').innerHTML = '';
    }
}
function updateRekening() {
    var bank = document.getElementById('bank').value;
    var rekening = '';
    if (bank === 'BCA') rekening = '1234567890 a.n. 3 Putri Gorden';
    if (bank === 'BRI') rekening = '0987654321 a.n. 3 Putri Gorden';
    if (bank === 'Mandiri') rekening = '1122334455 a.n. 3 Putri Gorden';
    document.getElementById('rekeningInfo').innerHTML = '<b>No. Rekening: ' + rekening + '</b>';
}
document.getElementById('bank').addEventListener('change', updateRekening);
document.addEventListener('DOMContentLoaded', function() {
    toggleBank();
});
</script>
</body>
</html> 