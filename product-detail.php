<?php
include 'db.php';
$id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE product_id = $id")->fetch_assoc();

// Fetch related products (adjust as needed)
$related_products = $conn->query("SELECT * FROM products WHERE product_id != $id LIMIT 9")->fetch_all(MYSQLI_ASSOC);

// Break related products into groups of 3 per slide
$related_chunks = array_chunk($related_products, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .product-container {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-details h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .product-details .price {
            font-size: 1.5rem;
            color: #28a745;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        .product-details p {
            font-size: 1rem;
            line-height: 1.5;
        }
        .btn-success {
            font-size: 1rem;
            font-weight: 600;
        }

        /* Position add-to-cart at bottom-right */
        .product-details {
            position: relative;
        }
        .add-to-cart-container {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        /* Make carousel controls more visible */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 2rem;
            height: 2rem;
            background-color: rgba(168, 39, 219, 0.6);
            border-radius: 50%;
            background-size: 50%;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Spacing for related products */
        .related-product img {
            border-radius: 6px;
        }
        .related-product h5 {
            margin-top: 0.5rem;
            font-size: 1.1rem;
        }
        .related-product p {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="product-container">
            <div class="row">
                <div class="col-md-5">
                    <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" class="product-image img-fluid">
                </div>
                <div class="col-md-7 product-details d-flex flex-column">
                    <div>
                        <h1><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                        <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                        <p><?php echo nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                    </div>
                    <div class="add-to-cart-container">
                        <form id="add-to-cart-form-<?php echo (int)$product['product_id']; ?>" class="add-to-cart-form mb-0">
                            <input type="hidden" name="product_id" value="<?php echo (int)$product['product_id']; ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="button" class="btn btn-success add-to-cart-button" data-product-id="<?php echo (int)$product['product_id']; ?>">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($related_products)) : ?>
            <div id="relatedProductsCarousel" class="carousel slide mt-5" data-bs-ride="carousel">
                <h1>Other Orchid</h1>
                <div class="carousel-inner">
                    <?php $isFirst = true; ?>
                    <?php foreach ($related_chunks as $chunk): ?>
                        <div class="carousel-item <?php echo $isFirst ? 'active' : ''; ?>">
                            <div class="row justify-content-center">
                                <?php foreach ($chunk as $rel): ?>
                                    <div class="col-6 col-sm-4 col-md-3 related-product text-center px-3">
                                        <a href="product-detail.php?id=<?php echo (int)$rel['product_id']; ?>" class="text-decoration-none text-dark">
                                            <img src="<?php echo htmlspecialchars($rel['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                class="img-fluid" 
                                                style="max-height: 200px; object-fit: cover;" 
                                                alt="<?php echo htmlspecialchars($rel['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <h6 class="mt-2 text-truncate"><?php echo htmlspecialchars($rel['name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                            <p class="mb-0 small">$<?php echo number_format($rel['price'], 2); ?></p>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $isFirst = false; ?>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#relatedProductsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#relatedProductsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        <?php endif; ?>
        <br>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.add-to-cart-button').on('click', function() {
            var productId = $(this).data('product-id');
            var formData = {
                product_id: productId,
                action: 'add'
            };

            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Reload the cart count
                    $('#cart-badge-container').load('header.php #cart-badge-container > *');

                    // Trigger the Bootstrap toast (ensure you have a toast element in your layout)
                    var toastElement = document.getElementById('toast-notification');
                    if (toastElement) {
                        var toast = new bootstrap.Toast(toastElement);
                        toast.show();
                    }
                },
                error: function() {
                    alert('Failed to add product to cart.');
                }
            });
        });
    });
    </script>
</body>
</html>
