<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php');

// --- TUMHARI RAZORPAY KEY SECRET YAHAN BHI DALNI HAI (Signature Verify ke liye) ---
$razorpay_key_secret = 'JU6fS1arI1DlbwcaebAF9aUK';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $is_buy_now = isset($_POST['is_buy_now']) && $_POST['is_buy_now'] === 'true';
    $checkout_items = $is_buy_now ? (isset($_SESSION['buy_now']) ? $_SESSION['buy_now'] : []) : (isset($_SESSION['cart']) ? $_SESSION['cart'] : []);

    if (empty($checkout_items)) {
        header("Location: " . $site . "cart.php");
        exit();
    }

    $f_name   = htmlspecialchars(trim($_POST['first_name']));
    $l_name   = htmlspecialchars(trim($_POST['last_name']));
    $full_name = htmlspecialchars(trim($f_name . ' ' . $l_name));
    $email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone     = htmlspecialchars(trim($_POST['phone']));
    $raw_address = htmlspecialchars(trim($_POST['address']));
    $pay_mode  = htmlspecialchars(trim($_POST['payment_method']));

    // Razorpay Inputs
    $rzp_payment_id = isset($_POST['razorpay_payment_id']) ? $_POST['razorpay_payment_id'] : null;
    $rzp_order_id = isset($_POST['razorpay_order_id']) ? $_POST['razorpay_order_id'] : null;
    $rzp_signature = isset($_POST['razorpay_signature']) ? $_POST['razorpay_signature'] : null;

    $order_status = 'Pending';
    $payment_status = 'Pending';

    // ============================================
    // RAZORPAY SECURITY VERIFICATION (IF ONLINE)
    // ============================================
    if ($pay_mode === 'Credit Card') { 
        if (!empty($rzp_signature) && !empty($rzp_payment_id) && !empty($rzp_order_id)) {
            // Generating Signature to match with Razorpay
            $generated_signature = hash_hmac('sha256', $rzp_order_id . "|" . $rzp_payment_id, $razorpay_key_secret);
            
            if (hash_equals($generated_signature, $rzp_signature)) {
                $payment_status = 'Paid'; // Verified!
                $order_status = 'Processing';
            } else {
                header("Location: " . $site . "order-status.php?status=failed");
                exit();
            }
        } else {
            header("Location: " . $site . "order-status.php?status=failed");
            exit();
        }
    } else {
        // It's COD
        $order_status = 'Processing'; // COD order accepted
    }

    // Bill Calculation Logic (Same as before)
    $total = 0;
    $product_list = "";
    foreach ($checkout_items as $item) {
        $p_id = intval($item['id']);
        $v_id = isset($item['variation_id']) ? intval($item['variation_id']) : 0;
        $qty = intval($item['quantity']);
        $query = $conn->query("SELECT pro_name, selling_price FROM products WHERE id = '$p_id' LIMIT 1");
        if ($query && $query->num_rows > 0) {
            $p = $query->fetch_assoc();
            $item_name = $p['pro_name'];
            $unit_price = floatval($p['selling_price']);
            if ($v_id > 0) {
                $var_query = $conn->query("SELECT * FROM product_variations WHERE id = '$v_id'");
                if ($var_query && $var_query->num_rows > 0) {
                    $v_data = $var_query->fetch_assoc();
                    $item_name .= " (" . $v_data['weight_size'] . ")";
                    $unit_price = floatval($v_data['single_price']);
                    if ($qty >= 6 && floatval($v_data['price_6_plus']) > 0) { $unit_price = floatval($v_data['price_6_plus']); } 
                    elseif ($qty >= 5 && floatval($v_data['price_5_plus']) > 0) { $unit_price = floatval($v_data['price_5_plus']); } 
                    elseif ($qty >= 4 && floatval($v_data['price_4_plus']) > 0) { $unit_price = floatval($v_data['price_4_plus']); }
                }
            }
            $subtotal = $unit_price * $qty;
            $product_list .= $item_name . " (x" . $qty . ") - Price: ₹" . $subtotal . "\n";
            $total += $subtotal;
        }
    }

    $order_number = 'ANT' . date('Ymd') . rand(1000, 9999); 
    $user_id = 0; 
    $city = 'N/A'; $pincode = 'N/A';
    $final_address_block = "--- Shipping Address ---\n" . $raw_address . "\n\n--- Items List ---\n" . $product_list;

    // Database Insertion (Now including Razorpay Columns)
    $stmt = $conn->prepare("INSERT INTO `orders` (`user_id`, `total_amount`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `customer_city`, `customer_pincode`, `payment_method`, `grand_total`, `order_status`, `payment_status`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("idssssssssdsssss", $user_id, $total, $order_number, $full_name, $email, $phone, $final_address_block, $city, $pincode, $pay_mode, $total, $order_status, $payment_status, $rzp_order_id, $rzp_payment_id, $rzp_signature);

    if ($stmt->execute()) {
        $stmt->close();
        
        // ============================================
        // NEXT PHASE TRIGGER: YAHAN MAIL AUR WHATSAPP BHEJNA HAI
        // We will add the PHPMailer code here in the next step.
        // ============================================

        if ($is_buy_now) { unset($_SESSION['buy_now']); } 
        else { unset($_SESSION['cart']); }

        header("Location: " . $site . "order-status.php?status=success&order_id=" . $order_number);
        exit();
    } else {
        error_log("Database crash trace info constraints: " . $stmt->error);
        $stmt->close();
        header("Location: " . $site . "order-status.php?status=failed");
        exit();
    }
} else {
    header("Location: " . $site . "index.php");
    exit();
}
?>