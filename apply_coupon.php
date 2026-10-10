<?php
session_start();
include('config/connect.php');

if(isset($_POST['coupon_code']) && isset($_POST['cart_total'])) {
    $code = mysqli_real_escape_string($conn, strtoupper(trim($_POST['coupon_code'])));
    $cart_total = floatval($_POST['cart_total']);
    $today = date('Y-m-d');

    $query = mysqli_query($conn, "SELECT * FROM coupons WHERE coupon_code = '$code' AND status = 1");
    
    if(mysqli_num_rows($query) > 0) {
        $coupon = mysqli_fetch_assoc($query);

        if($coupon['expiry_date'] < $today) {
            echo json_encode(['status' => 'error', 'message' => 'This coupon has expired!']);
            exit;
        }

        if($coupon['used_count'] >= $coupon['usage_limit']) {
            echo json_encode(['status' => 'error', 'message' => 'Coupon usage limit reached!']);
            exit;
        }

        if($cart_total < $coupon['min_cart_value']) {
            echo json_encode(['status' => 'error', 'message' => 'Minimum cart value should be ₹'.$coupon['min_cart_value']]);
            exit;
        }

        $discount_amount = 0;
        if($coupon['discount_type'] == 'flat') {
            $discount_amount = $coupon['discount_value'];
        } else {
            $discount_amount = ($cart_total * $coupon['discount_value']) / 100;
        }

        if($discount_amount > $cart_total) {
            $discount_amount = $cart_total;
        }

        $_SESSION['applied_coupon'] = $code;
        $_SESSION['discount_amount'] = $discount_amount;

        echo json_encode([
            'status' => 'success', 
            'message' => 'Coupon applied successfully!',
            'discount' => $discount_amount
        ]);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or inactive coupon code!']);
    }
}
?>