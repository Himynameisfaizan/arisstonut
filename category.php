<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Configuration data connection pipeline
include('config/connect.php');

// 1. .htaccess se redirected dynamic URL parameter catch karna
if (!isset($_GET['url']) || empty(trim($_GET['url']))) {
    header("Location: " . $site . "index.php");
    exit();
}

$category_slug = htmlspecialchars(trim($_GET['url']));

$cat_stmt = $conn->prepare("SELECT `cate_id`, `categories`, `meta_title`, `meta_desc` FROM `categories` WHERE `slug_url` = ? AND `status` = 1 LIMIT 1");
$cat_stmt->bind_param("s", $category_slug);
$cat_stmt->execute();
$cat_res = $cat_stmt->get_result();

if (!$cat_res || $cat_res->num_rows == 0) {
    echo "<h2 style='text-align:center; margin-top:100px; color:#8B4513; font-family:sans-serif;'>Category Not Found.</h2>";
    exit();
}

$category_data = $cat_res->fetch_assoc();
$current_cate_id = intval($category_data['cate_id']);
$current_category_name = htmlspecialchars($category_data['categories']);
$cat_stmt->close();

// RELATIONAL FIX: products table ke 'pro_cate' column ko 'cate_id' se match karna
$prod_query = "SELECT * FROM `products` WHERE `pro_cate` = '$current_cate_id' AND `status` = 1 AND `is_disabled` = 0 ORDER BY `id` DESC";
$products_result = $conn->query($prod_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category_data['meta_title']); ?> - AristoNut</title>
    <meta name="description" content="<?php echo htmlspecialchars($category_data['meta_desc']); ?>">

    <?php 
    // DYNAMIC BREADCRUMB SETUP
    $pageTitle = $current_category_name;
    $parentName = "All Categories";
    $parentUrl = $site . "product.php";
    include('inc/header.php'); 
    include('inc/breadcrumb.php'); 
    ?>

</head>

<body>

    <main class="category-products-wrapper">
        <div class="container">
            
            <div class="row g-4 justify-content-center">
                <?php
                if ($products_result && $products_result->num_rows > 0) {
                    while ($product = $products_result->fetch_assoc()) {
                        $p_id = intval($product['id']);
                        $p_name = htmlspecialchars($product['pro_name']);
                        $p_price = htmlspecialchars($product['selling_price']);
                        $p_qty = htmlspecialchars($product['qty']);
                        $p_slug = htmlspecialchars($product['slug_url']);
                        $p_img = !empty($product['pro_img']) ? $site . 'admin/assets/img/uploads/' . htmlspecialchars($product['pro_img']) : $site . 'assets/images/hero.webp';

                        // 🎯 SEO Friendly Link Structure
                        $seo_detail_url = $site . "product/" . $p_slug;
                        $is_wished = (isset($_SESSION['wishlist']) && in_array($p_id, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
                        ?>
                        
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="video-prod-card">
                                
                                <!-- Wishlist Toggle -->
                                <div class="v-wish-btn" onclick="handleWishlist(<?php echo $p_id; ?>, this)">
                                    <i class="bi <?php echo $is_wished; ?>"></i>
                                </div>

                                <!-- Product Image with 360 Hover Rotation -->
                                <a href="<?php echo $seo_detail_url; ?>" class="v-img-box">
                                    <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>">
                                </a>

                                <!-- Rating Stars -->
                                <div class="v-rating">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <span class="text-muted ms-1">(4.9)</span>
                                </div>

                                <!-- Product Title & Weight -->
                                <a href="<?php echo $seo_detail_url; ?>" class="v-title" title="<?php echo $p_name; ?>">
                                    <?php echo $p_name; ?>
                                </a>
                                <div class="v-weight">Net Wt: <?php echo !empty($p_qty) ? $p_qty : '100g'; ?></div>

                                <!-- Price & Action Buttons -->
                                <div class="v-bottom-section">
                                    <div class="v-price">₹<?php echo $p_price; ?></div>
                                    <div class="v-action-buttons">
                                        <button class="v-btn-cart" onclick="addToCart(<?php echo $p_id; ?>)">
                                            Cart
                                        </button>
                                        <button class="v-btn-buy" onclick="buyNow(<?php echo $p_id; ?>)">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // Modern Empty State Design
                    ?>
                    <div class="col-12 text-center py-5 my-5">
                        <div class="p-5 rounded-4 border" style="background: #FFFFFF; border-style: dashed !important; border-color: rgba(156,85,33,0.3) !important;">
                            <i class="bi bi-box-seam display-2 d-block mb-3" style="color: var(--brand-accent);"></i>
                            <h4 class="fw-bold" style="color: var(--text-dark); font-family: 'Poppins', sans-serif;">Coming Soon!</h4>
                            <p class="text-muted" style="font-family: 'Inter', sans-serif;">We are currently updating products in the <strong>"<?php echo $current_category_name; ?>"</strong> collection.</p>
                            <a href="<?php echo $site; ?>product.php" class="btn text-white px-4 py-2 mt-3 rounded-pill" style="background: var(--text-dark); font-weight: 500;">Explore Other Categories</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>
    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ================= SAFE AJAX WITH TOAST POPUP ================= -->
    <script>
        // ADD TO CART FUNCTION
        function addToCart(productId, variationId = 0, qty = 1) {
            $.ajax({
                url: '<?php echo $site; ?>cart_action.php',
                type: 'POST',
                data: {
                    action: 'add_to_cart',
                    product_id: productId,
                    variation_id: variationId,
                    quantity: qty
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        $('.cart-count').text(response.cart_count);
                        showToast("Added to Cart!", "Item successfully added to your basket.", "success");
                    } else {
                        showToast("Action Failed", response.message, "error");
                    }
                },
                error: function() {
                    showToast("System Error", "Could not connect to the server.", "error");
                }
            });
        }

        // BUY NOW FUNCTION
        function buyNow(productId, variationId = 0, qty = 1) {
            $.ajax({
                url: '<?php echo $site; ?>cart_action.php',
                type: 'POST',
                data: {
                    action: 'buy_now',
                    product_id: productId,
                    variation_id: variationId,
                    quantity: qty
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        // Redirect to checkout specifically for Buy Now
                        window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true';
                    } else {
                        showToast("Action Failed", response.message, "error");
                    }
                },
                error: function () {
                    showToast("System Error", "Could not connect to the server.", "error");
                }
            });
        }
    </script>
</body>

</html>