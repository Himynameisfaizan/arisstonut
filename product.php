<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php');

$search_query = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = $conn->real_escape_string(trim($_GET['search']));
}

$limit = 12; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

if (!empty($search_query)) {
    $count_query = "SELECT COUNT(*) as total FROM products WHERE status = 1 AND (pro_name LIKE '%$search_query%' OR description LIKE '%$search_query%' OR meta_title LIKE '%$search_query%')";
    
    $product_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url 
                      FROM products 
                      WHERE status = 1 
                      AND (pro_name LIKE '%$search_query%' OR description LIKE '%$search_query%' OR meta_title LIKE '%$search_query%') 
                      ORDER BY id DESC LIMIT $limit OFFSET $offset";
} else {
    $count_query = "SELECT COUNT(*) as total FROM products WHERE status = 1";
    
    $product_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url 
                      FROM products 
                      WHERE status = 1 
                      ORDER BY id DESC LIMIT $limit OFFSET $offset";
}

$count_result = $conn->query($count_query);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit); 

$product_result = $conn->query($product_query);
?>

<?php 
// BREADCRUMB PROPS
$pageTitle = !empty($search_query) ? "Search Results" : "All Products";

include('inc/header.php'); 
include('inc/breadcrumb.php'); 
?>


<section class="products-listing-section">
    <div class="container">

        <!-- Search Alert UI -->
        <?php if (!empty($search_query)): ?>
            <div class="mb-5 p-4 rounded-4" style="background:#FFFFFF; border: 1px solid rgba(156, 85, 33, 0.2); box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <h3 class="fw-bold mb-2" style="color: var(--text-dark); font-family: 'Poppins', sans-serif;">Search Results for: <span style="color: var(--brand-accent);">"<?php echo htmlspecialchars($search_query); ?>"</span></h3>
                <p class="text-muted small mb-4" style="font-family: 'Inter', sans-serif; font-size: 1rem;">Found <?php echo $total_rows; ?> premium makhana products matching your search.</p>
                <a href="<?php echo $site; ?>product.php" class="btn text-white px-4 py-2" style="background:var(--text-dark); border-radius:50px; font-size:0.9rem; font-weight: 600;">Clear Filter & View All <i class="bi bi-x-circle ms-1"></i></a>
            </div>
        <?php endif; ?>

        <!-- Products Grid -->
        <div class="row g-4 justify-content-center">
            <?php
            if ($product_result && $product_result->num_rows > 0) {
                while ($row = $product_result->fetch_assoc()) {
                    $p_id = $row['id'];
                    $p_name = htmlspecialchars($row['pro_name']);
                    $p_price = htmlspecialchars($row['selling_price']);
                    $p_weight = htmlspecialchars($row['qty']);
                    $p_slug = htmlspecialchars($row['slug_url']);

                    $p_img = !empty($row['pro_img']) ? $site . 'admin/assets/img/uploads/' . htmlspecialchars($row['pro_img']) : $site . 'assets/images/hero.webp';
                    
                    $is_wished = (isset($_SESSION['wishlist']) && in_array($p_id, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
            ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="video-prod-card">
                            
                            <!-- Wishlist Toggle -->
                            <div class="v-wish-btn" onclick="handleWishlist(<?php echo $p_id; ?>, this)">
                                <i class="bi <?php echo $is_wished; ?>"></i>
                            </div>

                            <!-- Product Image with 360 Hover Rotation -->
                            <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" class="v-img-box">
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
                            <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" class="v-title" title="<?php echo $pname; ?>">
                                <?php echo $p_name; ?>
                            </a>
                            <div class="v-weight">Net Wt: <?php echo !empty($p_weight) ? $p_weight : '100g'; ?></div>

                            <!-- Price & Side-by-Side Cart/Buy Now Buttons -->
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
            ?>
                <!-- Modern Empty State -->
                <div class="col-12 text-center py-5 my-5">
                    <div class="p-5 rounded-4 border" style="background: #FFFFFF; border-style: dashed !important; border-color: rgba(156,85,33,0.3) !important;">
                        <i class="bi bi-search-heart display-2 d-block mb-3" style="color: var(--brand-accent);"></i>
                        <h4 class="fw-bold" style="color: var(--text-dark); font-family: 'Poppins', sans-serif;">No Delicious Makhana Found!</h4>
                        <p class="text-muted" style="font-family: 'Inter', sans-serif;">We couldn't find anything matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>". Try another keyword.</p>
                        <a href="<?php echo $site; ?>product.php" class="btn text-white px-4 py-2 mt-3 rounded-pill" style="background: var(--text-dark); font-weight: 500;">View All Premium Products</a>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <!-- Dynamic Circular Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Product Page Navigation">
                <ul class="pagination justify-content-center custom-pagination">

                    <!-- Previous Button -->
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($search_query) ? '&search='.$search_query : ''; ?>" aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search_query) ? '&search='.$search_query : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($search_query) ? '&search='.$search_query : ''; ?>" aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>

                </ul>
            </nav>
        <?php endif; ?>

    </div>
</section>

<?php include('inc/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- BUY NOW SCRIPT (Keep intact) -->
<script>
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
            success: function(response) {
                if(response.status === 'success') { 
                    window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true'; 
                } else { 
                    showToast("Action Failed", response.message, "error"); 
                }
            },
            error: function() {
                showToast("System Error", "Could not connect to the server.", "error");
            }
        });
    }

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
</script>