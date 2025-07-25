<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
    if ($product && $product['stock'] >= $quantity) {
        $total = $product['price'] * $quantity;
        $conn->query("INSERT INTO orders (user_id, total) VALUES ($user_id, $total)");
        $order_id = $conn->insert_id;
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, {$product['price']})");
        $conn->query("UPDATE products SET stock = stock - $quantity WHERE id=$product_id");
        header("Location: payment.php?order_id=$order_id");
        exit();
    } else {
        echo "<script>alert('Stok tidak cukup!');window.location='products.php';</script>";
        exit();
    }
} else {
    header('Location: products.php');
    exit();
} 