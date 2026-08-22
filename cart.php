<?php
session_start();
include('config/connect.php');
?>

<?php include('inc/header.php'); ?>

<style>
    /* Premium Quantity Control Styling */
    .quantity-control {
        display: inline-flex;
        align-items: center;
        border: 2px solid #F5E6D3;
        border-radius: 30px;
        background: #FFF8F0;
        padding: 4px 12px;
    }
    .quantity-control button {
        background: none;
        border: none;
        color: #8B4513;
        font-weight: bold;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0 8px;
    }
    .quantity-control span {
        font-weight: bold;
        color: #3E2723;
        min-width: 25px;
        text-align: center;
    }
    .btn-trash-action {
        color: #ff0100;
        background: #ffe5e5;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        transition: 0.3s;
    }
    .btn-trash-action:hover {
        background: #ff0100;
        color: white;
    }

    /* Mobile Specific Overrides */
    @media (max-width: 767px) {
        .quantity-control {
            padding: 2px 8px;
        }
        .quantity-control button {
            font-size: 1.1rem;
            padding: 0 5px;
        }
        .mobile-cart-label {
            font-size: 0.75rem;
            color: #8D6E63;
            display: block;
            margin-bottom: 2px;
            font-weight: 500;
        }
    }
</style>

<main class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-brown"><i class="bi bi-cart3 me-2"></i>Shopping Cart</h2>
        </div>
    </div>

    <div class="row g-4" id="cart-main-container" <?php echo empty($_SESSION['cart']) ? 'style="display:none;"' : ''; ?>>
        
        <!-- Cart Items Section -->
        <div class="col-lg-8">
            <div class="card cart-wrapper-card p-2 p-md-4 border-0 shadow-sm" style="border-radius: 16px;">
                
                <!-- Desktop Header Row (Hidden on Mobile) -->
                <div class="d-none d-md-flex row align-items-center text-muted fw-bold pb-3 mb-2 px-3" style="border-bottom: 2px dashed #F5E6D3;">
                    <div class="col-md-5">Product Info</div>
                    <div class="col-md-2 text-center">Unit Price</div>
                    <div class="col-md-2 text-center">Quantity</div>
                    <div class="col-md-2 text-center">Subtotal</div>
                    <div class="col-md-1 text-center"><i class="bi bi-trash text-danger"></i></div>
                </div>

                <!-- Dynamic Cart Items Loop -->
                <div id="cart-tbody" class="px-2 px-md-3">
                    <?php
                    $grand_total = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $cart_key => $item) {
                            
                            $p_id = $item['id'];
                            $v_id = isset($item['variation_id']) ? $item['variation_id'] : 0;
                            $qty = $item['quantity'];

                            // 1. Fetch Main Product Details
                            $query = $conn->query("SELECT pro_name, pro_img FROM products WHERE id = '$p_id'");
                            if ($query && $query->num_rows > 0) {
                                $p = $query->fetch_assoc();
                                
                                $item_name = htmlspecialchars($p['pro_name']);
                                $item_img = $p['pro_img'];
                                $unit_price = 0;
                                $bulk_badge = "";

                                // 2. Fetch Variation Details
                                if ($v_id > 0) {
                                    $var_query = $conn->query("SELECT * FROM product_variations WHERE id = '$v_id'");
                                    if($var_query && $var_query->num_rows > 0) {
                                        $v_data = $var_query->fetch_assoc();
                                        
                                        $item_name .= " <span class='text-muted fs-6 d-inline-block'>(" . htmlspecialchars($v_data['weight_size']) . ")</span>";
                                        if (!empty($v_data['image_path'])) { $item_img = $v_data['image_path']; }

                                        // Bulk Pricing Logic
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
                    ?>
                                <!-- INDIVIDUAL CART ITEM ROW (Grid Based) -->
                                <div class="row align-items-center cart-item-row py-3 border-bottom position-relative" id="row-<?php echo $cart_key; ?>">
                                    
                                    <!-- Mobile Absolute Remove Button -->
                                    <div class="position-absolute d-md-none" style="top: 15px; right: 0; width: auto; z-index: 10;">
                                        <button type="button" class="btn btn-trash-action btn-sm px-2 py-1" onclick="removeItem('<?php echo $cart_key; ?>')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                    <!-- Image & Title -->
                                    <div class="col-12 col-md-5 mb-3 mb-md-0">
                                        <div class="d-flex align-items-center gap-3 pe-4 pe-md-0">
                                            <img src="<?php echo $site; ?>admin/assets/img/uploads/<?php echo htmlspecialchars($item_img); ?>" style="width:70px; height:70px; object-fit:contain; border: 1px solid #eee; border-radius: 8px; background: #fff;" alt="">
                                            <div>
                                                <h6 class="mb-1 fw-bold text-brown" style="line-height: 1.3;"><?php echo $item_name; ?></h6>
                                                <?php if(!empty($bulk_badge)): ?>
                                                    <span class="badge bg-success rounded-pill mt-1" style="font-size: 0.7rem;"><i class="bi bi-tag-fill me-1"></i><?php echo $bulk_badge; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Unit Price -->
                                    <div class="col-4 col-md-2 text-start text-md-center">
                                        <span class="d-md-none mobile-cart-label">Price</span>
                                        <span class="text-muted fw-bold">₹<span id="unit-price-<?php echo $cart_key; ?>"><?php echo number_format($unit_price, 2); ?></span></span>
                                    </div>

                                    <!-- Quantity Control -->
                                    <div class="col-4 col-md-2 text-center">
                                        <span class="d-md-none mobile-cart-label">Qty</span>
                                        <div class="quantity-control mx-auto">
                                            <button type="button" onclick="updateQty('<?php echo $cart_key; ?>', 'dec')">-</button>
                                            <span id="qty-<?php echo $cart_key; ?>"><?php echo $qty; ?></span>
                                            <button type="button" onclick="updateQty('<?php echo $cart_key; ?>', 'inc')">+</button>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-4 col-md-2 text-end text-md-center">
                                        <span class="d-md-none mobile-cart-label">Total</span>
                                        <span class="fw-bold fs-5 text-brown">₹<span id="subtotal-<?php echo $cart_key; ?>"><?php echo number_format($subtotal, 2); ?></span></span>
                                    </div>

                                    <!-- Desktop Remove Button -->
                                    <div class="col-md-1 text-center d-none d-md-block">
                                        <button type="button" class="btn btn-trash-action" onclick="removeItem('<?php echo $cart_key; ?>')">
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

        <!-- Cart Summary Section -->
        <div class="col-lg-4">
            <div class="card cart-wrapper-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                <h5 class="fw-bold mb-3 pb-3 text-brown" style="border-bottom: 2px dashed #F5E6D3;"><i class="bi bi-receipt me-2"></i>Cart Summary</h5>
                
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Subtotal Price</span>
                    <span class="fw-bold text-dark">₹<span class="grand-total-val"><?php echo number_format($grand_total, 2); ?></span></span>
                </div>
                
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Delivery</span>
                    <span class="text-success fw-bold">FREE</span>
                </div>
                
                <hr class="my-3" style="border-top: 1px dashed #F5E6D3;">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-5 text-dark">Grand Total</span>
                    <span class="fw-bold fs-3 text-brown">₹<span class="grand-total-val"><?php echo number_format($grand_total, 2); ?></span></span>
                </div>
                
                <a href="checkout.php" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold" style="background:#8B4513; border:none; color:white;">
                    Proceed to Checkout <i class="bi bi-arrow-right-circle ms-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Empty Cart Fallback -->
    <div id="empty-cart-msg" class="text-center py-5" <?php echo !empty($_SESSION['cart']) ? 'style="display:none;"' : ''; ?>>
        <i class="bi bi-bag-x text-muted display-1 d-block mb-3"></i>
        <h3 class="fw-bold text-brown">Your cart feels light!</h3>
        <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
        <a href="product.php" class="btn btn-primary-custom px-4 py-2 mt-2 rounded-pill shadow" style="background:#8B4513; border:none; color:white;">Explore Premium Flavors</a>
    </div>
</main>

<?php include('inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Quantity Increase / Decrease Handler
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
                    $('#qty-' + cartKey).text(res.quantity);
                    $('#subtotal-' + cartKey).text(res.subtotal);
                    $('.grand-total-val').text(res.grand_total);
                    $('.cart-count').text(res.cart_count); 
                    location.reload(); 
                }
            }
        });
    }

    // Remove Item from Cart Handler
    function removeItem(cartKey) {
        if (confirm("Are you sure you want to remove this item?")) {
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
                        $('#row-' + cartKey).fadeOut(300, function() {
                            $(this).remove();
                            $('.grand-total-val').text(res.grand_total);
                            $('.cart-count').text(res.cart_count); 

                            if (res.cart_empty) {
                                $('#cart-main-container').hide();
                                $('#empty-cart-msg').fadeIn();
                            }
                        });
                    }
                }
            });
        }
    }
</script>
</body>
</html>