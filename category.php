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
$prod_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url FROM `products` WHERE `pro_cate` = '$current_cate_id' AND `status` = 1 AND `is_disabled` = 0 ORDER BY `id` DESC";
$products_result = $conn->query($prod_query);

// ==========================================
// HEADER & BREADCRUMB (Outside HEAD tag)
// ==========================================
$pageTitle = $current_category_name;
$parentName = "All Categories";
$parentUrl = $site . "product.php";
include('inc/header.php'); 
include('inc/breadcrumb.php'); 
?>

<!-- ================= PREMIUM PRODUCT CARD CSS ================= -->
<style>
    :root {
        --cat-bg: #FCFAF8;
        --card-bg: #FFFFFF;
        --text-dark: #2C1E16;
        --text-muted: #6B5B53;
        --brand-accent: #9C5521;
    }

    .category-products-wrapper {
        background-color: var(--cat-bg);
        padding: 80px 0 120px 0;
        min-height: 60vh;
    }

    .video-prod-card {
        background: var(--card-bg);
        border-radius: 24px;
        padding: 24px 20px;
        height: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .video-prod-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(156, 85, 33, 0.08);
        border-color: rgba(156, 85, 33, 0.15);
    }

    .v-wish-btn {
        position: absolute; top: 18px; right: 18px; background: #FFFFFF; width: 36px; height: 36px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06); cursor: pointer; z-index: 3; color: #777; transition: all 0.2s ease;
    }
    .v-wish-btn:hover { transform: scale(1.1); color: #E02020; }

    .v-img-box {
        height: 210px; width: 100%; display: flex; align-items: center; justify-content: center;
        position: relative; background: #F8F5F0; border-radius: 16px; margin-bottom: 20px; overflow: hidden;
    }

    .v-img-box img { max-height: 85%; max-width: 85%; object-fit: contain; transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    .video-prod-card:hover .v-img-box img { transform: rotate(360deg) scale(1.1); }

    .v-rating { font-size: 0.75rem; color: #F39C12; margin-bottom: 6px; text-align: center; }
    
    .v-title {
        font-family: 'Poppins', sans-serif; font-size: 1.15rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: 4px; text-align: center; text-decoration: none; display: -webkit-box;
        -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
    }
    .v-title:hover { color: var(--brand-accent); }

    .v-weight { font-family: 'Inter', sans-serif; font-size: 0.82rem; color: var(--text-muted); text-align: center; margin-bottom: 15px; }

    .v-bottom-section { margin-top: auto; display: flex; flex-direction: column; gap: 12px; }
    
    .v-price { font-family: 'Poppins', sans-serif; font-size: 1.3rem; font-weight: 800; color: var(--text-dark); text-align: center; }

    .v-action-buttons { display: flex; gap: 10px; width: 100%; }

    .v-btn-cart, .v-btn-buy {
        flex: 1; padding: 10px 0; border-radius: 50px; font-family: 'Inter', sans-serif; font-size: 0.85rem;
        font-weight: 600; text-align: center; cursor: pointer; transition: all 0.3s ease; border: 1px solid #D2B48C;
    }

    .v-btn-cart { background: #FFFFFF; color: var(--text-dark); }
    .v-btn-cart:hover { background: #FDF4E6; border-color: var(--brand-accent); }

    .v-btn-buy { background: #5C2C16; color: #FFFFFF; border-color: #5C2C16; }
    .v-btn-buy:hover { background: var(--brand-accent); border-color: var(--brand-accent); transform: translateY(-2px); }
</style>

<main class="category-products-wrapper">
    <div class="container">
        
        <div class="row g-4 justify-content-center">
            <?php
            if ($products_result && $products_result->num_rows > 0) {
                while ($product = $products_result->fetch_assoc()) {
                    $p_id = intval($product['id']);
                    $p_name = htmlspecialchars($product['pro_name']);
                    $p_price = htmlspecialchars($product['selling_price']);
                    
                    // 🔥 PHP 8+ SAFE NULL HANDLING FIX 🔥
                    $p_qty = htmlspecialchars($product['qty'] ?? '100g'); 
                    
                    $p_slug = htmlspecialchars($product['slug_url']);
                    $p_img = !empty($product['pro_img']) ? $site . 'admin/assets/img/uploads/' . htmlspecialchars($product['pro_img']) : $site . 'assets/images/hero.webp';

                    $seo_detail_url = $site . "product/" . $p_slug;
                    $is_wished = (isset($_SESSION['wishlist']) && in_array($p_id, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
                    ?>
                    
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="video-prod-card">
                            
                            <div class="v-wish-btn" onclick="handleWishlist(<?php echo $p_id; ?>, this)">
                                <i class="bi <?php echo $is_wished; ?>"></i>
                            </div>

                            <a href="<?php echo $seo_detail_url; ?>" class="v-img-box">
                                <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>">
                            </a>

                            <div class="v-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <span class="text-muted ms-1">(4.9)</span>
                            </div>

                            <a href="<?php echo $seo_detail_url; ?>" class="v-title" title="<?php echo $p_name; ?>">
                                <?php echo $p_name; ?>
                            </a>
                            <div class="v-weight">Net Wt: <?php echo !empty($p_qty) ? $p_qty : '100g'; ?></div>

                            <div class="v-bottom-section">
                                <div class="v-price">₹<?php echo $p_price; ?></div>
                                <div class="v-action-buttons">
                                    <button class="v-btn-cart" onclick="addToCart(<?php echo $p_id; ?>)">Cart</button>
                                    <button class="v-btn-buy" onclick="buyNow(<?php echo $p_id; ?>)">Buy Now</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php
                }
            } else {
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
    function addToCart(productId, variationId = 0, qty = 1) {
        $.ajax({
            url: '<?php echo $site; ?>cart_action.php',
            type: 'POST',
            data: { action: 'add_to_cart', product_id: productId, variation_id: variationId, quantity: qty },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    $('.cart-count').text(response.cart_count);
                    showToast("Added to Cart!", "Item successfully added to your basket.", "success");
                } else {
                    showToast("Action Failed", response.message, "error");
                }
            },
            error: function() { showToast("System Error", "Could not connect to the server.", "error"); }
        });
    }

    function buyNow(productId, variationId = 0, qty = 1) {
        $.ajax({
            url: '<?php echo $site; ?>cart_action.php',
            type: 'POST',
            data: { action: 'buy_now', product_id: productId, variation_id: variationId, quantity: qty },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true';
                } else { showToast("Action Failed", response.message, "error"); }
            },
            error: function () { showToast("System Error", "Could not connect to the server.", "error"); }
        });
    }
</script>