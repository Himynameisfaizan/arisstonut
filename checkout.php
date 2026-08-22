<?php
session_start();
include('config/connect.php');

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}
?>
    <?php include('inc/header.php'); ?>

    <main class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-brown"><i class="bi bi-shield-check me-2"></i>Secure Checkout</h2>
                <p class="text-muted small">Confirm your shipping credentials to parse your delicious premium Makhana boxes.</p>
            </div>
        </div>

        <form action="process_checkout.php" method="POST">
            <div class="row g-4">

                <div class="col-lg-7">
                    <div class="card checkout-card p-4">
                        <h5 class="card-title-header"><i class="bi bi-truck me-2 text-brown"></i>Shipping Address</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium small">First Name</label>
                                <input type="text" name="first_name" class="form-control" placeholder="John" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium small">Last Name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Doe" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium small">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="johndoe@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium small">Contact Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-medium small">Complete Address</label>
                            <textarea name="address" class="form-control" rows="4" placeholder="House/Flat No, Street Name, Landmark, City, State" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card checkout-card p-4">
                        <h5 class="card-title-header"><i class="bi bi-bag-check me-2 text-brown"></i>Order Basket</h5>

                        <div class="product-summary-list-scroll mb-4" style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                            <?php
                            $total = 0;
                            foreach ($_SESSION['cart'] as $pid => $item) {
                                // Dynamic wildcard evaluation matching product context schema specifications
                                $query = $conn->query("SELECT pro_name, selling_price, pro_img FROM products WHERE id = '$pid'");
                                $p = $query->fetch_assoc();
                                $subtotal = $p['selling_price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                                <div class="product-summary-item">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="admin/assets/img/uploads/<?php echo htmlspecialchars($p['pro_img']); ?>" alt="<?php echo htmlspecialchars($p['pro_name']); ?>">
                                        <div>
                                            <h6 class="mb-0 fw-bold small text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($p['pro_name']); ?></h6>
                                            <small class="text-muted">Qty: <?php echo $item['quantity']; ?> x ₹<?php echo $p['selling_price']; ?></small>
                                        </div>
                                    </div>
                                    <span class="fw-bold small text-brown">₹<?php echo $subtotal; ?></span>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="summary-total-block mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Cart Subtotal</span>
                                <span class="fw-medium small">₹<?php echo $total; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Delivery Charges</span>
                                <span class="text-success fw-medium small">FREE</span>
                            </div>
                            <hr class="my-2" style="border-top: 1px dashed #FFE4C4;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Amount</span>
                                <span class="fw-bold fs-4 text-brown">₹<?php echo $total; ?></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-brown"><i class="bi bi-credit-card me-2"></i>Select Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="COD">💵 Cash on Delivery (COD)</option>
                                <option value="Credit Card">💳 Secure Online Card Transaction</option>
                            </select>
                        </div>

                        <button type="submit" name="place_order" class="btn btn-checkout-submit">
                            Confirm & Place Order <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>