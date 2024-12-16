<?php
session_start();
require 'db.php';
require 'db_pdo.php';

// Ensure user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Determine active tab
$activeTab = $_GET['tab'] ?? 'statistics'; // default to 'orders'

// Fetch total number of products
$productQuery = "SELECT COUNT(*) AS total_products FROM products";
$productResult = $conn->query($productQuery);
$totalProducts = $productResult->fetch_assoc()['total_products'];

// Fetch total number of users
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = $conn->query($userQuery);
$totalUsers = $userResult->fetch_assoc()['total_users'];

// Fetch total number of orders
$orderQuery = "SELECT COUNT(*) AS total_orders FROM orders";
$orderResult = $conn->query($orderQuery);
$totalOrders = $orderResult->fetch_assoc()['total_orders'];

// Total Revenue
$revenueQuery = "
    SELECT SUM(quantity * price) AS total_revenue 
    FROM order_items";
$revenueResult = $conn->query($revenueQuery);
$totalRevenue = $revenueResult->fetch_assoc()['total_revenue'] ?? 0;

// Average Order Value
$aovQuery = "
    SELECT SUM(quantity * price) / COUNT(DISTINCT order_id) AS avg_order_value 
    FROM order_items";
$aovResult = $conn->query($aovQuery);
$aov = $aovResult->fetch_assoc()['avg_order_value'] ?? 0;

// Fetch new users in the last 30 days
$newUsersQuery = "SELECT COUNT(*) AS new_users FROM users WHERE created_at >= NOW() - INTERVAL 30 DAY";
$newUsersResult = $conn->query($newUsersQuery);
$newUsers = $newUsersResult->fetch_assoc()['new_users'] ?? 0;

// Fetch pending orders
$pendingOrdersQuery = "SELECT COUNT(*) AS pending_orders FROM orders WHERE status = 'pending'";
$pendingOrdersResult = $conn->query($pendingOrdersQuery);
$pendingOrders = $pendingOrdersResult->fetch_assoc()['pending_orders'] ?? 0;

// Fetch products out of stock
$outOfStockQuery = "SELECT COUNT(*) AS out_of_stock FROM products WHERE stock_quantity = 0";
$outOfStockResult = $conn->query($outOfStockQuery);
$outOfStock = $outOfStockResult->fetch_assoc()['out_of_stock'] ?? 0;


// Handle POST actions
// 1. Handle changing order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_order_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    if ($stmt->execute([$new_status, $order_id])) {
        $success = 'Order status updated successfully.';
    } else {
        $error = 'Failed to update order status. Please try again.';
    }
}

// 2. Handle deleting a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['user_id'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    if ($stmt->execute([$user_id])) {
        $success = 'User deleted successfully.';
    } else {
        $error = 'Failed to delete user. Please try again.';
    }
}

// 3. Handle editing a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $user_id = (int)$_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?");
    if ($stmt->execute([$username, $email, $role, $user_id])) {
        $success = 'User updated successfully.';
    } else {
        $error = 'Failed to update user. Please try again.';
    }
}

// 4. Handle deleting a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = (int)$_POST['product_id'];

    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    if ($stmt->execute([$product_id])) {
        $success = 'Product deleted successfully.';
    } else {
        $error = 'Failed to delete product. Please try again.';
    }
}

// 5. Handle editing a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $product_id = (int)$_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE product_id = ?");
    if ($stmt->execute([$product_name, $price, $description, $image, $product_id])) {
        $success = 'Product updated successfully.';
    } else {
        $error = 'Failed to update product. Please try again.';
    }
}

// 6. Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = $_POST['new_product_name'];
    $price = $_POST['new_price'];
    $description = $_POST['new_description'];
    $image = $_POST['new_image'];

    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$product_name, $price, $description ,$image])) {
        $success = 'Product added successfully.';
    } else {
        $error = 'Failed to add product. Please try again.';
    }
}

