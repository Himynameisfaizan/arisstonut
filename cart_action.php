<?php
// Session safety routing layers mapping logic
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database module integration layer mapping configuration
include('config/connect.php');

header('Content-Type: application/json');

// ==========================================
// 1. ADD TO CART SYSTEM EXECUTION MODULE
// ==========================================
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $pid = intval($_POST['product_id']); 
    $vid = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0; // NEW: Receive Variation ID
    $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // NEW: Receive Custom Quantity

    if ($pid > 0) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Cart Key: Agar variation hai to 'pid_vid' banega (e.g. 14_2), warna sirf 'pid' (e.g. 14)
        $cart_key = ($vid > 0) ? $pid . '_' . $vid : $pid;

        // Sequence lookup verification
        if (array_key_exists($cart_key, $_SESSION['cart'])) {
            $_SESSION['cart'][$cart_key]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$cart_key] = [
                'id' => $pid,
                'variation_id' => $vid,
                'quantity' => $qty
            ];
        }

        // Update header count
        $total_items = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_items += $item['quantity'];
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Item successfully initialized in session container matrix.',
            'cart_count' => $total_items
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Product ID mapping configuration context rule.'
        ]);
    }
    exit();
}

// ==========================================
// 2. LIVE QUANTITY INCREMENT DECREMENT HANDLING 
// ==========================================
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $cart_key = $_POST['product_id']; // This is now cart_key (e.g. 14_2)
    $type = $_POST['type']; 

    if (isset($_SESSION['cart'][$cart_key])) {
        if ($type == 'inc') {
            $_SESSION['cart'][$cart_key]['quantity']++;
        } elseif ($type == 'dec') {
            if ($_SESSION['cart'][$cart_key]['quantity'] > 1) {
                $_SESSION['cart'][$cart_key]['quantity']--;
            }
        }

        // Product Pricing Logic 
        $pid = $_SESSION['cart'][$cart_key]['id'];
        $vid = $_SESSION['cart'][$cart_key]['variation_id'];
        $curr_qty = $_SESSION['cart'][$cart_key]['quantity'];
        
        $item_price = 0;

        if($vid > 0) {
            // Variation Pricing Logic
            $q = $conn->query("SELECT * FROM product_variations WHERE id = '$vid'");
            $v_data = $q->fetch_assoc();
            
            $item_price = $v_data['single_price'];
            if($curr_qty >= 6 && $v_data['price_6_plus'] > 0) $item_price = $v_data['price_6_plus'];
            elseif($curr_qty >= 5 && $v_data['price_5_plus'] > 0) $item_price = $v_data['price_5_plus'];
            elseif($curr_qty >= 4 && $v_data['price_4_plus'] > 0) $item_price = $v_data['price_4_plus'];
        } else {
            // Fallback Main Product Pricing
            $query = $conn->query("SELECT selling_price FROM products WHERE id = '$pid'");
            $p = $query->fetch_assoc();
            $item_price = $p['selling_price'];
        }

        $new_subtotal = $item_price * $curr_qty;

        // Compute Grand Total
        $grand_total = 0;
        $total_items = 0;
        foreach ($_SESSION['cart'] as $k => $item) {
            $i_pid = $item['id'];
            $i_vid = $item['variation_id'];
            $i_qty = $item['quantity'];
            
            $i_price = 0;
            if($i_vid > 0) {
                $q = $conn->query("SELECT * FROM product_variations WHERE id = '$i_vid'");
                $vd = $q->fetch_assoc();
                $i_price = $vd['single_price'];
                if($i_qty >= 6 && $vd['price_6_plus'] > 0) $i_price = $vd['price_6_plus'];
                elseif($i_qty >= 5 && $vd['price_5_plus'] > 0) $i_price = $vd['price_5_plus'];
                elseif($i_qty >= 4 && $vd['price_4_plus'] > 0) $i_price = $vd['price_4_plus'];
            } else {
                $q = $conn->query("SELECT selling_price FROM products WHERE id = '$i_pid'");
                $pd = $q->fetch_assoc();
                $i_price = $pd['selling_price'];
            }

            $grand_total += ($i_price * $i_qty);
            $total_items += $i_qty;
        }

        echo json_encode([
            'status' => 'success',
            'quantity' => $curr_qty,
            'subtotal' => number_format($new_subtotal, 2),
            'grand_total' => number_format($grand_total, 2),
            'cart_count' => $total_items
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}

// ==========================================
// 3. REMOVE ITEM FROM CART
// ==========================================
if (isset($_POST['action']) && $_POST['action'] == 'remove_item') {
    $cart_key = $_POST['product_id'];

    if (isset($_SESSION['cart'][$cart_key])) {
        unset($_SESSION['cart'][$cart_key]); 

        $grand_total = 0;
        $total_items = 0;
        
        // Loop again for Grand Total Update (Same loop logic as above)
        foreach ($_SESSION['cart'] as $k => $item) {
             $i_pid = $item['id'];
             $i_vid = $item['variation_id'];
             $i_qty = $item['quantity'];
             
             $i_price = 0;
             if($i_vid > 0) {
                 $q = $conn->query("SELECT * FROM product_variations WHERE id = '$i_vid'");
                 $vd = $q->fetch_assoc();
                 $i_price = $vd['single_price'];
                 if($i_qty >= 6 && $vd['price_6_plus'] > 0) $i_price = $vd['price_6_plus'];
                 elseif($i_qty >= 5 && $vd['price_5_plus'] > 0) $i_price = $vd['price_5_plus'];
                 elseif($i_qty >= 4 && $vd['price_4_plus'] > 0) $i_price = $vd['price_4_plus'];
             } else {
                 $q = $conn->query("SELECT selling_price FROM products WHERE id = '$i_pid'");
                 $pd = $q->fetch_assoc();
                 $i_price = $pd['selling_price'];
             }
 
             $grand_total += ($i_price * $i_qty);
             $total_items += $i_qty;
        }

        echo json_encode([
            'status' => 'success',
            'cart_empty' => empty($_SESSION['cart']),
            'grand_total' => number_format($grand_total, 2),
            'cart_count' => $total_items
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}

echo json_encode(['status' => 'bad_request']);
exit();
?>