<?php 
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Little Orchid Shop</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            background-color: #f8f9fa;
        }
        .banner {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('img/banner.webp') no-repeat center center;
            background-size: cover;
            color: white;
            text-align: center;
            padding: 120px 20px;
            border-radius: 10px;
        }
        .banner h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .banner p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        .banner .btn {
            font-size: 1.2rem;
            padding: 12px 40px;
        }
        .cta-section {
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .cta-section img {
            border-radius: 10px 0 0 10px;
        }
        .cta-section h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .cta-section .btn {
            font-size: 1rem;
            padding: 10px 20px;
        }
        .text-highlight {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Banner -->
    <div class="banner mb-5 text-center">
        <h1>Welcome to Little Orchid Shop</h1>
        <p>Fresh Flowers, Unique Bouquets, and Express Delivery</p>
        <a href="products.php" class="btn btn-success">Shop Now</a>
    </div>

    <!-- Best Selling Section -->
        <div class="container my-5">
            <h2 class="text-center mb-4">Our <span class="text-highlight">Best Sellers</span></h2>
            <div class="row">
                <?php
                $bestSellingQuery = "
                    SELECT 
                        p.product_id,
                        p.name,
                        p.price,
                        p.image,
                        SUM(oi.quantity) AS total_sold
                    FROM 
                        products p
                    JOIN 
                        order_items oi ON p.product_id = oi.product_id
                    GROUP BY 
                        p.product_id, p.name, p.price, p.image
                    ORDER BY 
                        total_sold DESC
                    LIMIT 4;
                ";
                $bestSellingResult = $conn->query($bestSellingQuery);

                while ($product = $bestSellingResult->fetch_assoc()): ?>
                    <div class="col-md-3">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text">฿<?php echo number_format($product['price'], 2); ?></p>
                                <p class="text-muted">Sold: <?php echo $product['total_sold']; ?> units</p>
                                <a href="product-detail.php?id=<?php echo $product['product_id']; ?>" class="btn btn-success">Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
    </div>

    <!-- Testimonials Section -->
    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">What Our Customers Say</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <p>"The orchids were stunning! Delivered fresh and on time. Will order again!"</p>
                                <footer class="blockquote-footer">Jane Doe</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <p>"Amazing quality and great customer service. Highly recommend!"</p>
                                <footer class="blockquote-footer">John Smith</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <p>"Beautiful arrangements that made my special occasion even more memorable."</p>
                                <footer class="blockquote-footer">Emily Johnson</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">About <span class="text-highlight">Us</span></h2>
        <div class="row">
            <div class="col-md-6">
                <img src="img/about.jpg" class="img-fluid" alt="About Us">
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-center">
                <p>
                    At Little Orchid Shop, we are passionate about delivering the finest quality orchids and floral arrangements. 
                    With a wide selection of exotic and local varieties, we cater to every occasion. 
                    Whether you’re celebrating a milestone or simply brightening your day, our flowers make the perfect choice.
                </p>
                <a href="products.php" class="btn btn-success">Shop Now</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
