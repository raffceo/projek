<?php
include 'config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'user';

    if ($password !== $confirm) {
        $message = 'Password tidak cocok!';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $username, $email, $hash, $role);
        if ($stmt->execute()) {
            header('Location: login.php?register=success');
            exit();
        } else {
            $message = 'Registrasi gagal: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - 3 Putri Gorden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo 3 Putri Gorden">
    <h1>3 Putri Gorden</h1>
</div>
<div class="container">
    <h2>Register</h2>
    <?php if ($message) echo '<p style="color:red">'.$message.'</p>'; ?>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <label>Konfirmasi Password:</label><br>
        <input type="password" name="confirm" required><br>
        <input type="hidden" name="role" value="user">
        <button type="submit">Register</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</div>
</body>
</html> 