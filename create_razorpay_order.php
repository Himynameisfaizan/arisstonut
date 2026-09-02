<?php
session_start();
include('config/connect.php');

header('Content-Type: application/json');

// --- TUMHARI RAZORPAY KEYS (YAHAN APNI LIVE KEYS DAALNA) ---
$razorpay_key_id = 'rzp_live_TWjOQkUEcLZpaN'; 
$razorpay_key_secret = 'JU6fS1arI1DlbwcaebAF9aUK';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check which session to calculate
    $is_buy_now = isset($_POST['is_buy_now']) && $_POST['is_buy_now'] === 'true';
    $checkout_items = $is_buy_now ? (isset($_SESSION['buy_now']) ? $_SESSION['buy_now'] : []) : (isset($_SESSION['cart']) ? $_SESSION['cart'] : []);

    if (empty($checkout_items)) {
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
        exit;
    }

    $total = 0;
    // Calculate total accurate price
    foreach ($checkout_items as $item) {
        $p_id = intval($item['id']);
        $v_id = isset($item['variation_id']) ? intval($item['variation_id']) : 0;
        $qty = intval($item['quantity']);

        $query = $conn->query("SELECT selling_price FROM products WHERE id = '$p_id' LIMIT 1");
        if ($query && $query->num_rows > 0) {
            $p = $query->fetch_assoc();
            $unit_price = floatval($p['selling_price']);

            if ($v_id > 0) {
                $var_query = $conn->query("SELECT * FROM product_variations WHERE id = '$v_id'");
                if ($var_query && $var_query->num_rows > 0) {
                    $v_data = $var_query->fetch_assoc();
                    $unit_price = floatval($v_data['single_price']);
                    
                    if ($qty >= 6 && floatval($v_data['price_6_plus']) > 0) { $unit_price = floatval($v_data['price_6_plus']); } 
                    elseif ($qty >= 5 && floatval($v_data['price_5_plus']) > 0) { $unit_price = floatval($v_data['price_5_plus']); } 
                    elseif ($qty >= 4 && floatval($v_data['price_4_plus']) > 0) { $unit_price = floatval($v_data['price_4_plus']); }
                }
            }
            $total += ($unit_price * $qty);
        }
    }

    // Razorpay accepts amount in paise (multiply by 100)
    $amount_in_paise = round($total * 100);
    $receipt_id = 'ANT' . date('Ymd') . rand(1000, 9999);

    // Call Razorpay API using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'amount' => $amount_in_paise,
        'currency' => 'INR',
        'receipt' => $receipt_id
    ]));
    curl_setopt($ch, CURLOPT_USERPWD, $razorpay_key_id . ':' . $razorpay_key_secret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
    $result = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $response = json_decode($result, true);

    if ($http_status == 200 && isset($response['id'])) {
        echo json_encode([
            'status' => 'success',
            'order_id' => $response['id'], // Razorpay generated Order ID
            'amount' => $amount_in_paise,
            'key' => $razorpay_key_id
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create Razorpay order', 'error' => $response]);
    }
}
?>