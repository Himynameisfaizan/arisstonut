<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php');

$search_query = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = $conn->real_escape_string(trim($_GET['search']));
}

// PAGINATION LOGIC START
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
// PAGINATION LOGIC END
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - AristoNut</title>
    <style>
        /* Modern Button Styles */
        .modern-btn-add {
            background-color: #FFF8F0;
            color: #8B4513;
            border: 1.5px solid #8B4513;
            border-radius: 20px;
            padding: 8px 5px;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.3s;
        }
        .modern-btn-add:hover {
            background-color: #8B4513;
            color: #FFFFFF;
        }
        .modern-btn-buy {
            background-color: #8B4513;
            color: #FFFFFF;
            border: 1.5px solid #8B4513;
            border-radius: 20px;
            padding: 8px 5px;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.3s;
        }
        .modern-btn-buy:hover {
            background-color: #6D3410;
            border-color: #6D3410;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(139, 69, 19, 0.2);
        }
    </style>
</head>
<body>

    <?php include('inc/header.php'); ?>

    <section class="quick-products py-5 mb-5">
        <div class="container">

            <?php if (!empty($search_query)): ?>
                <div class="mb-5 p-4 rounded-4 text-start" style="background:#FFF8F0; border: 2px dashed #F5E6D3;">
                    <h3 class="text-brown fw-bold mb-1">Search Results for: <span class="text-muted">"<?php echo htmlspecialchars($search_query); ?>"</span></h3>
                    <p class="text-muted small mb-3">Found <?php echo $total_rows; ?> organic makhana varieties matching your parameters.</p>
                    <a href="<?php echo $site; ?>product.php" class="btn btn-sm text-white px-4 py-2" style="background:#8B4513; border-radius:20px; font-size:0.85rem; text-decoration:none;">Clear Filter & View All <i class="bi bi-x-circle ms-1"></i></a>
                </div>
            <?php else: ?>
                <div class="section-title text-center mb-5">
                    <h2 class="fw-bold text-brown">Browse Our Products</h2>
                    <p class="text-muted">Explore our wide range of makhana products from the best manufacturer</p>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php
                if ($product_result && $product_result->num_rows > 0) {
                    while ($row = $product_result->fetch_assoc()) {
                        $p_id = $row['id'];
                        $p_name = htmlspecialchars($row['pro_name']);
                        $p_price = htmlspecialchars($row['selling_price']);
                        $p_weight = htmlspecialchars($row['qty']);
                        $p_slug = htmlspecialchars($row['slug_url']);

                        $p_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($row['pro_img']);
                ?>
                        <!-- VIDEO-STYLE CARD STRUCTURE -->
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="modern-product-card">
                                
                                <?php
                                $is_wished = (isset($_SESSION['wishlist']) && in_array($p_id, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
                                ?>
                                <span class="modern-wishlist-btn" onclick="handleWishlist(<?php echo $p_id; ?>, this)">
                                    <i class="bi <?php echo $is_wished; ?>" style="font-size:1.1rem;"></i>
                                </span>

                                <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" class="text-decoration-none w-100">
                                    <div class="modern-img-circle">
                                        <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>">
                                    </div>
                                </a>

                                <div class="modern-product-info">
                                    <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" class="text-decoration-none">
                                        <h5 class="modern-product-name text-truncate" title="<?php echo $p_name; ?>"><?php echo $p_name; ?></h5>
                                    </a>
                                    
                                    <p class="modern-product-weight">Net Wt: <?php echo !empty($p_weight) ? $p_weight : '100'; ?>g</p>
                                    
                                    <div class="modern-product-price">₹<?php echo $p_price; ?></div>

                                    <!-- NAYA CODE: TWO BUTTONS (CART & BUY NOW) -->
                                    <div class="d-flex gap-2 w-100 mt-auto">
                                        <button class="modern-btn-add w-50" onclick="addToCart(<?php echo $p_id; ?>)">
                                            Cart
                                        </button>
                                        <button class="modern-btn-buy w-50" onclick="buyNow(<?php echo $p_id; ?>)">
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

            <!-- PAGINATION UI BLOCK -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Product Page Navigation">
                    <ul class="pagination justify-content-center custom-pagination">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($search_query) ? '&search='.$search_query : ''; ?>" aria-label="Previous">
                                <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                            </a>
                        </li>
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search_query) ? '&search='.$search_query : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($search_query) ? '&search='.$search_query : ''; ?>" aria-label="Next">
                                <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
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
    
    <!-- NEW: BUY NOW SCRIPT -->
    <script>
    function buyNow(productId, variationId = 0, qty = 1) {
        $.ajax({
            url: '<?php echo $site; ?>cart_action.php',
            type: 'POST',
            data: {
                action: 'buy_now', // FIX: Yahan add_to_cart ko buy_now kar diya gaya hai
                product_id: productId,
                variation_id: variationId,
                quantity: qty
            },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    // FIX: Checkout page par parameter pass kar rahe hain
                    window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true';
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function() {
                alert("System Error: Could not connect to the server.");
            }
        });
    }
</script>
</body>
</html>