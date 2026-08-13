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
    $pid = intval($_POST['product_id']); // Sanitize integer inputs parameter metrics

    if ($pid > 0) {
        // Agar cart array mapping index layout session me zero value parse ho, to build karein
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check verification matrix parameter matching arrays sequence lookup code rule
        if (array_key_exists($pid, $_SESSION['cart'])) {
            $_SESSION['cart'][$pid]['quantity']++;
        } else {
            $_SESSION['cart'][$pid] = [
                'id' => $pid,
                'quantity' => 1
            ];
        }

        // Global count matrix calculation for header element dynamic integration badge nodes
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
            'message' => 'Exception core failure parameters sequence: Invalid Product ID mapping configuration context rule.'
        ]);
    }
    exit();
}

// ==========================================
// 2. LIVE QUANTITY INCREMENT DECREMENT HANDLING 
// ==========================================
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $pid = intval($_POST['product_id']);
    $type = $_POST['type']; // Valid variables: 'inc' (plus) OR 'dec' (minus)

    if (isset($_SESSION['cart'][$pid])) {
        if ($type == 'inc') {
            $_SESSION['cart'][$pid]['quantity']++;
        } elseif ($type == 'dec') {
            if ($_SESSION['cart'][$pid]['quantity'] > 1) {
                $_SESSION['cart'][$pid]['quantity']--;
            }
        }

        // Single unit lookup evaluation parsing pipeline configuration
        $query = $conn->query("SELECT selling_price FROM products WHERE id = '$pid'");
        $p = $query->fetch_assoc();

        $new_subtotal = $p['selling_price'] * $_SESSION['cart'][$pid]['quantity'];

        // Final Global compute parsing logic block summary calculation metrics
        $grand_total = 0;
        $total_items = 0;
        foreach ($_SESSION['cart'] as $id => $item) {
            $q = $conn->query("SELECT selling_price FROM products WHERE id = '$id'");
            $prod = $q->fetch_assoc();
            $grand_total += ($prod['selling_price'] * $item['quantity']);
            $total_items += $item['quantity'];
        }

        echo json_encode([
            'status' => 'success',
            'quantity' => $_SESSION['cart'][$pid]['quantity'],
            'subtotal' => number_format($new_subtotal, 2),
            'grand_total' => number_format($grand_total, 2),
            'cart_count' => $total_items
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Product logic sequence routing breach: Target ID data node block mismatch parameters mapping evaluation.'
        ]);
    }
    exit();
}

// ==========================================
// 3. REMOVE ITEM FROM CART (AJAX CONTROLLER INTERFACE)
// ==========================================
if (isset($_POST['action']) && $_POST['action'] == 'remove_item') {
    $pid = intval($_POST['product_id']);

    if (isset($_SESSION['cart'][$pid])) {
        unset($_SESSION['cart'][$pid]); // Explicit clearing row value attributes data array elements logic

        // Re-indexing calculation summary update triggers mapping context evaluation layers
        $grand_total = 0;
        $total_items = 0;
        foreach ($_SESSION['cart'] as $id => $item) {
            $q = $conn->query("SELECT selling_price FROM products WHERE id = '$id'");
            $prod = $q->fetch_assoc();
            $grand_total += ($prod['selling_price'] * $item['quantity']);
            $total_items += $item['quantity'];
        }

        echo json_encode([
            'status' => 'success',
            'cart_empty' => empty($_SESSION['cart']),
            'grand_total' => number_format($grand_total, 2),
            'cart_count' => $total_items
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Removal process deployment aborted: System entity allocation mapping mismatch variables logs parameters.'
        ]);
    }
    exit();
}

// Fallback protection redirection code routing layers structure configurations
echo json_encode([
    'status' => 'bad_request',
    'message' => 'Direct internal runtime parsing script configuration bypass execution strategy blocked.'
]);
exit();
