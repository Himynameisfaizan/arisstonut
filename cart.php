<?php
session_start();
include('config/connect.php');

$pageTitle = "Shopping Cart";
include('inc/header.php'); 
include('inc/breadcrumb.php'); 
?>

<!-- ================= PREMIUM CART UI ================= -->
<style>
    :root {
        --cart-bg: #FCFAF8;
        --cart-card-bg: #FFFFFF;
        --cart-dark: #2C1E16;
        --cart-muted: #6B5B53;
        --cart-accent: #9C5521;
        --cart-light: #FFF0E5;
        --cart-danger: #E02020;
    }

    body { background-color: var(--cart-bg); }

    .cart-page-wrapper {
        padding: 60px 0 100px 0;
    }

    /* Premium Box Styling */
    .cart-box {
        background: var(--cart-card-bg);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.02);
    }

    /* Header Row */
    .cart-header-row {
        display: flex;
        align-items: center;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 2px dashed rgba(156, 85, 33, 0.15);
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: var(--cart-muted);
        font-size: 0.95rem;
    }

    /* Individual Cart Item */
    .cart-item-row {
        display: flex;
        align-items: center;
        padding: 25px 0;
        border-bottom: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }
    .cart-item-row:last-child { border-bottom: none; padding-bottom: 0; }
    
    .cart-img-box {
        width: 90px;
        height: 90px;
        background: #F9F6F0;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        padding: 10px; flex-shrink: 0;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .cart-img-box img { max-width: 100%; max-height: 100%; object-fit: contain; mix-blend-mode: multiply; }

    .cart-product-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: var(--cart-dark);
        font-size: 1.1rem;
        margin-bottom: 5px;
        line-height: 1.3;
    }
    .cart-product-meta { font-family: 'Inter', sans-serif; font-size: 0.85rem; color: var(--cart-muted); }

    .cart-price-col { font-family: 'Inter', sans-serif; font-weight: 600; color: var(--cart-muted); font-size: 1.05rem; }
    .cart-subtotal-col { font-family: 'Poppins', sans-serif; font-weight: 700; color: var(--cart-dark); font-size: 1.15rem; }

    /* Quantity Control */
    .premium-qty-control {
        display: inline-flex;
        align-items: center;
        background: #FFFFFF;
        border: 1px solid rgba(156, 85, 33, 0.2);
        border-radius: 50px;
        padding: 4px 6px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    }
    .premium-qty-control button {
        background: var(--cart-light);
        border: none;
        color: var(--cart-accent);
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; cursor: pointer; transition: 0.3s;
    }
    .premium-qty-control button:hover { background: var(--cart-accent); color: #FFF; }
    .premium-qty-control span {
        font-family: 'Poppins', sans-serif;
        font-weight: 700; color: var(--cart-dark);
        min-width: 35px; text-align: center; font-size: 0.95rem;
    }

    /* Trash Button */
    .btn-trash-action {
        background: rgba(224, 32, 32, 0.08);
        color: var(--cart-danger);
        border: none;
        width: 40px; height: 40px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; cursor: pointer; transition: 0.3s;
    }
    .btn-trash-action:hover { background: var(--cart-danger); color: #FFF; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(224, 32, 32, 0.2); }

    /* Cart Summary Sidebar */
    .cart-summary-box {
        background: var(--cart-light);
        border-radius: 24px;
        padding: 40px;
        position: sticky;
        top: 100px;
    }
    .summary-title { font-family: 'Poppins', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--cart-dark); margin-bottom: 25px; border-bottom: 2px dashed rgba(156, 85, 33, 0.15); padding-bottom: 15px; }
    
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-family: 'Inter', sans-serif; font-size: 1rem; color: var(--cart-muted); }
    .summary-row.total { font-family: 'Poppins', sans-serif; font-size: 1.35rem; font-weight: 800; color: var(--cart-dark); margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.06); }
    
    .btn-checkout {
        background: var(--cart-accent);
        color: #FFFFFF;
        border: none;
        width: 100%;
        padding: 16px;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.05rem;
        transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        margin-top: 30px;
    }
    .btn-checkout:hover { background: #7A4219; transform: translateY(-3px); box-shadow: 0 10px 25px rgba(156, 85, 33, 0.3); color: #FFF; }

    /* Empty Cart State */
    .empty-cart-wrap { background: #FFFFFF; border-radius: 24px; padding: 80px 20px; text-align: center; border: 1px dashed rgba(156, 85, 33, 0.2); }
    .empty-icon { font-size: 4rem; color: var(--cart-accent); margin-bottom: 20px; opacity: 0.8; }

    @media (max-width: 991px) {
        .cart-box, .cart-summary-box { padding: 25px; }
        .cart-header-row { display: none; } /* Hide headers on mobile */
        .cart-item-row { flex-wrap: wrap; position: relative; padding: 20px 0; }
        .cart-img-box { width: 70px; height: 70px; }
        .mobile-label { display: block; font-size: 0.75rem; color: var(--cart-muted); margin-bottom: 3px; }
        .btn-trash-action { position: absolute; top: 15px; right: 0; width: 35px; height: 35px; }
        .cart-price-col, .cart-subtotal-col { text-align: left !important; margin-top: 15px; }
    }
</style>

<main class="cart-page-wrapper container">
    <div class="row g-5" id="cart-main-container" <?php echo empty($_SESSION['cart']) ? 'style="display:none;"' : ''; ?>>
        
        <!-- ================= CART ITEMS LIST ================= -->
        <div class="col-lg-8">
            <div class="cart-box">
                
                <!-- Desktop Table Header -->
                <div class="cart-header-row d-none d-md-flex row">
                    <div class="col-md-5">Product Details</div>
                    <div class="col-md-2 text-center">Unit Price</div>
                    <div class="col-md-2 text-center">Quantity</div>
                    <div class="col-md-2 text-center">Subtotal</div>
                    <div class="col-md-1 text-end"><i class="bi bi-trash"></i></div>
                </div>

                <!-- Cart Items -->
                <div id="cart-tbody">
                    <?php
                    $grand_total = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $cart_key => $item) {
                            
                            $p_id = $item['id'];
                            $v_id = isset($item['variation_id']) ? $item['variation_id'] : 0;
                            $qty = $item['quantity'];

                            $query = $conn->query("SELECT pro_name, pro_img FROM products WHERE id = '$p_id'");
                            if ($query && $query->num_rows > 0) {
                                $p = $query->fetch_assoc();
                                
                                $item_name = htmlspecialchars($p['pro_name']);
                                $item_img = $p['pro_img'];
                                $unit_price = 0;
                                $bulk_badge = "";

                                if ($v_id > 0) {
                                    $var_query = $conn->query("SELECT * FROM product_variations WHERE id = '$v_id'");
                                    if($var_query && $var_query->num_rows > 0) {
                                        $v_data = $var_query->fetch_assoc();
                                        
                                        $item_name .= " <span class='text-muted fs-6 d-inline-block'>(" . htmlspecialchars($v_data['weight_size']) . ")</span>";
                                        if (!empty($v_data['image_path'])) { $item_img = $v_data['image_path']; }

                                        $unit_price = $v_data['single_price'];
                                        if($qty >= 6 && $v_data['price_6_plus'] > 0) { $unit_price = $v_data['price_6_plus']; $bulk_badge = "6+ Bulk Deal"; }
                                        elseif($qty >= 5 && $v_data['price_5_plus'] > 0) { $unit_price = $v_data['price_5_plus']; $bulk_badge = "5+ Bulk Deal"; }
                                        elseif($qty >= 4 && $v_data['price_4_plus'] > 0) { $unit_price = $v_data['price_4_plus']; $bulk_badge = "4+ Bulk Deal"; }
                                    }
                                } else {
                                    $fallback_q = $conn->query("SELECT selling_price FROM products WHERE id = '$p_id'");
                                    $unit_price = $fallback_q->fetch_assoc()['selling_price'];
                                }

                                $subtotal = $unit_price * $qty;
                                $grand_total += $subtotal;
                                $img_src = !empty($item_img) ? $site . 'admin/assets/img/uploads/' . htmlspecialchars($item_img) : $site . 'assets/images/hero.webp';
                    ?>
                                <!-- Dynamic Item Row -->
                                <div class="cart-item-row row" id="row-<?php echo $cart_key; ?>">
                                    
                                    <!-- Mobile Absolute Remove Button -->
                                    <button type="button" class="btn-trash-action d-md-none" onclick="removeItem('<?php echo $cart_key; ?>')" title="Remove Item">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>

                                    <!-- Product Info -->
                                    <div class="col-12 col-md-5 d-flex align-items-center gap-3 mb-3 mb-md-0">
                                        <div class="cart-img-box">
                                            <img src="<?php echo $img_src; ?>" alt="">
                                        </div>
                                        <div>
                                            <h3 class="cart-product-title"><?php echo $item_name; ?></h3>
                                            <?php if(!empty($bulk_badge)): ?>
                                                <span class="badge bg-success rounded-pill mt-1" style="font-size: 0.7rem; background: rgba(39, 174, 96, 0.1) !important; color: #27AE60 !important;"><i class="bi bi-tag-fill me-1"></i><?php echo $bulk_badge; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Unit Price -->
                                    <div class="col-4 col-md-2 text-md-center cart-price-col">
                                        <span class="d-md-none mobile-label">Unit Price</span>
                                        ₹<span id="unit-price-<?php echo $cart_key; ?>"><?php echo number_format($unit_price, 2); ?></span>
                                    </div>

                                    <!-- Quantity Control -->
                                    <div class="col-4 col-md-2 text-center">
                                        <span class="d-md-none mobile-label text-center">Qty</span>
                                        <div class="premium-qty-control mx-auto">
                                            <button type="button" onclick="updateQty('<?php echo $cart_key; ?>', 'dec')"><i class="bi bi-dash"></i></button>
                                            <span id="qty-<?php echo $cart_key; ?>"><?php echo $qty; ?></span>
                                            <button type="button" onclick="updateQty('<?php echo $cart_key; ?>', 'inc')"><i class="bi bi-plus"></i></button>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-4 col-md-2 text-end text-md-center cart-subtotal-col">
                                        <span class="d-md-none mobile-label text-end">Subtotal</span>
                                        ₹<span id="subtotal-<?php echo $cart_key; ?>"><?php echo number_format($subtotal, 2); ?></span>
                                    </div>

                                    <!-- Desktop Remove Button -->
                                    <div class="col-md-1 text-end d-none d-md-flex justify-content-end">
                                        <button type="button" class="btn-trash-action" onclick="removeItem('<?php echo $cart_key; ?>')" title="Remove Item">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>

                                </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- ================= CART SUMMARY SIDEBAR ================= -->
        <div class="col-lg-4">
            <div class="cart-summary-box shadow-sm">
                <h3 class="summary-title"><i class="bi bi-receipt me-2"></i>Cart Summary</h3>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span class="fw-bold text-dark">₹<span class="grand-total-val"><?php echo number_format($grand_total, 2); ?></span></span>
                </div>
                
                <div class="summary-row">
                    <span>Delivery</span>
                    <span class="text-success fw-bold">FREE</span>
                </div>
                
                <div class="summary-row total">
                    <span>Grand Total</span>
                    <span>₹<span class="grand-total-val"><?php echo number_format($grand_total, 2); ?></span></span>
                </div>
                
                <a href="<?php echo $site; ?>checkout.php" class="btn-checkout text-decoration-none">
                    Proceed to Checkout <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- ================= EMPTY CART FALLBACK ================= -->
    <div id="empty-cart-msg" class="empty-cart-wrap shadow-sm" <?php echo !empty($_SESSION['cart']) ? 'style="display:none;"' : ''; ?>>
        <i class="bi bi-cart-x empty-icon d-block"></i>
        <h2 class="fw-bold mb-3" style="color: var(--cart-dark); font-family: 'Poppins', sans-serif;">Your cart feels light!</h2>
        <p class="text-muted mb-4" style="font-family: 'Inter', sans-serif;">Looks like you haven't added any premium makhana to your cart yet.</p>
        <a href="<?php echo $site; ?>product.php" class="btn text-white px-5 py-3 rounded-pill fw-bold shadow-sm" style="background: var(--cart-dark);">Explore Premium Range</a>
    </div>

</main>

<?php include('inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ================= SAFE AJAX WITH TOAST POPUP ================= -->
<script>
    // Quantity Update Handler
    function updateQty(cartKey, type) {
        $.ajax({
            url: 'cart_action.php',
            type: 'POST',
            data: {
                action: 'update_quantity',
                product_id: cartKey, 
                type: type
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    // Update DOM smoothly without page reload!
                    $('#qty-' + cartKey).text(res.quantity);
                    $('#subtotal-' + cartKey).text(res.subtotal);
                    $('.grand-total-val').text(res.grand_total);
                    $('.cart-count').text(res.cart_count); 
                    
                    // Show Premium Toast
                    showToast("Cart Updated", "Quantity has been adjusted successfully.", "success");
                } else {
                    showToast("Update Failed", res.message, "error");
                }
            },
            error: function() {
                showToast("System Error", "Could not connect to the server.", "error");
            }
        });
    }

    // Remove Item Handler
    function removeItem(cartKey) {
        // Removed the annoying confirm() browser popup. Straight to action!
        $.ajax({
            url: 'cart_action.php',
            type: 'POST',
            data: {
                action: 'remove_item',
                product_id: cartKey
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    // Smoothly fade out the item row
                    $('#row-' + cartKey).fadeOut(300, function() {
                        $(this).remove();
                        $('.grand-total-val').text(res.grand_total);
                        $('.cart-count').text(res.cart_count); 

                        // Show Premium Toast
                        showToast("Item Removed", "Product has been removed from your basket.", "success");

                        // If cart is empty, show empty state
                        if (res.cart_empty) {
                            $('#cart-main-container').hide();
                            $('#empty-cart-msg').fadeIn();
                        }
                    });
                } else {
                    showToast("Action Failed", res.message, "error");
                }
            },
            error: function() {
                showToast("System Error", "Could not connect to the server.", "error");
            }
        });
    }
</script>
</body>
</html>