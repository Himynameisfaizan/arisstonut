<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php'); // Global path configuration

// Direct access prevention
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {

    // 1. Identify which session to process (Buy Now vs Regular Cart)
    $is_buy_now = isset($_POST['is_buy_now']) && $_POST['is_buy_now'] === 'true';
    $checkout_items = $is_buy_now ? (isset($_SESSION['buy_now']) ? $_SESSION['buy_now'] : []) : (isset($_SESSION['cart']) ? $_SESSION['cart'] : []);

    // If no items found, redirect back to cart
    if (empty($checkout_items)) {
        header("Location: " . $site . "cart.php");
        exit();
    }

    // 2. Form Variables Sanitization
    $f_name   = htmlspecialchars(trim($_POST['first_name']));
    $l_name   = htmlspecialchars(trim($_POST['last_name']));
    $full_name = htmlspecialchars(trim($f_name . ' ' . $l_name));
    $email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone     = htmlspecialchars(trim($_POST['phone']));
    $raw_address = htmlspecialchars(trim($_POST['address']));
    $pay_mode  = htmlspecialchars(trim($_POST['payment_method']));

    // Optional fields fallback
    $city    = isset($_POST['city']) ? htmlspecialchars(trim($_POST['city'])) : 'N/A';
    $pincode = isset($_POST['pincode']) ? htmlspecialchars(trim($_POST['pincode'])) : 'N/A';

    // 3. Generate DB Variables
    $order_number = 'ANT' . date('Ymd') . rand(1000, 9999); 
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; 
    $order_status = 'Pending';
    $payment_status = 'Pending';

    $product_list = "";
    $total = 0;

    // 4. Calculate accurate final values (Including Variations & Bulk Discounts)
    foreach ($checkout_items as $item) {
        $p_id = intval($item['id']);
        $v_id = isset($item['variation_id']) ? intval($item['variation_id']) : 0;
        $qty = intval($item['quantity']);

        $query = $conn->query("SELECT pro_name, selling_price FROM products WHERE id = '$p_id' LIMIT 1");

        if ($query && $query->num_rows > 0) {
            $p = $query->fetch_assoc();
            $item_name = $p['pro_name'];
            $unit_price = floatval($p['selling_price']);

            // Fetch specific variation and dynamic bulk pricing if applicable
            if ($v_id > 0) {
                $var_query = $conn->query("SELECT * FROM product_variations WHERE id = '$v_id'");
                if ($var_query && $var_query->num_rows > 0) {
                    $v_data = $var_query->fetch_assoc();
                    $item_name .= " (" . $v_data['weight_size'] . ")";
                    $unit_price = floatval($v_data['single_price']);
                    
                    // Apply Bulk Discount Logic for accurate database saving
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

    // Prepare description block
    $final_address_block = "--- Shipping Address ---\n" . $raw_address . "\n\n--- Items List ---\n" . $product_list;

    // 5. Schema Exact Matched SQL Prepared Statement Binding
    $stmt = $conn->prepare("INSERT INTO `orders` (`user_id`, `total_amount`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `customer_city`, `customer_pincode`, `payment_method`, `grand_total`, `order_status`, `payment_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("idssssssssdss", $user_id, $total, $order_number, $full_name, $email, $phone, $final_address_block, $city, $pincode, $pay_mode, $total, $order_status, $payment_status);

    if ($stmt->execute()) {
        $stmt->close();
        
        // Clear ONLY the processed session to keep user experience clean
        if ($is_buy_now) {
            unset($_SESSION['buy_now']);
        } else {
            unset($_SESSION['cart']);
        }

        // REDIRECT TO PREMIUM SUCCESS PAGE
        header("Location: " . $site . "order-status.php?status=success&order_id=" . $order_number);
        exit();

    } else {
        error_log("Database crash trace info constraints: " . $stmt->error);
        $stmt->close();

        // REDIRECT TO PREMIUM FAILED PAGE
        header("Location: " . $site . "order-status.php?status=failed");
        exit();
    }

} else {
    // Kick out direct URL access
    header("Location: " . $site . "index.php");
    exit();
}
?>