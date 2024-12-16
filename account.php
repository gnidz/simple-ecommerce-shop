<?php
session_start();
require 'db_pdo.php';

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fetch user's orders by user_id
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <!-- Main container for the page content with top margin -->
    <div class="container mt-5">
        <h1>My Account</h1>

        <!-- Card displaying user information -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h5>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Role: <?php echo htmlspecialchars($user['role']); ?></p>

                <div class="d-flex align-items-center gap-2 mt-3">

                    <!-- Show Admin Dashboard button if the user has an admin role -->
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="admin_dashboard.php" class="btn btn-primary">Admin Dashboard</a>
                    <?php endif; ?>

                    <!-- Logout button wrapped in a form -->
                    <form method="POST" class="mb-0">
                        <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Section for displaying orders -->                
        <h2>My Orders</h2>
        <?php if (empty($orders)): ?>
            <div class="alert alert-warning">You have no orders yet.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <?php
                            // Fetch total price for the order
                            $stmt = $pdo->prepare("SELECT SUM(oi.price * oi.quantity) AS total_price 
                                                   FROM order_items oi 
                                                   WHERE oi.order_id = ?");
                            $stmt->execute([$order['order_id']]);
                            $total = $stmt->fetchColumn();
                            ?>
                            <!-- Display order details securely using htmlspecialchars -->
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td>฿<?php echo number_format($total, 2); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>

                                <!-- Link to view detailed order confirmation -->
                                <td>
                                    <a href="order_confirmation.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
