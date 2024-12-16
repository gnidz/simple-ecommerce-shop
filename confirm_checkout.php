<?php
session_start();
require 'db_pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("Error: You must be logged in to place an order.");
    }

    $user_id = $_SESSION['user_id']; // Retrieve user ID from session

    // Validate that the cart exists and is not empty
    if (empty($_SESSION['cart'])) {
        die("Error: Your cart is empty.");
    }

    // Capture user details
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';

    // Validate user details
    if (empty($name) || empty($address) || empty($payment_method)) {
        die("Error: All fields are required.");
    }

    // Group product IDs in the cart by quantity
    $cart = $_SESSION['cart'];
    $product_quantities = array_count_values($cart);

    // Calculate total order price and validate product existence
    $placeholders = implode(',', array_fill(0, count($product_quantities), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->execute(array_keys($product_quantities));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($products)) {
        die("Error: Invalid products in your cart.");
    }

    $total_price = 0;
    foreach ($products as $product) {
        $product_id = $product['product_id'];
        $quantity = $product_quantities[$product_id];
        $total_price += $product['price'] * $quantity;
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert into orders table
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, name, address, payment_method, order_date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $name, $address, $payment_method]);
        $order_id = $pdo->lastInsertId();

        // Insert into order_items table
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($products as $product) {
            $product_id = $product['product_id'];
            $quantity = $product_quantities[$product_id];
            $price = $product['price'];
            $stmt->execute([$order_id, $product_id, $quantity, $price]);
        }

        // Commit transaction
        $pdo->commit();

        // Clear the cart
        unset($_SESSION['cart']);

        // Redirect to the confirmation page
        header("Location: order_confirmation.php?order_id=$order_id");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        die("Error: Failed to process your order. Please try again.");
    }
} else {
    die("Invalid request method.");
}
?>
