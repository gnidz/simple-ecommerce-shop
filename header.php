<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the cart session is an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Cart count (default is 0 if the cart is empty)
$cart_items = $_SESSION['cart'];
$cart_count = count($cart_items);
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Little Orchid Shop</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Alegreya', sans-serif;
        }
        .top-header {
            background: linear-gradient(to right, #f4f5f4, #f4f5f4);
            border-bottom: 1px solid #f7f7f7;
            padding: 10px 20px;
        }
        .top-header .contact-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-header .contact-info div {
            display: flex;
            align-items: center;
        }
        .top-header .contact-info div i {
            margin-right: 10px;
        }
        .navbar-brand {
            color: #a827db;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .navbar-brand:hover {
            color: black;
        }

        .navbar-nav .nav-link {
            color: #000;
            font-size: 1rem;
            position: relative;
            transition: color 0.2s;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            height: 3px;
            left: 0;
            bottom: -3px;
            width: 0;
            background: #a827db;
            transition: width 0.2s;
        }

        .navbar-nav .nav-link:hover {
            color: #a827db;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .badge {
            background-color: lightgreen;
            font-size: 0.8rem !important;
        }

        .social-icons a {
            margin: 0 10px;
            color: #555;
        }
        .social-icons a:hover {
            color: #000;
        }

        .toast {
            font-size: 1rem;
            font-weight: bold;
            background-color: #28a745;
            color: #fff;
        }

        .toast .toast-body {
            text-align: center;
        }

        .toast .btn-close-white {
            filter: brightness(0) invert(1);
        }
        .contact-header {
            background: linear-gradient(135deg, #7f7fd5, #86a8e7, #91eae4);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        .contact-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .contact-header p {
            font-size: 1.2rem;
        }
        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }
        .contact-info {
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Bounce animation */
        @keyframes bounce-in {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        @keyframes bounce-out {
            0% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(50px);
                opacity: 0;
            }
        }

        .toast.bounce-in {
            animation: bounce-in 0.7s ease forwards;
        }

        .toast.bounce-out {
            animation: bounce-out 0.5s ease forwards;
        }

    </style>
</head>
<body>

    <!-- Top Header -->
    <div class="top-header">
        <div class="contact-info">
            <div>
                <i class="fas fa-phone"></i> +66 2 649 5000
                <i class="far fa-envelope ms-3"></i> contact@swu.ac.th
            </div>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow">
        <div class="container">
            <a class="navbar-brand" href="index.php">Little Orchid Shop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="account.php">Account</a></li>
                    <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        Cart
                        <span id="cart-badge-container">
                            <?php if ($cart_count > 0): ?>
                                <span class="badge bg-primary"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </span>
                    </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Toast notification container -->
    <div aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toast-notification" class="toast align-items-center text-bg-success border-0 bounce-in" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Product added to cart successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>