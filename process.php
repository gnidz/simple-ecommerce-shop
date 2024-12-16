<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $productId = $_POST['product_id'];

    if ($action === 'add') {
        // Add product ID to the cart array
        $_SESSION['cart'][] = $productId;
    } elseif ($action === 'remove') {
        // Find and remove product ID from the cart array
        if (($key = array_search($productId, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
        }
    }
}

header('Location: cart.php');
exit();