// Fetch data based on active tab
if ($activeTab === 'orders') {
    // Fetch all orders
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($activeTab === 'users') {
    // Fetch all users
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($activeTab === 'products') {
    // Fetch all products
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($activeTab === 'messages') {
    // Fetch all contact messages
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #7f7fd5, #86a8e7, #91eae4);
            color: #fff;
            padding: 40px 0;
            text-align: center;
            margin-bottom: 40px;
            border-radius: 0 0 10px 10px;
        }
        .dashboard-header h1 {
            margin: 0;
            font-size: 3rem;
            font-weight: 700;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .nav-tabs .nav-link.active {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p>Manage orders, users, and products</p>
    </div>

    <div class="container mt-5">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link <?php echo ($activeTab === 'statistics') ? 'active' : ''; ?>" href="?tab=statistics">Statistics</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($activeTab === 'orders') ? 'active' : ''; ?>" href="?tab=orders">Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($activeTab === 'users') ? 'active' : ''; ?>" href="?tab=users">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($activeTab === 'products') ? 'active' : ''; ?>" href="?tab=products">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($activeTab === 'messages') ? 'active' : ''; ?>" href="?tab=messages">Messages</a>
            </li>
        </ul>

        <!-- Statistics Section -->
            <?php if ($activeTab === 'statistics'): ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="stat-card d-flex align-items-center">
                            <i class="fas fa-box fa-2x me-3 text-primary"></i>
                            <div>
                                <h4 class="mb-0"><?php echo $totalProducts; ?></h4>
                                <p class="mb-0">Total Products</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card d-flex align-items-center">
                            <i class="fas fa-users fa-2x me-3 text-primary"></i>
                            <div>
                                <h4 class="mb-0"><?php echo $totalUsers; ?></h4>
                                <p class="mb-0">Total Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card d-flex align-items-center">
                            <i class="fas fa-shopping-cart fa-2x me-3 text-primary"></i>
                            <div>
                                <h4 class="mb-0"><?php echo $totalOrders; ?></h4>
                                <p class="mb-0">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card d-flex align-items-center">
                            <i class="fas fa-dollar-sign fa-2x me-3 text-primary"></i>
                            <div>
                                <h4 class="mb-0">฿<?php echo number_format($totalRevenue, 2); ?></h4>
                                <p class="mb-0">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card d-flex align-items-center">
                            <i class="fas fa-chart-line fa-2x me-3 text-primary"></i>
                            <div>
                                <h4 class="mb-0">฿<?php echo number_format($aov, 2); ?></h4>
                                <p class="mb-0">Average Order Value</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card d-flex align-items-center">
                            <i class="fas fa-user-plus fa-2x me-3 text-primary"></i>
                            <div>
                                <h4 class="mb-0"><?php echo $newUsers; ?></h4>
                                <p class="mb-0">New Users (Last 30 Days)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card d-flex align-items-center">
                            <i class="fas fa-clock fa-2x me-3 text-primary"></i>
                            <div>
                                <h4 class="mb-0"><?php echo $pendingOrders; ?></h4>
                                <p class="mb-0">Pending Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card d-flex align-items-center">
                            <i class="fas fa-box-open fa-2x me-3 text-primary"></i>
                            <div>
                                <h4 class="mb-0"><?php echo $outOfStock; ?></h4>
                                <p class="mb-0">Products Out of Stock</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>



        <!-- Orders Section -->
        <?php if ($activeTab === 'orders'): ?>
            <div class="card p-4 mb-5">
                <h2 class="mb-4">All Orders</h2>
                <?php if (empty($orders)): ?>
                    <div class="alert alert-warning">No orders found.</div>
                <?php else: ?>
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>User ID</th>
                                <th>Order Date</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                                    <td>
                                        <!-- Change Status -->
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="order_id" value="<?php echo (int)$order['order_id']; ?>">
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                                <option value="Pending" <?php echo ($order['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Processing" <?php echo ($order['status'] === 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                                <option value="Shipped" <?php echo ($order['status'] === 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                                <option value="Completed" <?php echo ($order['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                                <option value="Cancelled" <?php echo ($order['status'] === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" name="change_order_status" class="btn btn-primary btn-sm">Update</button>
                                        </form>

                                        <!-- View Order Button -->
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewOrderModal-<?php echo $order['order_id']; ?>">View</button>
                                    </td>
                                </tr>

                                <!-- View Order Modal -->
                                <div class="modal fade" id="viewOrderModal-<?php echo $order['order_id']; ?>" tabindex="-1" aria-labelledby="viewOrderModalLabel-<?php echo $order['order_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewOrderModalLabel-<?php echo $order['order_id']; ?>">Order Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></h6>
                                                <h6><strong>User ID:</strong> <?php echo htmlspecialchars($order['user_id']); ?></h6>
                                                <h6><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></h6>
                                                <h6><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></h6>
                                                <h6><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></h6>
                                                <h6><strong>Shipping Address:</strong></h6>
                                                <p><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>

                                                <h6 class="mt-4"><strong>Order Items:</strong></h6>
                                                <?php
                                                // Fetch order items
                                                $stmt = $pdo->prepare("
                                                    SELECT oi.*, p.name 
                                                    FROM order_items oi
                                                    JOIN products p ON oi.product_id = p.product_id
                                                    WHERE oi.order_id = ?
                                                ");
                                                $stmt->execute([$order['order_id']]); // Use the correct order ID field
                                                $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                if (!empty($order_items)): ?>
                                                    <ul>
                                                        <?php foreach ($order_items as $item): ?>
                                                            <li>
                                                                <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?> 
                                                                - Quantity: <?php echo (int)$item['quantity']; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php else: ?>
                                                    <p>No items found for this order.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Users Section -->
        <?php if ($activeTab === 'users'): ?>
            <div class="card p-4 mb-5">
                <h2 class="mb-4">Users List</h2>
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['role']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal-<?php echo $u['user_id']; ?>">Edit</button>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit User Modal -->
                            <div class="modal fade" id="editUserModal-<?php echo $u['user_id']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel-<?php echo $u['user_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($u['username']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Role</label>
                                                    <select class="form-select" name="role" required>
                                                        <option value="user" <?php echo $u['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                                        <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                    </select>
                                                </div>
                                                <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Products Section -->
        <?php if ($activeTab === 'products'): ?>
            <div class="card p-4">
                <h2 class="mb-4">Products List</h2>
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-success col-2" data-bs-toggle="modal" data-bs-target="#addProductModal">Add New Product</button>
                </div>

                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['product_id']); ?></td>
                                <td><?php echo htmlspecialchars($p['name']); ?></td>
                                <td><?php echo number_format($p['price'], 2); ?> ฿</td>
                                <td><img src="<?php echo htmlspecialchars($p['image']); ?>" alt="Product Image" style="width: 60px;"></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal-<?php echo $p['product_id']; ?>">Edit</button>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
                                        <button type="submit" name="delete_product" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Product Modal -->
                            <div class="modal fade" id="editProductModal-<?php echo $p['product_id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Product</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Product Name</label>
                                                    <input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($p['name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Price (฿)</label>
                                                    <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $p['price']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($p['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Image URL</label>
                                                    <input type="text" class="form-control" name="image" value="<?php echo htmlspecialchars($p['image']); ?>" required>
                                                </div>
                                                <button type="submit" name="edit_product" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add Product Modal -->
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="new_product_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price (฿)</label>
                                    <input type="number" step="0.01" class="form-control" name="new_price" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="new_description" rows="5" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Image URL</label>
                                    <input type="text" class="form-control" name="new_image" value="img/" required>
                                </div>
                                <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Messages Section -->
        <?php if ($activeTab === 'messages'): ?>
            <div class="card p-4">
                <h2 class="mb-4">Contact Messages</h2>
                <?php if (empty($messages)): ?>
                    <div class="alert alert-info">No messages found.</div>
                <?php else: ?>
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Received On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $m): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($m['id']); ?></td>
                                    <td><?php echo htmlspecialchars($m['name']); ?></td>
                                    <td><?php echo htmlspecialchars($m['email']); ?></td>
                                    <td style="max-width: 300px; word-wrap: break-word; white-space: normal; overflow-wrap: anywhere;">
                                        <?php echo nl2br(htmlspecialchars($m['message'], ENT_QUOTES, 'UTF-8')); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($m['created_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
