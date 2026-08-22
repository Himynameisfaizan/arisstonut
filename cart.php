<?php
session_start();
include('config/connect.php');
?>

<?php include('inc/header.php'); ?>

<style>
    .quantity-control {
        display: inline-flex;
        align-items: center;
        border: 2px solid #F5E6D3;
        border-radius: 30px;
        background: #FFF8F0;
        padding: 5px 15px;
    }
    .quantity-control button {
        background: none;
        border: none;
        color: #8B4513;
        font-weight: bold;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0 10px;
    }
    .quantity-control span {
        font-weight: bold;
        color: #3E2723;
        min-width: 30px;
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
</style>

<main class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-brown"><i class="bi bi-cart3 me-2"></i>Shopping Cart</h2>
        </div>
    </div>

    <div class="row g-4" id="cart-main-container" <?php echo empty($_SESSION['cart']) ? 'style="display:none;"' : ''; ?>>
        <div class="col-lg-8">
            <div class="card cart-wrapper-card p-4 border-0 shadow-sm" style="border-radius: 16px;">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="text-muted" style="border-bottom: 2px dashed #F5E6D3;">
                            <tr>
                                <th>Product Info</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="cart-tbody">
                            <?php
                            $grand_total = 0;
                            if (!empty($_SESSION['cart'])) {
                                // Loop through dynamically generated keys (e.g. 14_2)
                                foreach ($_SESSION['cart'] as $cart_key => $item) {
                                    
                                    $p_id = $item['id'];
                                    $v_id = isset($item['variation_id']) ? $item['variation_id'] : 0;
                                    $qty = $item['quantity'];

                                    // 1. Fetch Main Product Default Details
                                    $query = $conn->query("SELECT pro_name, pro_img FROM products WHERE id = '$p_id'");
                                    if ($query && $query->num_rows > 0) {
                                        $p = $query->fetch_assoc();
                                        
                                        $item_name = htmlspecialchars($p['pro_name']);
                                        $item_img = $p['pro_img'];
                                        $unit_price = 0;
                                        $bulk_badge = "";

                                        // 2. Fetch Variation Details if exists
                                        if ($v_id > 0) {
                                            $var_query = $conn->query("SELECT * FROM product_variations WHERE id = '$v_id'");
                                            if($var_query && $var_query->num_rows > 0) {
                                                $v_data = $var_query->fetch_assoc();
                                                
                                                // Append Weight Size to Name
                                                $item_name .= " <span class='text-muted fs-6'>(" . htmlspecialchars($v_data['weight_size']) . ")</span>";
                                                
                                                // Use variation image if uploaded
                                                if (!empty($v_data['image_path'])) {
                                                    $item_img = $v_data['image_path'];
                                                }

                                                // Exact Bulk Price Logic (Matches Backend)
                                                $unit_price = $v_data['single_price'];
                                                if($qty >= 6 && $v_data['price_6_plus'] > 0) { $unit_price = $v_data['price_6_plus']; $bulk_badge = "6+ Bulk Deal"; }
                                                elseif($qty >= 5 && $v_data['price_5_plus'] > 0) { $unit_price = $v_data['price_5_plus']; $bulk_badge = "5+ Bulk Deal"; }
                                                elseif($qty >= 4 && $v_data['price_4_plus'] > 0) { $unit_price = $v_data['price_4_plus']; $bulk_badge = "4+ Bulk Deal"; }
                                            }
                                        } else {
                                            // Fallback if no variation mapped
                                            $fallback_q = $conn->query("SELECT selling_price FROM products WHERE id = '$p_id'");
                                            $unit_price = $fallback_q->fetch_assoc()['selling_price'];
                                        }

                                        $subtotal = $unit_price * $qty;
                                        $grand_total += $subtotal;
                            ?>
                                        <tr class="cart-item-row border-bottom" id="row-<?php echo $cart_key; ?>">
                                            <td>
                                                <div class="d-flex align-items-center gap-3 py-3">
                                                    <img src="<?php echo $site; ?>admin/assets/img/uploads/<?php echo htmlspecialchars($item_img); ?>" style="width:65px; height:65px; object-fit:contain; border: 1px solid #eee; border-radius: 8px; background: #fff;" alt="">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-brown"><?php echo $item_name; ?></h6>
                                                        <?php if(!empty($bulk_badge)): ?>
                                                            <span class="badge bg-success rounded-pill" style="font-size: 0.7rem;"><i class="bi bi-tag-fill me-1"></i><?php echo $bulk_badge; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-muted fw-bold">₹<span id="unit-price-<?php echo $cart_key; ?>"><?php echo number_format($unit_price, 2); ?></span></td>
                                            <td class="text-center">
                                                <div class="quantity-control">
                                                    <!-- Note: Added quotes around '$cart_key' to fix JS error -->
                                                    <button type="button" onclick="updateQty('<?php echo $cart_key; ?>', 'dec')">-</button>
                                                    <span id="qty-<?php echo $cart_key; ?>"><?php echo $qty; ?></span>
                                                    <button type="button" onclick="updateQty('<?php echo $cart_key; ?>', 'inc')">+</button>
                                                </div>
                                            </td>
                                            <td class="text-center fw-bold fs-5 text-brown">₹<span id="subtotal-<?php echo $cart_key; ?>"><?php echo number_format($subtotal, 2); ?></span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-trash-action" onclick="removeItem('<?php echo $cart_key; ?>')">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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
                product_id: cartKey, // Passes '14_2' correctly as string
                type: type
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    // Update exact elements
                    $('#qty-' + cartKey).text(res.quantity);
                    $('#subtotal-' + cartKey).text(res.subtotal);
                    $('.grand-total-val').text(res.grand_total);
                    $('.cart-count').text(res.cart_count); // Updates Header Icon

                    // Reload page to reflect exact unit price changes if bulk tier changes (easier sync)
                    // If you want a smoother experience without refresh, you can leave this out, 
                    // but the unit price visual text won't auto-update without refresh.
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
                            $('.cart-count').text(res.cart_count); // Updates Header Icon

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