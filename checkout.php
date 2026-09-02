<?php
session_start();
include('config/connect.php');

$is_buy_now = isset($_GET['buy_now']) && $_GET['buy_now'] == 'true';
$checkout_items = $is_buy_now && isset($_SESSION['buy_now']) && !empty($_SESSION['buy_now']) ? $_SESSION['buy_now'] : (isset($_SESSION['cart']) ? $_SESSION['cart'] : []);

if (empty($checkout_items)) {
    header("Location: cart.php");
    exit();
}

$pageTitle = "Secure Checkout";
include('inc/header.php');
include('inc/breadcrumb.php');
?>

<!-- ================= PREMIUM CHECKOUT UI ================= -->
<style>
    :root {
        --chk-bg: #FCFAF8;
        --chk-card-bg: #FFFFFF;
        --chk-dark: #2C1E16;
        --chk-muted: #6B5B53;
        --chk-accent: #9C5521;
        --chk-light: #FFF0E5;
    }

    body {
        background-color: var(--chk-bg);
    }

    .checkout-wrapper {
        padding: 60px 0 100px 0;
    }

    /* Premium Box Styling */
    .checkout-box {
        background: var(--chk-card-bg);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.02);
        margin-bottom: 24px;
    }

    .box-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--chk-dark);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 2px dashed rgba(156, 85, 33, 0.15);
        padding-bottom: 15px;
    }

    .box-title i {
        color: var(--chk-accent);
    }

    /* Custom Form Inputs */
    .custom-input {
        background: #F9F9F9;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 14px 20px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        color: var(--chk-dark);
        transition: all 0.3s ease;
    }

    .custom-input:focus {
        background: #FFFFFF;
        border-color: var(--chk-accent);
        box-shadow: 0 0 0 4px rgba(156, 85, 33, 0.1);
        outline: none;
    }

    .form-label {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        color: var(--chk-dark);
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    /* Item Summary List */
    .summary-item-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 15px;
        margin-bottom: 15px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
    }

    .summary-item-img {
        width: 65px;
        height: 65px;
        background: #F9F6F0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        flex-shrink: 0;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .summary-item-img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    .summary-item-details {
        flex-grow: 1;
    }

    .summary-item-name {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: var(--chk-dark);
        font-size: 0.95rem;
        margin: 0;
        line-height: 1.3;
    }

    .summary-item-meta {
        font-family: 'Inter', sans-serif;
        font-size: 0.8rem;
        color: var(--chk-muted);
        margin: 0;
    }

    .summary-item-price {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: var(--chk-dark);
        font-size: 1.05rem;
    }

    /* Billing Totals */
    .bill-row {
        display: flex;
        justify-content: space-between;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        color: var(--chk-muted);
        margin-bottom: 12px;
    }

    .bill-total {
        font-family: 'Poppins', sans-serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--chk-dark);
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Payment Method Cards (Modern Select) */
    .payment-option {
        display: none;
    }

    .payment-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px 20px;
        border: 2px solid rgba(0, 0, 0, 0.06);
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 12px;
        background: #FFFFFF;
    }

    .payment-card i {
        font-size: 1.5rem;
        color: var(--chk-muted);
        transition: 0.3s;
    }

    .payment-card span {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        color: var(--chk-dark);
        font-size: 1rem;
    }

    .payment-option:checked+.payment-card {
        border-color: var(--chk-accent);
        background: var(--chk-light);
    }

    .payment-option:checked+.payment-card i {
        color: var(--chk-accent);
    }

    /* Place Order Button */
    .btn-pay {
        background: var(--chk-accent);
        color: #FFFFFF;
        border: none;
        width: 100%;
        padding: 18px;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 25px;
        box-shadow: 0 10px 20px rgba(156, 85, 33, 0.2);
    }

    .btn-pay:hover {
        background: #7A4219;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(156, 85, 33, 0.3);
        color: #FFF;
    }

    @media (max-width: 991px) {
        .checkout-box {
            padding: 25px;
        }
    }
