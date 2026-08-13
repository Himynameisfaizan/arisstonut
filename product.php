<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database context mapping structure load line 1
include('config/connect.php');

// 1. Search Query Param Catch aur Sanitize karna
$search_query = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = $conn->real_escape_string(trim($_GET['search']));
}

// 2. Dynamic SQL Query Execution Setup
if (!empty($search_query)) {
    $product_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url 
                      FROM products 
                      WHERE status = 1 
                      AND (pro_name LIKE '%$search_query%' OR description LIKE '%$search_query%' OR meta_title LIKE '%$search_query%') 
                      ORDER BY id DESC";
} else {
    $product_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url 
                      FROM products 
                      WHERE status = 1 
                      ORDER BY id DESC";
}

$product_result = $conn->query($product_query);
?>
<body>

    <?php include('inc/header.php'); ?>

    <section class="quick-products">
        <div class="container">

            <?php if (!empty($search_query)): ?>
                <div class="mb-5 p-4 rounded-4 text-start" style="background:#FFF8F0; border: 2px dashed #F5E6D3;">
                    <h3 class="text-brown fw-bold mb-1">Search Results for: <span class="text-muted">"<?php echo htmlspecialchars($search_query); ?>"</span></h3>
                    <p class="text-muted small mb-3">Found <?php echo $product_result ? $product_result->num_rows : 0; ?> organic makhana varieties matching your parameters.</p>
                    <a href="<?php echo $site; ?>product.php" class="btn btn-sm text-white px-4 py-2" style="background:#8B4513; border-radius:20px; font-size:0.85rem; text-decoration:none;">Clear Filter & View All <i class="bi bi-x-circle ms-1"></i></a>
                </div>
            <?php else: ?>
                <div class="section-title">
                    <h2>Our Premium Collection</h2>
                    <p>Explore our healthy, authentic and delicious Makhana range</p>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php
                if ($product_result && $product_result->num_rows > 0) {
                    while ($row = $product_result->fetch_assoc()) {
                        $p_id = $row['id'];
                        $p_name = htmlspecialchars($row['pro_name']);
                        $p_price = htmlspecialchars($row['selling_price']);
                        $p_weight = htmlspecialchars($row['qty']);
                        $p_slug = htmlspecialchars($row['slug_url']);

                        // Absolute dynamic image logic setup to prevent sub-folder directory breaking
                        $p_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($row['pro_img']);
                ?>

                        <div class="col-md-6 col-lg-4">
                            <div class="product-card">
                                <div class="product-image position-relative">

                                    <?php
                                    $is_wished = (isset($_SESSION['wishlist']) && in_array($p_id, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
                                    ?>
                                    <span class="position-absolute" style="top:15px; left:15px; background:#fff; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 4px 10px rgba(0,0,0,0.05); z-index:10;" onclick="handleWishlist(<?php echo $p_id; ?>, this)">
                                        <i class="bi <?php echo $is_wished; ?>" style="font-size:1.1rem;"></i>
                                    </span>

                                    <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" style="display:flex; width:100%; height:100%; align-items:center; justify-content:center;">
                                        <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>">
                                    </a>
                                    <span class="product-badge">Best Seller</span>
                                </div>
                                <div class="product-info">
                                    <h5 class="product-name">
                                        <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" class="text-decoration-none text-dark hover-brown">
                                            <?php echo $p_name; ?>
                                        </a>
                                    </h5>
                                    <div class="product-price">₹<?php echo $p_price; ?> <span class="weight">/ <?php echo $p_weight; ?>g</span></div>

                                    <button class="btn btn-add-cart" onclick="addToCart(<?php echo $p_id; ?>)">
                                        Add to Cart <i class="bi bi-cart-plus ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                } else {
                    // Empty Search Fallback State Interface Block Card
                    ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search-heart text-muted display-1 d-block mb-3"></i>
                        <h4 class="fw-bold text-muted">No Delicious Makhana Found!</h4>
                        <p class="text-muted small">We couldn't find anything matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>". Try another keyword or clear filter.</p>
                        <a href="<?php echo $site; ?>product.php" class="btn text-white px-4 py-2 mt-3" style="background:#8B4513; border-radius:20px; text-decoration:none; font-size:0.9rem;">View All Premium Products</a>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>