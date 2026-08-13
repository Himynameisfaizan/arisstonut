<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php'); // Global path configuration and absolute session path lock
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status - AristoNut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/logo.webp">
    
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="container text-center">
        <div class="status-box mx-auto" style="max-width: 500px;">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {

                // 1. Session Cart Empty Check validation layer
                if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                    echo '<i class="bi bi-cart-x text-warning display-1"></i>
                      <h2 class="mt-3 text-brown fw-bold">Your Cart is Empty</h2>
                      <p class="text-muted small">Please add some fresh makhana items before attempting checkout.</p>
                      <a href="' . $site . 'product.php" class="btn btn-home mt-3">Browse Products</a>';
                    exit();
                }

                // 2. Form Variables Sanitization (Mapping fields accurately from form inputs)
                $f_name   = htmlspecialchars(trim($_POST['first_name']));
                $l_name   = htmlspecialchars(trim($_POST['last_name']));
                $full_name = htmlspecialchars(trim($f_name . ' ' . $l_name));
                $email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
                $phone     = htmlspecialchars(trim($_POST['phone']));
                $raw_address = htmlspecialchars(trim($_POST['address']));
                $pay_mode  = htmlspecialchars(trim($_POST['payment_method']));

                // Optional fields fallback handling (Checking if they exist in your HTML form layout)
                $city    = isset($_POST['city']) ? htmlspecialchars(trim($_POST['city'])) : 'N/A';
                $pincode = isset($_POST['pincode']) ? htmlspecialchars(trim($_POST['pincode'])) : 'N/A';

                // 3. Generate Strict DB Matched Variables
                $order_number = 'ANT' . date('Ymd') . rand(100, 999); // Maps to `order_number`
                $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; // Default 0 for guest shoppers
                $order_status = 'Pending';
                $payment_status = 'Pending';

                $product_list = "";
                $total = 0;

                // 4. Calculate final values tracking prices dynamically from live table state row configurations
                foreach ($_SESSION['cart'] as $pid => $item) {
                    $pid = intval($pid);
                    $query = $conn->query("SELECT pro_name, selling_price FROM products WHERE id = '$pid' LIMIT 1");

                    if ($query && $query->num_rows > 0) {
                        $p = $query->fetch_assoc();
                        $product_list .= $p['pro_name'] . " (x" . $item['quantity'] . ") - Price: ₹" . $p['selling_price'] . "\n";
                        $total += (floatval($p['selling_price']) * intval($item['quantity']));
                    }
                }

                // Chunki aapki orders table mein separate items structure database column nahi hai, 
                // isliye data secure rakhne ke liye hum list ko clear description breakdown ke sath customer_address field mein concat kar rahe hain.
                $final_address_block = "--- Shipping Address ---\n" . $raw_address . "\n\n--- Items List ---\n" . $product_list;

                // 5. Schema Exact Matched SQL Prepared Statement Binding Pipeline
                // Columns sequence: user_id, total_amount, order_number, customer_name, customer_email, customer_phone, customer_address, customer_city, customer_pincode, payment_method, grand_total, order_status, payment_status
                $stmt = $conn->prepare("INSERT INTO `orders` (`user_id`, `total_amount`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `customer_city`, `customer_pincode`, `payment_method`, `grand_total`, `order_status`, `payment_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Types mapping structure: i = integer, d = decimal/double, s = string, f = float
                // sequence format context: i, d, s, s, s, s, s, s, s, s, d, s, s
                $stmt->bind_param("idssssssssdss", $user_id, $total, $order_number, $full_name, $email, $phone, $final_address_block, $city, $pincode, $pay_mode, $total, $order_status, $payment_status);

                if ($stmt->execute()) {
                    // Flash clear cart sessions on storage layer execution complete match
                    unset($_SESSION['cart']);

                    echo '<i class="bi bi-check-circle-fill text-success" style="font-size: 4.5rem;"></i>
                      <h2 class="mt-4 text-brown fw-bold">Order Placed Successfully!</h2>
                      <p class="text-muted mt-2">Thank you for choosing AristoNut. Your healthy crisp snacking pack routing pipeline has been verified.</p>
                      <div class="p-3 my-3 rounded-3 text-start bg-light" style="border:1px solid #f5e6d3;">
                          <div class="small text-muted mb-1"><strong>Tracking Order No:</strong> <span class="text-dark fw-medium">' . $order_number . '</span></div>
                          <div class="small text-muted"><strong>Total Bill Value:</strong> <span class="text-success fw-bold">₹' . $total . '</span></div>
                      </div>
                      <a href="' . $site . 'index.php" class="btn btn-home mt-2 w-100">Continue Snacking</a>';
                } else {
                    // Secure Error logging metrics fallback trace
                    error_log("Database crash trace info constraints: " . $stmt->error);

                    echo '<i class="bi bi-x-circle-fill text-danger" style="font-size: 4.5rem;"></i>
                      <h2 class="mt-4 text-danger fw-bold">Transaction Failed</h2>
                      <p class="text-muted mt-2">Internal server configuration constraints blocked input dataset streams compilation rules.</p>
                      <a href="' . $site . 'cart.php" class="btn btn-outline-danger mt-3 px-4 rounded-pill">Back to Cart</a>';
                }

                $stmt->close();
            } else {
                // Direct file penetration prevention redirect mapping standard context execution
                header("Location: " . $site . "index.php");
                exit();
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>