</style>

<main class="checkout-wrapper container">
    <form action="process_checkout.php" method="POST" id="checkoutForm">
        <!-- Identifying order type for backend processing -->
        <input type="hidden" name="is_buy_now" value="<?php echo $is_buy_now ? 'true' : 'false'; ?>">

        <div class="row g-5">

            <!-- ================= LEFT: SHIPPING DETAILS ================= -->
            <div class="col-lg-7">
                <div class="checkout-box">
                    <h2 class="box-title"><i class="bi bi-geo-alt"></i> Shipping Address</h2>

                    <input type="hidden" name="is_buy_now" value="<?php echo $is_buy_now ? 'true' : 'false'; ?>">
    
    <!-- Razorpay Hidden Fields -->
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                    <div class="row g-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control custom-input" placeholder="e.g. John" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control custom-input" placeholder="e.g. Doe" required>
                        </div>
                    </div>

                    <div class="mb-4 mt-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control custom-input" placeholder="name@example.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="phone" class="form-control custom-input" placeholder="+91 98765 43210" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Complete Address</label>
                        <textarea name="address" class="form-control custom-input" rows="4" placeholder="House/Flat No, Street Name, Landmark, City, State, Pincode" required></textarea>
                    </div>
                </div>

                <!-- PAYMENT METHOD SECTION -->
                <div class="checkout-box">
                    <h2 class="box-title"><i class="bi bi-wallet2"></i> Payment Method</h2>

                    <input type="radio" name="payment_method" id="pay_cod" value="COD" class="payment-option" checked>
                    <label for="pay_cod" class="payment-card">
                        <i class="bi bi-cash-stack"></i>
                        <span>Cash on Delivery (COD)</span>
                    </label>

                    <input type="radio" name="payment_method" id="pay_card" value="Credit Card" class="payment-option">
                    <label for="pay_card" class="payment-card">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Secure Online Payment (Card / UPI / NetBanking)</span>
                    </label>
                </div>
            </div>

            <!-- ================= RIGHT: ORDER SUMMARY ================= -->
            <div class="col-lg-5">
                <div class="checkout-box" style="position: sticky; top: 100px;">
                    <h2 class="box-title"><i class="bi bi-bag-check"></i> Order Summary</h2>

                    <div class="mb-4" style="max-height: 350px; overflow-y: auto; padding-right: 10px;">
                        <?php
                        $total = 0;
                        foreach ($checkout_items as $cart_key => $item) {
                            $p_id = $item['id'];
                            $v_id = isset($item['variation_id']) ? $item['variation_id'] : 0;
                            $qty = $item['quantity'];

                            $query = $conn->query("SELECT pro_name, pro_img FROM products WHERE id = '$p_id'");
                            if ($query && $query->num_rows > 0) {
                                $p = $query->fetch_assoc();
                                $item_name = htmlspecialchars($p['pro_name']);
                                $item_img = $p['pro_img'];
                                $unit_price = 0;

                                if ($v_id > 0) {
                                    $var_query = $conn->query("SELECT * FROM product_variations WHERE id = '$v_id'");
                                    if ($var_query && $var_query->num_rows > 0) {
                                        $v_data = $var_query->fetch_assoc();
                                        $item_name .= " (" . htmlspecialchars($v_data['weight_size']) . ")";
                                        if (!empty($v_data['image_path'])) {
                                            $item_img = $v_data['image_path'];
                                        }

                                        $unit_price = $v_data['single_price'];
                                        if ($qty >= 6 && $v_data['price_6_plus'] > 0) {
                                            $unit_price = $v_data['price_6_plus'];
                                        } elseif ($qty >= 5 && $v_data['price_5_plus'] > 0) {
                                            $unit_price = $v_data['price_5_plus'];
                                        } elseif ($qty >= 4 && $v_data['price_4_plus'] > 0) {
                                            $unit_price = $v_data['price_4_plus'];
                                        }
                                    }
                                } else {
                                    $fallback_q = $conn->query("SELECT selling_price FROM products WHERE id = '$p_id'");
                                    $unit_price = $fallback_q->fetch_assoc()['selling_price'];
                                }

                                $subtotal = $unit_price * $qty;
                                $total += $subtotal;
                                $img_src = !empty($item_img) ? $site . 'admin/assets/img/uploads/' . htmlspecialchars($item_img) : $site . 'assets/images/hero.webp';
                        ?>

                                <div class="summary-item-row">
                                    <div class="summary-item-img">
                                        <img src="<?php echo $img_src; ?>" alt="">
                                    </div>
                                    <div class="summary-item-details">
                                        <h6 class="summary-item-name text-truncate" style="max-width: 180px;"><?php echo $item_name; ?></h6>
                                        <p class="summary-item-meta">Qty: <?php echo $qty; ?> &times; ₹<?php echo number_format($unit_price, 2); ?></p>
                                    </div>
                                    <div class="summary-item-price">
                                        ₹<?php echo number_format($subtotal, 2); ?>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>

                    <!-- Billing Calculations -->
                    <div>
                        <div class="bill-row">
                            <span>Cart Subtotal</span>
                            <span class="fw-bold text-dark">₹<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="bill-row">
                            <span>Delivery Charges</span>
                            <span class="text-success fw-bold">FREE</span>
                        </div>

                        <div class="bill-total">
                            <span>Total Amount</span>
                            <span style="color: var(--chk-accent);">₹<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>

                    <button type="submit" name="place_order" class="btn-pay">
                        Confirm & Pay <i class="bi bi-lock-fill"></i>
                    </button>

                    <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.8rem; font-family: 'Inter', sans-serif;">
                        <i class="bi bi-shield-check text-success"></i> 100% Safe & Secure Checkout
                    </p>
                </div>
            </div>

        </div>
    </form>
</main>

<?php include('inc/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- RAZORPAY SCRIPT -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Stop standard submission
    
    const form = this;
    // Check form validation natively
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const payBtn = document.querySelector('.btn-pay');
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const isBuyNow = document.querySelector('input[name="is_buy_now"]').value;
    
    const customerName = document.querySelector('input[name="first_name"]').value + " " + document.querySelector('input[name="last_name"]').value;
    const customerEmail = document.querySelector('input[name="email"]').value;
    const customerPhone = document.querySelector('input[name="phone"]').value;

    if (paymentMethod === 'COD') {
        // Submit directly if COD
        payBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
        form.submit();
    } else {
        // It's Online Payment -> Generate Order from Server
        payBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Initializing Payment...';
        
        $.ajax({
            url: 'create_razorpay_order.php',
            type: 'POST',
            data: { is_buy_now: isBuyNow },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    
                    var options = {
                        "key": res.key, 
                        "amount": res.amount, 
                        "currency": "INR",
                        "name": "AristoNut",
                        "description": "Premium Makhana Order",
                        "image": "assets/images/logo.webp", 
                        "order_id": res.order_id, 
                        "handler": function (response){
                            // ON SUCCESS: Fill hidden fields & submit form to process_checkout.php
                            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                            document.getElementById('razorpay_signature').value = response.razorpay_signature;
                            payBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Verifying...';
                            form.submit();
                        },
                        "prefill": {
                            "name": customerName,
                            "email": customerEmail,
                            "contact": customerPhone
                        },
                        "theme": { "color": "#9C5521" }, // Brand Color
                        "modal": {
                            "ondismiss": function(){
                                payBtn.innerHTML = 'Confirm & Pay <i class="bi bi-lock-fill"></i>';
                            }
                        }
                    };
                    var rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    alert("Error: " + res.message);
                    payBtn.innerHTML = 'Confirm & Pay <i class="bi bi-lock-fill"></i>';
                }
            },
            error: function() {
                alert("Server error while initializing payment.");
                payBtn.innerHTML = 'Confirm & Pay <i class="bi bi-lock-fill"></i>';
            }
        });
    }
});
</script>

</body>

</html>