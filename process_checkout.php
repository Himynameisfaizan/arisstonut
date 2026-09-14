<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php'); // Global path configuration

// --- RAZORPAY TEST SECRET KEY (Must match create_razorpay_order.php) ---
// $razorpay_key_secret = 'NQ9g2FiTNj5Yn7pb6K194HG7'; 
$razorpay_key_secret = 'JU6fS1arI1DlbwcaebAF9aUK'; // Live Key

// We just check if it's a POST request (Bypassing the input name conflict)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Identify Session
    $is_buy_now = isset($_POST['is_buy_now']) && $_POST['is_buy_now'] === 'true';
    $checkout_items = $is_buy_now ? (isset($_SESSION['buy_now']) ? $_SESSION['buy_now'] : []) : (isset($_SESSION['cart']) ? $_SESSION['cart'] : []);

    if (empty($checkout_items)) {
        header("Location: " . $site . "cart.php");
        exit();
    }

    // 2. Form Variables Sanitization
   // 2. Form Variables Sanitization
    $f_name   = htmlspecialchars(trim($_POST['first_name']));
    $l_name   = htmlspecialchars(trim($_POST['last_name']));
    $full_name = htmlspecialchars(trim($f_name . ' ' . $l_name));
    $email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone     = htmlspecialchars(trim($_POST['phone']));
    $raw_address = htmlspecialchars(trim($_POST['address']));
    $pay_mode  = htmlspecialchars(trim($_POST['payment_method']));

    $city     = htmlspecialchars(trim($_POST['city']));
    $state    = htmlspecialchars(trim($_POST['state']));
    $pincode  = htmlspecialchars(trim($_POST['pincode']));
    
    // NEW OPTIONAL FIELDS
    $alt_phone = isset($_POST['alternate_phone']) ? htmlspecialchars(trim($_POST['alternate_phone'])) : '';
    $landmark  = isset($_POST['landmark']) ? htmlspecialchars(trim($_POST['landmark'])) : '';
    $order_notes = isset($_POST['order_notes']) ? htmlspecialchars(trim($_POST['order_notes'])) : '';

    // Format a beautiful address block for Emails
    $formatted_address = $raw_address;
    if(!empty($landmark)) { $formatted_address .= "\nLandmark: " . $landmark; }
    $formatted_address .= "\n" . $city . ", " . $state . " - " . $pincode;
    if(!empty($alt_phone)) { $formatted_address .= "\nAlt Phone: " . $alt_phone; }
    if(!empty($order_notes)) { $formatted_address .= "\nNotes: " . $order_notes; }

    // Razorpay Inputs
    $rzp_payment_id = isset($_POST['razorpay_payment_id']) ? $_POST['razorpay_payment_id'] : null;
    $rzp_order_id = isset($_POST['razorpay_order_id']) ? $_POST['razorpay_order_id'] : null;
    $rzp_signature = isset($_POST['razorpay_signature']) ? $_POST['razorpay_signature'] : null;

    $order_status = 'Pending';
    $payment_status = 'Pending';

    // ============================================
    // RAZORPAY SECURITY SIGNATURE VERIFICATION
    // ============================================
    if ($pay_mode === 'Credit Card') { 
        if (!empty($rzp_signature) && !empty($rzp_payment_id) && !empty($rzp_order_id)) {
            // Generates HMAC SHA256 Signature
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
        // Cash on Delivery
        $order_status = 'Processing'; 
    }

    // 3. Bill Calculation
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
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; 
   $final_address_block = "--- Shipping Address ---\n" . $formatted_address . "\n\n--- Items List ---\n" . $product_list;

    // 4. DB Insertion (20 Columns updated for Shiprocket readiness)
    $stmt = $conn->prepare("INSERT INTO `orders` (`user_id`, `total_amount`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `alternate_phone`, `customer_address`, `customer_city`, `customer_state`, `customer_pincode`, `customer_landmark`, `order_notes`, `payment_method`, `grand_total`, `order_status`, `payment_status`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("idssssssssssssdsssss", $user_id, $total, $order_number, $full_name, $email, $phone, $alt_phone, $final_address_block, $city, $state, $pincode, $landmark, $order_notes, $pay_mode, $total, $order_status, $payment_status, $rzp_order_id, $rzp_payment_id, $rzp_signature);   if ($stmt->execute()) {
        
        $stmt->close();
        
        // ============================================
        // 📧 SEND ANTI-SPAM EMAIL CONFIRMATION
        // ============================================ 
        if (file_exists('vendor/autoload.php')) {
            require_once 'vendor/autoload.php';
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'faizanonlink@gmail.com'; 
                $mail->Password   = 'ujjk fkni icdd vmyb';   
                // $mail->Username   = 'aristowebin@gmail.com';
                // $mail->Password   = 'kzte hzkh tysh cezg'; 
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('faizanonlink@gmail.com', 'AristoNut');
                $mail->addReplyTo('faizanonlink@gmail.com', 'AristoNut Support'); 
                $mail->addAddress($email, $full_name);

                $mail->isHTML(true);
                $mail->Subject = "Order Confirmed - AristoNut (#" . $order_number . ")";
                
                $mail->Body = "
                <div style='font-family: Arial, sans-serif; background-color: #f9f6f0; padding: 30px;'>
                    <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #9c5521;'>
                        <h2 style='color: #2c1e16; text-align: center; margin-bottom: 20px;'>Thank You for Your Order!</h2>
                        <p style='color: #6b5b53; font-size: 16px;'>Hi <strong>{$f_name}</strong>,</p>
                        <p style='color: #6b5b53; font-size: 16px;'>We're thrilled to let you know that your order <strong>#{$order_number}</strong> has been successfully placed. Your premium makhana will be processed shortly.</p>
                        
                        <div style='background: #fbf8f5; border: 1px dashed #d2b48c; border-radius: 8px; padding: 20px; margin: 25px 0;'>
                            <h3 style='color: #9c5521; margin-top: 0;'>Order Summary</h3>
                            <pre style='font-family: Arial, sans-serif; font-size: 14px; color: #4a3326; white-space: pre-wrap;'>" . $product_list . "</pre>
                            <hr style='border: none; border-top: 1px solid #eaddcf; margin: 15px 0;'>
                            <h3 style='color: #2c1e16; text-align: right; margin: 0;'>Total Paid: ₹" . number_format($total, 2) . "</h3>
                        </div>
                        
                        <p style='color: #6b5b53; text-align: center; font-size: 14px;'>Track your order easily on our website using your email address or phone number.</p>
                        <div style='text-align: center; margin-top: 20px;'>
                            <a href='{$site}track-order.php' style='background: #9c5521; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 25px; font-weight: bold;'>Track My Order</a>
                        </div>
                    </div>
                </div>";

                // ANTI-SPAM FIX: Add Plain Text Fallback
                $mail->AltBody = "Hi {$f_name}, your order #{$order_number} has been placed successfully. Total Amount: Rs {$total}. Track your order on our website.";

                $mail->send();
            } catch (Exception $e) {
                error_log("Order email failed: " . $mail->ErrorInfo);
            }
        }

        // Clear Session and Redirect
        if ($is_buy_now) { unset($_SESSION['buy_now']); } 
        else { unset($_SESSION['cart']); }

        header("Location: " . $site . "order-status.php?status=success&order_id=" . $order_number);
        exit();

    } else {
        error_log("Database crash trace: " . $stmt->error);
        $stmt->close();
        header("Location: " . $site . "order-status.php?status=failed");
        exit();
    }
} else {
    header("Location: " . $site . "index.php");
    exit();
}
?>