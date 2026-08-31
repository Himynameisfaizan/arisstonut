<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php'); // Database & Global $site Config Layer

// URL Parameter Validation
if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $conn->real_escape_string($_GET['slug']);

    $query = $conn->query("SELECT * FROM products WHERE slug_url = '$slug' AND status = 1 LIMIT 1");

    if ($query && $query->num_rows > 0) {
        $product = $query->fetch_assoc();

        $p_id = $product['id'];
        $p_name = htmlspecialchars($product['pro_name']);
        $p_mrp = $product['mrp'];
        $p_price = $product['selling_price'];
        $p_cate = $product['pro_cate'];

        $p_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($product['pro_img']);
        $p_short_desc = $product['short_desc'];
        $p_long_desc = $product['description'];

        $seo_title = htmlspecialchars($product['meta_title']);
        $seo_desc = htmlspecialchars($product['meta_desc']);
        $seo_keywords = htmlspecialchars($product['meta_key']);

        // --- Fetch Variations ---
        $variations = [];
        $var_query = $conn->query("SELECT * FROM product_variations WHERE product_id = '$p_id' ORDER BY id ASC");
        if ($var_query && $var_query->num_rows > 0) {
            while ($row = $var_query->fetch_assoc()) {
                $variations[] = $row;
            }
        }
        $variations_json = json_encode($variations);

        // --- Fetch Related Products ---
        $related_query = $conn->query("SELECT id, pro_name, selling_price, qty, pro_img, slug_url FROM products WHERE pro_cate = '$p_cate' AND id != '$p_id' AND status = 1 ORDER BY id DESC LIMIT 4");

    } else {
        header("Location: " . $site . "index.php");
        exit();
    }
} else {
    header("Location: " . $site . "index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $seo_title; ?> - AristoNut</title>
    <meta name="description" content="<?php echo $seo_desc; ?>">
    <meta name="keywords" content="<?php echo $seo_keywords; ?>">

    <?php 
    // SETUP DYNAMIC BREADCRUMB
    $pageTitle = $p_name;
    $parentName = "All Products";
    $parentUrl = $site . "product.php";
    include('inc/header.php'); 
    ?>
</head>

<body>

    <?php include('inc/breadcrumb.php'); ?>

    <main class="product-detail-wrapper container">
        
        <div class="row g-5">
            <!-- ================= LEFT: STICKY IMAGE GALLERY ================= -->
            <div class="col-lg-5">
                <div class="sticky-gallery">
                    <div class="main-img-box" id="magnify-container-node">
                        <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>" id="magnify-target-img">
                    </div>

                    <?php if (!empty($variations)): ?>
                        <div class="thumb-gallery" id="variation-thumbnails">
                            <?php foreach ($variations as $index => $var): ?>
                                <?php $thumb_img = !empty($var['image_path']) ? $site . 'admin/assets/img/uploads/' . $var['image_path'] : $p_img; ?>
                                <img src="<?php echo $thumb_img; ?>"
                                    class="var-thumb <?php echo $index === 0 ? 'active-thumb' : ''; ?>"
                                    data-index="<?php echo $index; ?>" alt="<?php echo $var['weight_size']; ?>"
                                    title="<?php echo $var['weight_size']; ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ================= RIGHT: PRODUCT INFO & ACTION BAR ================= -->
            <div class="col-lg-7">
                <div class="product-info-panel">
                    
                    <div class="stock-badge" id="stock-badge">
                        <i class="bi bi-shield-check me-1"></i> In Stock
                    </div>
                    
                    <h1 class="product-title"><?php echo $p_name; ?></h1>

                    <div class="review-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <span>(124 Verified Reviews)</span>
                    </div>

                    <div class="price-wrap">
                        <div class="d-flex align-items-baseline">
                            <span class="current-price" id="display-price">₹<?php echo $p_price; ?></span>
                            <span class="total-price-hint" id="display-total-price"></span>
                        </div>
                        <span class="tax-inclusive"><i class="bi bi-tags-fill me-1"></i> Inclusive of all regional taxes</span>
                    </div>

                    <?php if (!empty($variations)): ?>
                        <div class="mb-4">
                            <h4 class="section-label">Select Pack Size:</h4>
                            <div class="d-flex flex-wrap" id="weight-options">
                                <?php foreach ($variations as $index => $var): ?>
                                    <input type="radio" class="variation-radio" name="pack_size"
                                        id="var_<?php echo $var['id']; ?>" value="<?php echo $var['id']; ?>" <?php echo $index === 0 ? 'checked' : ''; ?> data-index="<?php echo $index; ?>">
                                    <label class="variation-label" for="var_<?php echo $var['id']; ?>">
                                        <?php echo htmlspecialchars($var['weight_size']); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- THE FIX: ACTION BAR WITH BOTH BUTTONS -->
                    <div class="action-bar">
                        <h4 class="section-label mb-3">Quantity:</h4>
                        
                        <div class="action-grid">
                            <!-- QTY -->
                            <div class="qty-control">
                                <button type="button" class="qty-btn" id="btn-qty-minus"><i class="bi bi-dash"></i></button>
                                <input type="text" class="qty-input" id="product-qty" value="1" readonly>
                                <button type="button" class="qty-btn" id="btn-qty-plus"><i class="bi bi-plus"></i></button>
                            </div>

                            <!-- BUTTONS SIDE BY SIDE -->
                            <button type="button" class="btn-action btn-add-cart" id="custom-add-to-cart-btn">
                                <i class="bi bi-bag"></i> Add to Cart
                            </button>
                            
                            <button type="button" class="btn-action btn-buy-now" id="custom-buy-now-btn">
                                <i class="bi bi-lightning-charge-fill"></i> Buy Now
                            </button>
                        </div>

                        <!-- BULK DISCOUNT TABLE -->
                        <div id="bulk-pricing-table-container" class="bulk-table-wrap" style="display: none;">
                            <p class="fw-bold text-success mb-2 small"><i class="bi bi-percent"></i> Bulk Discount Applied!</p>
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Qty</th>
                                        <th>4+ Packs</th>
                                        <th>5+ Packs</th>
                                        <th>6+ Packs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Price</td>
                                        <td id="bp-4">-</td>
                                        <td id="bp-5">-</td>
                                        <td id="bp-6">-</td>
                                    </tr>
                                </tbody>
                            </table>
                            <small class="text-danger fw-bold mt-2 d-block" id="bulk-discount-msg" style="display:none;"></small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ================= EDITORIAL DETAILED OVERVIEW ================= -->
        <div class="editorial-desc">
            <h3 class="desc-heading">Detailed Overview</h3>
            <div class="rich-content">
                <?php echo $p_long_desc; ?>
            </div>
        </div>

        <!-- ================= RELATED PRODUCTS (EXACT INDEX CARD) ================= -->
        <?php if ($related_query && $related_query->num_rows > 0): ?>
            <div class="pt-5 mt-5">
                <h2 class="desc-heading text-center border-0 mb-5">You Might Also Like</h2>
                <div class="row g-4 justify-content-center">
                    <?php while ($r_prod = $related_query->fetch_assoc()): 
                        $rp_id = $r_prod['id'];
                        $rp_name = htmlspecialchars($r_prod['pro_name']);
                        $rp_price = htmlspecialchars($r_prod['selling_price']);
                        $rp_weight = htmlspecialchars($r_prod['qty'] ?? '100g'); 
                        $rp_slug = htmlspecialchars($r_prod['slug_url']);
                        $rp_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($r_prod['pro_img']);
                        $is_wished_rp = (isset($_SESSION['wishlist']) && in_array($rp_id, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
                    ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="video-prod-card">
                                
                                <div class="v-wish-btn" onclick="handleWishlist(<?php echo $rp_id; ?>, this)">
                                    <i class="bi <?php echo $is_wished_rp; ?>"></i>
                                </div>

                                <a href="<?php echo $site; ?>product/<?php echo $rp_slug; ?>" class="v-img-box">
                                    <img src="<?php echo $rp_img; ?>" alt="<?php echo $rp_name; ?>">
                                </a>

                                <div class="v-rating">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                </div>

                                <a href="<?php echo $site; ?>product/<?php echo $rp_slug; ?>" class="v-title" title="<?php echo $rp_name; ?>"><?php echo $rp_name; ?></a>
                                <div class="v-weight">Net Wt: <?php echo $rp_weight; ?></div>

                                <div class="v-bottom-section">
                                    <div class="v-price">₹<?php echo $rp_price; ?></div>
                                    <div class="v-action-buttons">
                                        <button class="v-btn-cart-sm" onclick="addToCart(<?php echo $rp_id; ?>)">Cart</button>
                                        <button class="v-btn-buy-sm" onclick="buyNow(<?php echo $rp_id; ?>)">Buy Now</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- KEEPING YOUR EXACT JS LOGIC FOR VARIATIONS AND AJAX INTACT -->
  <script>
        document.addEventListener("DOMContentLoaded", function () {

            const targetImg = document.getElementById("magnify-target-img");
            const variations = <?php echo !empty($variations_json) ? $variations_json : '[]'; ?>;
            const baseImgUrl = '<?php echo $site . "admin/assets/img/uploads/"; ?>';
            const defaultImg = '<?php echo $p_img; ?>';

            let currentVariation = variations.length > 0 ? variations[0] : null;
            let qty = 1;

            // Thumbnail Click Event
            document.querySelectorAll('.var-thumb').forEach(thumb => {
                thumb.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    const radio = document.querySelectorAll('.variation-radio')[index];
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                });
            });

            function updateUI() {
                if (!currentVariation) return;

                let unitPrice = parseFloat(currentVariation.single_price);
                let discountMsg = "";

                if (qty >= 6 && currentVariation.price_6_plus !== null && parseFloat(currentVariation.price_6_plus) > 0) {
                    unitPrice = parseFloat(currentVariation.price_6_plus); discountMsg = "Super Saver: 6+ Bulk Price Applied! 💥";
                } else if (qty >= 5 && currentVariation.price_5_plus !== null && parseFloat(currentVariation.price_5_plus) > 0) {
                    unitPrice = parseFloat(currentVariation.price_5_plus); discountMsg = "Mega Saver: 5+ Bulk Price Applied! 🔥";
                } else if (qty >= 4 && currentVariation.price_4_plus !== null && parseFloat(currentVariation.price_4_plus) > 0) {
                    unitPrice = parseFloat(currentVariation.price_4_plus); discountMsg = "Smart Saver: 4+ Bulk Price Applied! 🎉";
                }

                let totalPrice = unitPrice * qty;

                document.getElementById('display-price').innerHTML = '₹' + unitPrice.toFixed(2) + ' <span style="font-size: 1rem; color: #6B5B53; font-weight: 500;">(' + currentVariation.weight_size + ')</span>';
                if (qty > 1) document.getElementById('display-total-price').innerText = '(Total: ₹' + totalPrice.toFixed(2) + ')';
                else document.getElementById('display-total-price').innerText = '';

                const msgEl = document.getElementById('bulk-discount-msg');
                if (discountMsg) { msgEl.innerText = discountMsg; msgEl.style.display = 'block'; }
                else { msgEl.style.display = 'none'; }

                const bpContainer = document.getElementById('bulk-pricing-table-container');
                let hasBulk = false;
                if (currentVariation.price_4_plus > 0) { document.getElementById('bp-4').innerText = '₹' + currentVariation.price_4_plus; hasBulk = true; } else { document.getElementById('bp-4').innerText = '-'; }
                if (currentVariation.price_5_plus > 0) { document.getElementById('bp-5').innerText = '₹' + currentVariation.price_5_plus; hasBulk = true; } else { document.getElementById('bp-5').innerText = '-'; }
                if (currentVariation.price_6_plus > 0) { document.getElementById('bp-6').innerText = '₹' + currentVariation.price_6_plus; hasBulk = true; } else { document.getElementById('bp-6').innerText = '-'; }

                bpContainer.style.display = hasBulk ? 'block' : 'none';

                if (currentVariation.image_path && currentVariation.image_path.trim() !== '') {
                    targetImg.src = baseImgUrl + currentVariation.image_path;
                } else { targetImg.src = defaultImg; }

                document.querySelectorAll('.var-thumb').forEach(thumb => {
                    if (thumb.getAttribute('data-index') == variations.indexOf(currentVariation)) {
                        thumb.classList.add('active-thumb');
                    } else { thumb.classList.remove('active-thumb'); }
                });

                const stockBadge = document.getElementById('stock-badge');
                if (parseInt(currentVariation.stock) > 0) {
                    stockBadge.style.background = 'rgba(39, 174, 96, 0.1)';
                    stockBadge.style.color = '#27AE60';
                    stockBadge.innerHTML = '<i class="bi bi-shield-check me-1"></i> In Stock';
                } else {
                    stockBadge.style.background = 'rgba(224, 32, 32, 0.1)';
                    stockBadge.style.color = '#E02020';
                    stockBadge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Out of Stock';
                }
            }

            document.querySelectorAll('.variation-radio').forEach(radio => {
                radio.addEventListener('change', function () {
                    const index = this.getAttribute('data-index');
                    currentVariation = variations[index];
                    qty = 1; document.getElementById('product-qty').value = qty;
                    updateUI();
                });
            });

            document.getElementById('btn-qty-plus').addEventListener('click', () => {
                if (currentVariation && qty < parseInt(currentVariation.stock)) {
                    qty++; document.getElementById('product-qty').value = qty; updateUI();
                }
            });

            document.getElementById('btn-qty-minus').addEventListener('click', () => {
                if (qty > 1) { qty--; document.getElementById('product-qty').value = qty; updateUI(); }
            });

            // ==========================================
            // MAIN PRODUCT: ADD TO CART (SHOWS TOAST)
            // ==========================================
            document.getElementById('custom-add-to-cart-btn').addEventListener('click', () => {
                if (currentVariation && parseInt(currentVariation.stock) > 0) {
                    $.ajax({
                        url: '<?php echo $site; ?>cart_action.php',
                        type: 'POST',
                        data: { action: 'add_to_cart', product_id: <?php echo $p_id; ?>, variation_id: currentVariation.id, quantity: qty },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                $('.cart-count').text(response.cart_count);
                                // Toast for Main Product
                                showToast("Added to Cart!", qty + " Pack of " + currentVariation.weight_size + " is in your basket.", "success");
                            } else { 
                                showToast("Action Failed", response.message, "error"); 
                            }
                        }
                    });
                } else { alert('Cannot add out of stock item to basket.'); }
            });

            // ==========================================
            // MAIN PRODUCT: BUY NOW (REDIRECTS TO CHECKOUT)
            // ==========================================
            document.getElementById('custom-buy-now-btn').addEventListener('click', () => {
                if (currentVariation && parseInt(currentVariation.stock) > 0) {
                    const btn = document.getElementById('custom-buy-now-btn');
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                    
                    $.ajax({
                        url: '<?php echo $site; ?>cart_action.php',
                        type: 'POST',
                        data: { action: 'buy_now', product_id: <?php echo $p_id; ?>, variation_id: currentVariation.id, quantity: qty },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') { 
                                window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true'; 
                            } else { 
                                showToast("Action Failed", response.message, "error"); 
                                btn.innerHTML = '<i class="bi bi-lightning-charge-fill"></i> Buy Now';
                            }
                        }
                    });
                } else { alert('Cannot buy out of stock item.'); }
            });

            updateUI();
        });

        // ==========================================
        // RELATED PRODUCTS: ADD TO CART (SHOWS TOAST)
        // ==========================================
        window.addToCart = function(productId, variationId = 0, qty = 1) {
            $.ajax({
                url: '<?php echo $site; ?>cart_action.php', 
                type: 'POST',
                data: { action: 'add_to_cart', product_id: productId, variation_id: variationId, quantity: qty },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('.cart-count').text(response.cart_count);
                        showToast("Added to Cart!", "Item successfully added to your basket.", "success");
                    } else { 
                        showToast("Action Failed", response.message, "error"); 
                    }
                }
            });
        }

        // ==========================================
        // RELATED PRODUCTS: BUY NOW (REDIRECTS TO CHECKOUT)
        // ==========================================
        window.buyNow = function(productId, variationId = 0, qty = 1) {
            $.ajax({
                url: '<?php echo $site; ?>cart_action.php', 
                type: 'POST',
                data: { action: 'buy_now', product_id: productId, variation_id: variationId, quantity: qty },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') { 
                        window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true'; 
                    } else { 
                        showToast("Action Failed", response.message, "error"); 
                    }
                }
            });
        }
    </script>
</body>
</html>