<?php
session_start();
require 'db_pdo.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $is_logged_in = false;
} else {
    $is_logged_in = true;
}

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$total_price = 0;
$cart_products = [];

// Fetch product details for all IDs in the cart
if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->execute($cart);
    $cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $product_id_to_remove = (int)$_POST['product_id'];

    // Remove only the first occurrence of the product ID
    $key = array_search($product_id_to_remove, $_SESSION['cart']);
    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
    }

    // Re-index the cart array to maintain proper indices
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header("Location: cart.php");
    exit();
}

// Group products by ID to calculate quantities
$product_quantities = array_count_values($cart);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .cart-summary {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 20px;
            background-color: #fff;
        }
        .cart-total {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h1>Checkout</h1>

        <?php if (empty($cart_products)): ?>
            <div class="alert alert-warning">Your cart is empty. <a href="products.php">Go back to products</a></div>
        <?php else: ?>
            <?php 
            // Calculate total price
            foreach ($cart_products as $product) {
                $quantity = $product_quantities[$product['product_id']];
                $product_total = $product['price'] * $quantity;
                $total_price += $product_total;
            }
            ?>

            <div class="cart-summary mb-4">
                <h2 class="mb-4">Cart Items</h2>
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Price (฿)</th>
                            <th>Quantity</th>
                            <th>Total (฿)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_products as $product): ?>
                            <?php
                            $quantity = $product_quantities[$product['product_id']];
                            $product_total = $product['price'] * $quantity;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo $quantity; ?></td>
                                <td><?php echo number_format($product_total, 2); ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$product['product_id']; ?>">
                                        <button type="submit" name="remove_item" class="btn btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-end">
                    <span class="cart-total">Total: ฿<?php echo number_format($total_price, 2); ?></span>
                </div>
            </div>

            <?php if (!$is_logged_in): ?>
                <!-- User not logged in -->
                <div class="alert alert-info">
                    Please <a href="login.php">login</a> to proceed with the checkout.
                </div>
            <?php else: ?>
                <!-- User is logged in, show form to get name, address, and payment_method -->
                <div class="card p-4 mb-5">
                    <h2 class="mb-4">Shipping & Payment Details</h2>
                    <form action="confirm_checkout.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Shipping Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Confirm Order</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
