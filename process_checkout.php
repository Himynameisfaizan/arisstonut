<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php'); // Global path configuration

// --- RAZORPAY TEST SECRET KEY (Must match create_razorpay_order.php) ---
// $razorpay_key_secret = 'NQ9g2FiTNj5Yn7pb6K194HG7'; 

// --- RAZORPAY LIVE SECRET KEY ---
$razorpay_key_secret = 'JU6fS1arI1DlbwcaebAF9aUK'; // Live Key

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $is_buy_now = isset($_POST['is_buy_now']) &&$_POST['is_buy_now'] === 'true';
    $checkout_items =$is_buy_now ? (isset($_SESSION['buy_now']) ?$_SESSION['buy_now'] : []) : (isset($_SESSION['cart']) ?$_SESSION['cart'] : []);

    if (empty($checkout_items)) {
        header("Location: " . $site . "cart.php");
        exit();
    }

    $f_name   = htmlspecialchars(trim($_POST['first_name']));$l_name   = htmlspecialchars(trim($_POST['last_name']));$full_name = htmlspecialchars(trim($f_name . ' ' .$l_name));
    $email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone     = htmlspecialchars(trim($_POST['phone']));
    $raw_address = htmlspecialchars(trim($_POST['address']));
    $pay_mode  = htmlspecialchars(trim($_POST['payment_method']));

    $city     = htmlspecialchars(trim($_POST['city']));
    $state    = htmlspecialchars(trim($_POST['state']));
    $pincode  = htmlspecialchars(trim($_POST['pincode']));
    
    $alt_phone = isset($_POST['alternate_phone']) ? htmlspecialchars(trim($_POST['alternate_phone'])) : '';
    $landmark  = isset($_POST['landmark']) ? htmlspecialchars(trim($_POST['landmark'])) : '';$order_notes = isset($_POST['order_notes']) ? htmlspecialchars(trim($_POST['order_notes'])) : '';

    $formatted_address =$raw_address;
    if(!empty($landmark)) { $formatted_address .= "\nLandmark: " . $landmark; }
    $formatted_address .= "\n" . $city . ", " . $state . " - " . $pincode;
    if(!empty($alt_phone)) { $formatted_address .= "\nAlt Phone: " . $alt_phone; }
    if(!empty($order_notes)) { $formatted_address .= "\nNotes: " . $order_notes; }

    $rzp_payment_id = isset($_POST['razorpay_payment_id']) ?$_POST['razorpay_payment_id'] : '';
    $rzp_order_id = isset($_POST['razorpay_order_id']) ? $_POST['razorpay_order_id'] : '';$rzp_signature = isset($_POST['razorpay_signature']) ?$_POST['razorpay_signature'] : '';

    $order_status = 'Pending';$payment_status = 'Pending';

    $cart_subtotal = 0;
    $product_list = "";
    
    foreach ($checkout_items as $item) {$p_id = intval($item['id']);$v_id = isset($item['variation_id']) ? intval($item['variation_id']) : 0;
        $qty = intval($item['quantity']);

        $query =$conn->query("SELECT pro_name, selling_price FROM products WHERE id = '$p_id' LIMIT 1");
        if ($query &&$query->num_rows > 0) {
            $p =$query->fetch_assoc();
            $item_name =$p['pro_name'];
            $unit_price = floatval($p['selling_price']);

            if ($v_id > 0) {
                $var_query =$conn->query("SELECT * FROM product_variations WHERE id = '$v_id'");
                if ($var_query &&$var_query->num_rows > 0) {
                    $v_data =$var_query->fetch_assoc();
                    $item_name .= " (" . $v_data['weight_size'] . ")";
                    $unit_price = floatval($v_data['single_price']);
                    
                    if ($qty >= 6 && floatval($v_data['price_6_plus']) > 0) { $unit_price = floatval($v_data['price_6_plus']); } 
                    elseif ($qty >= 5 && floatval($v_data['price_5_plus']) > 0) { $unit_price = floatval($v_data['price_5_plus']); } 
                    elseif ($qty >= 4 && floatval($v_data['price_4_plus']) > 0) { $unit_price = floatval($v_data['price_4_plus']); }
                }
            }

            $subtotal = $unit_price * $qty;
            $product_list .=$item_name . " (x" . $qty . ") - Price: ₹" . $subtotal . "\n";
            $cart_subtotal +=$subtotal;
        }
    }

    $shipping_fee = 0;
    
    if ($pay_mode === 'COD') {
        $shipping_fee = 99; 
        if ($cart_subtotal < 699) {
            $shipping_fee = 99; 
        }
    }

    $grand_total = $cart_subtotal +$shipping_fee;

    if ($pay_mode === 'Online') {
        if (!empty($rzp_signature) && !empty($rzp_payment_id) && !empty($rzp_order_id)) {
            $generated_signature = hash_hmac('sha256',$rzp_order_id . "|" . $rzp_payment_id,$razorpay_key_secret);
            if (hash_equals($generated_signature,$rzp_signature)) {
                $payment_status = 'Paid';$order_status = 'Processing';
            } else {
                header("Location: " . $site . "order-status.php?status=failed");
                exit();
            }
        } else {
            header("Location: " . $site . "order-status.php?status=failed");
            exit();
        }
    } else {
        $payment_status = 'Pending';$order_status = 'Processing'; 
    }

    $order_number = 'ANT' . date('Ymd') . rand(1000, 9999); 
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; 
    $final_address_block = "--- Shipping Address ---\n" . $formatted_address . "\n\n--- Items List ---\n" . $product_list;

    $stmt =$conn->prepare("INSERT INTO `orders` (`user_id`, `total_amount`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `alternate_phone`, `customer_address`, `customer_city`, `customer_state`, `customer_pincode`, `customer_landmark`, `order_notes`, `payment_method`, `grand_total`, `order_status`, `payment_status`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param("idssssssssssssdsssss", $user_id,$cart_subtotal, $order_number,$full_name, $email,$phone, $alt_phone,$final_address_block, $city,$state, $pincode,$landmark, $order_notes,$pay_mode, $grand_total,$order_status, $payment_status,$rzp_order_id, $rzp_payment_id,$rzp_signature);
    
    if ($stmt->execute()) {
        $stmt->close();
        
        if (file_exists('vendor/autoload.php')) {
            require_once 'vendor/autoload.php';
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';$mail->SMTPAuth   = true;
                $mail->Username   = 'aristowebin@gmail.com'; 
                $mail->Password   = 'kzte hzkh tysh cezg';$mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('aristowebin@gmail.com', 'AristoNut');
                $mail->addReplyTo('aristowebin@gmail.com', 'AristoNut Support'); 
                $mail->addAddress($email,$full_name);

                $mail->isHTML(true);$mail->Subject = "Order Confirmed - AristoNut (#" . $order_number . ")";
                
                $estimated_date = date('l, d M Y', strtotime('+5 days'));$shipping_display = ($shipping_fee > 0) ? "₹" . number_format($shipping_fee, 2) : "FREE";

                $mail->Body = "
                <div style='font-family: Arial, sans-serif; background-color: #f9f6f0; padding: 30px 10px;'>
                    <div style='max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05);'>
                        
                        <div style='background: #9c5521; padding: 30px; text-align: center;'>
                            <h1 style='color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px;'>Order Successfully Placed!</h1>
                            <p style='color: #f9f6f0; margin: 10px 0 0 0; font-size: 15px;'>Your premium makhana is on its way.</p>
                        </div>

                        <div style='padding: 30px;'>
                            <p style='color: #4a3326; font-size: 16px;'>Hi <strong>{$f_name}</strong>,</p>
                            <p style='color: #6b5b53; font-size: 15px; line-height: 1.6;'>Thank you for choosing AristoNut! We have received your order <strong>#{$order_number}</strong>.</p>
                            
                            <div style='background: #fbf8f5; border-left: 4px solid #27ae60; padding: 15px 20px; margin: 25px 0; border-radius: 4px;'>
                                <p style='margin: 0; color: #2c1e16; font-size: 14px;'><strong>Expected Delivery Date:</strong></p>
                                <h3 style='margin: 5px 0 0 0; color: #27ae60;'>{$estimated_date}</h3>
                                <p style='margin: 5px 0 0 0; color: #6b5b53; font-size: 12px;'>*This is an estimated date. Exact tracking details will be updated shortly.</p>
                            </div>

                            <h3 style='color: #9c5521; border-bottom: 2px dashed #eaddcf; padding-bottom: 10px; margin-top: 30px;'>Invoice Summary</h3>
                            
                            <table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>
                                <thead style='background-color: #f9f6f0;'>
                                    <tr>
                                        <th style='padding: 12px; text-align: left; color: #2c1e16; font-size: 14px; border-bottom: 1px solid #eaddcf;'>Item Description</th>
                                        <th style='padding: 12px; text-align: right; color: #2c1e16; font-size: 14px; border-bottom: 1px solid #eaddcf;'>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan='2' style='padding: 15px 12px; color: #6b5b53; font-size: 14px; line-height: 1.8; border-bottom: 1px solid #eee;'>
                                            " . nl2br($product_list) . "
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style='padding: 15px 12px; text-align: right; font-weight: bold; color: #2c1e16;'>Shipping:</td>
                                        <td style='padding: 15px 12px; text-align: right; font-weight: bold; color: #27ae60;'>{$shipping_display}</td>
                                    </tr>
                                    <tr style='background-color: #fbf8f5;'>
                                        <td style='padding: 15px 12px; text-align: right; font-weight: bold; color: #9c5521; font-size: 18px;'>Grand Total:</td>
                                        <td style='padding: 15px 12px; text-align: right; font-weight: bold; color: #9c5521; font-size: 18px;'>₹" . number_format($grand_total, 2) . "</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div style='margin-top: 35px; text-align: center;'>
                                <a href='{$site}track-order.php' style='background: #2c1e16; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block;'>Track Your Order Live</a>
                            </div>
                        </div>

                        <div style='background: #fbf8f5; padding: 20px; text-align: center; border-top: 1px solid #eaddcf;'>
                            <h4 style='color: #9c5521; margin: 0 0 5px 0; font-style: italic;'>Nourishing Lives with Every Crunch!</h4>
                            <p style='color: #888; font-size: 12px; margin: 0;'>AristoNut Premium Quality Snacking<br><a href='{$site}' style='color: #9c5521;'>www.aristonut.com</a></p>
                        </div>
                    </div>
                </div>";

                $mail->AltBody = "Hi {$f_name}, your order #{$order_number} has been placed. Grand Total: Rs {$grand_total}.";

                $mail->send();
            } catch (Exception $e) {
                error_log("Order email failed: " . $mail->ErrorInfo);
            }
        }

        if ($is_buy_now) { unset($_SESSION['buy_now']); } 
        else { unset($_SESSION['cart']); }

        header("Location: " . $site . "order-status.php?status=success&order_id=" . $order_number);
        exit();

    } else {
        error_log("Database crash trace: " . $stmt->error);$stmt->close();
        header("Location: " . $site . "order-status.php?status=failed");
        exit();
    }
} else {
    header("Location: " . $site . "index.php");
    exit();
}
?>