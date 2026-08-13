<?php
session_start();
include('config/connect.php');
?>

<?php include('inc/header.php'); ?>

<main class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-brown"><i class="bi bi-cart3 me-2"></i>Shopping Cart</h2>
        </div>
    </div>

    <div class="row g-4 id=" cart-main-container" <?php echo empty($_SESSION['cart']) ? 'style="display:none;"' : ''; ?>>
        <div class="col-lg-8">
            <div class="card cart-wrapper-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product Info</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="cart-tbody">
                            <?php
                            $grand_total = 0;
                            if (!empty($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $pid => $item) {
                                    $query = $conn->query("SELECT pro_name, selling_price, pro_img FROM products WHERE id = '$pid'");
                                    if ($query && $query->num_rows > 0) {
                                        $p = $query->fetch_assoc();
                                        $subtotal = $p['selling_price'] * $item['quantity'];
                                        $grand_total += $subtotal;
                            ?>
                                        <tr class="cart-item-row" id="row-<?php echo $pid; ?>">
                                            <td>
                                                <div class="d-flex align-items-center gap-3 py-2">
                                                    <img src="admin/assets/img/uploads/<?php echo htmlspecialchars($p['pro_img']); ?>" style="width:60px; height:60px; object-fit:contain;" alt="">
                                                    <h6 class="mb-0 fw-bold text-brown"><?php echo htmlspecialchars($p['pro_name']); ?></h6>
                                                </div>
                                            </td>
                                            <td class="text-center">₹<?php echo $p['selling_price']; ?></td>
                                            <td class="text-center">
                                                <div class="quantity-control">
                                                    <button type="button" onclick="updateQty(<?php echo $pid; ?>, 'dec')">-</button>
                                                    <span id="qty-<?php echo $pid; ?>"><?php echo $item['quantity']; ?></span>
                                                    <button type="button" onclick="updateQty(<?php echo $pid; ?>, 'inc')">+</button>
                                                </div>
                                            </td>
                                            <td class="text-center fw-bold text-brown">₹<span id="subtotal-<?php echo $pid; ?>"><?php echo number_format($subtotal, 2); ?></span></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-trash-action" onclick="removeItem(<?php echo $pid; ?>)">
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
            <div class="card cart-wrapper-card p-4">
                <h5 class="fw-bold mb-3 pb-2 text-brown" style="border-bottom: 2px dashed #F5E6D3;"><i class="bi bi-receipt me-2"></i>Cart Summary</h5>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Subtotal Price</span>
                    <span class="fw-bold">₹<span class="grand-total-val"><?php echo number_format($grand_total, 2); ?></span></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Delivery</span>
                    <span class="text-success fw-medium">FREE</span>
                </div>
                <hr class="my-3" style="border-top: 1px dashed #F5E6D3;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-5">Grand Total</span>
                    <span class="fw-bold fs-3 text-brown">₹<span class="grand-total-val"><?php echo number_format($grand_total, 2); ?></span></span>
                </div>
                <a href="checkout.php" class="btn btn-primary-custom w-100">Proceed to Checkout <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>
    </div>

    <div id="empty-cart-msg" class="text-center py-5" <?php echo !empty($_SESSION['cart']) ? 'style="display:none;"' : ''; ?>>
        <i class="bi bi-bag-x text-muted fs-1 d-block mb-3"></i>
        <h5 class="fw-bold text-muted">Your cart feels light!</h5>
        <a href="index.php" class="btn btn-primary-custom btn-sm d-inline-block mt-2">Explore Flavors</a>
    </div>
</main>

<?php include('inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Quantity Increase / Decrease Handler
    function updateQty(productId, type) {
        $.ajax({
            url: 'cart_action.php',
            type: 'POST',
            data: {
                action: 'update_quantity',
                product_id: productId,
                type: type
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    $('#qty-' + productId).text(res.quantity);
                    $('#subtotal-' + productId).text(res.subtotal);
                    $('.grand-total-val').text(res.grand_total);
                }
            }
        });
    }

    // Remove Item from Cart Handler
    function removeItem(productId) {
        if (confirm("Are you sure you want to remove this item?")) {
            $.ajax({
                url: 'cart_action.php',
                type: 'POST',
                data: {
                    action: 'remove_item',
                    product_id: productId
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        $('#row-' + productId).fadeOut(300, function() {
                            $(this).remove();
                            $('.grand-total-val').text(res.grand_total);

                            // Agar saare items delete ho gaye hain to empty block show karein
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