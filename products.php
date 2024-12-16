<?php
include 'db.php';

// Get search and sort parameters from the URL
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

// Start building the query
$query = "SELECT * FROM products WHERE 1=1";

// If the user entered a search term, filter by product name
if ($search !== '') {
    $search_esc = $conn->real_escape_string($search);
    $query .= " AND name LIKE '%$search_esc%'";
}

// Apply sorting based on the user’s choice
switch ($sort) {
    case 'price_asc':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY price DESC";
        break;
    case 'name_asc':
        $query .= " ORDER BY name ASC";
        break;
    case 'name_desc':
        $query .= " ORDER BY name DESC";
        break;
    default:
        // No sorting selected, leave query as is
        break;
}

// Execute the query
$result = $conn->query($query);

// Now $result contains your filtered and/or sorted products.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        /* Gradient heading section */
        .page-header {
            background: linear-gradient(135deg, #7f7fd5, #86a8e7, #91eae4);
            color: #fff;
            padding: 40px 0;
            text-align: center;
            margin-bottom: 40px;
            border-radius: 0 0 10px 10px;
        }
        .page-header h1 {
            margin: 0;
            font-size: 3rem;
            font-weight: 700;
        }

        /* Utility bar above products */
        .utility-bar {
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
        }

        .utility-bar .form-control,
        .utility-bar .form-select {
            max-width: 300px;
        }

        /* Product cards */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .card-img-top:hover {
            filter: brightness(70%);
            transition: filter 0.3s ease;
        }

        .card-body {
            position: relative;
        }

        /* Example badge for new or sale items */
        .badge-position {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        /* Star rating styling */
        .star-rating {
            color: #ffc107;
            font-size: 0.9rem;
        }

        /* Add-to-cart and details button spacing */
        .btn {
            border-radius: 20px;
            font-weight: 600;
        }

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <h1>Our Products</h1>
    </div>

    <div class="container">
        <!-- Utility Bar for Search and Sort -->
        <form method="get" class="utility-bar">
            <div class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search products..." aria-label="Search products" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="btn btn-secondary">Search</button>
            </div>
            <div>
                <select class="form-select" name="sort" onchange="this.form.submit()">
                    <option value="" <?php echo $sort === '' ? 'selected' : ''; ?>>Sort by</option>
                    <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name: A-Z</option>
                    <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Name: Z-A</option>
                </select>
            </div>
        </form>

        <!-- Products Grid -->
        <div class="row">
            <?php
            if ($result->num_rows > 0):
                while ($product = $result->fetch_assoc()):
                    #Show "New" if ID is recent, or "Sale" if price < 100
                    $badge = "";
                    if ($product['price'] < 100) {
                        $badge = '<span class="badge bg-danger badge-position">Sale</span>';
                    } else if ($product['product_id'] > 5) {
                        $badge = '<span class="badge bg-success badge-position">New</span>';
                    }
            ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="position-relative">
                                <?php echo $badge; ?>
                                <a href="product-detail.php?id=<?php echo (int)$product['product_id']; ?>">
                                    <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                </a>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-truncate" title="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </h5>
                                <p class="card-text fw-bold text-success"><?php echo number_format($product['price'], 2); ?> ฿</p>

                                <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
                                    <!-- Details Button -->
                                    <a href="product-detail.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-sm px-3">Details</a>
                                    
                                    <!-- Add to Cart Button -->
                                    <form id="add-to-cart-form-<?php echo (int)$product['product_id']; ?>" class="add-to-cart-form d-flex justify-content-end mb-0">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$product['product_id']; ?>">
                                        <input type="hidden" name="action" value="add">
                                        <button type="button" class="btn btn-success btn-sm px-3 add-to-cart-button" data-product-id="<?php echo (int)$product['product_id']; ?>">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;
            else:
                echo "<p>No products found.</p>";
            endif;
            ?>
        </div>
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
                    
                    // Trigger the Bootstrap toast
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
