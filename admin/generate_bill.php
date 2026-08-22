<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Admin database configuration connection
include "db-conn.php"; 

if (!isset($_GET['order_id']) || empty(trim($_GET['order_id']))) {
    echo "<script>alert('Invalid Order ID tracking reference.'); window.location.href='orders.php';</script>";
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order details matching database columns
$query = "SELECT * FROM `orders` WHERE `id` = '$order_id' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<h2 style='text-align:center; margin-top:50px; color:#dc3545; font-family:sans-serif;'>Order Registry Data Not Found!</h2>";
    exit();
}

$order = mysqli_fetch_assoc($result);

// --- ADVANCED STRING PARSER FOR SHIPPING ADDRESS & ITEMS TABLE ---
$full_address_data = $order['customer_address'];
$clean_shipping_address = "";
$items_array = [];

// "--- Items List ---" key matrix se boundary split karna
$address_parts = explode("--- Items List ---", $full_address_data);

if (count($address_parts) > 1) {
    // 1. Extract clean address block
    $clean_shipping_address = str_replace("--- Shipping Address ---", "", $address_parts[0]);
    $clean_shipping_address = trim($clean_shipping_address);
    
    // 2. Extract lines items text
    $raw_items_text = trim($address_parts[1]);
    $items_lines = explode("\n", $raw_items_text);
    
    // Regex se product text components trace karna: Name, Quantity (xN), Price (₹N)
    foreach ($items_lines as $line) {
        $line = trim($line);
        if (!empty($line) && preg_match('/^(.*)\s\(x(\d+)\)\s-\sPrice:\s₹(\d+(?:\.\d+)?)/', $line, $matches)) {
            $items_array[] = [
                'name' => trim($matches[1]),
                'qty'  => intval($matches[2]),
                'rate' => floatval($matches[3])
            ];
        }
    }
} else {
    // Redundant Fallback structure
    $clean_shipping_address = $full_address_data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Bill - #<?php echo htmlspecialchars($order['order_number']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; color: #333; }
        .invoice-card { background: #fff; border-radius: 0px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.02); padding: 45px; }
        .brand-color { color: #8B4513; }
        .invoice-header { border-bottom: 2px solid #F5E6D3; padding-bottom: 20px; margin-bottom: 30px; }
        
        /* Premium Billing Table Theming Styles */
        .table-invoice thead th { background: #2D1B18 !important; color: #fff !important; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 12px; }
        .table-invoice tbody td { padding: 12px; font-size: 0.9rem; color: #444; border-bottom: 1px solid #f1f5f9; }
        
        .info-block h6 { font-weight: 700; color: #2D1B18; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; margin-bottom: 10px; }
        .info-block p { font-size: 0.9rem; line-height: 1.6; color: #555; }
        
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; padding: 0 !important; }
            .invoice-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
            .container { max-width: 100% !important; width: 100% !important; }
        }
    </style>
</head>
<body>

<div class="container my-4 no-print text-end">
    <a href="javascript:history.back();" class="btn btn-sm btn-secondary rounded-pill px-3 me-2"><i class="bi bi-arrow-left me-1"></i> Back to Panel</a>
    <button onclick="window.print();" class="btn btn-sm btn-success rounded-pill px-4"><i class="bi bi-printer-fill me-1"></i> Print / Save PDF</button>
</div>

<div class="container mb-5">
    <div class="invoice-card mx-auto" style="max-width: 850px;">
        
        <div class="row invoice-header align-items-center">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <h2 class="fw-bold brand-color m-0">AristoNut</h2>
                <small class="text-muted text-uppercase tracking-wider" style="font-size:0.75rem; letter-spacing:2px;">Premium Quality Snacking</small>
                <p class="text-muted small mt-2 mb-0">Subhankarpur, Darbhanga, Bihar-846004<br>Email: aristonut@gmail.com</p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <h1 class="text-uppercase fw-light text-muted m-0" style="letter-spacing: 3px;">Invoice</h1>
                <div class="mt-2 small">
                    <span class="text-muted">Order Number:</span> <span class="font-monospace fw-bold"><?php echo htmlspecialchars($order['order_number']); ?></span><br>
                    <span class="text-muted">Date Generated:</span> <span class="text-dark"><?php echo htmlspecialchars($order['created_at']); ?></span>
                </div>
            </div>
        </div>

        <div class="row info-block mb-4 g-4">
            <div class="col-sm-6">
                <h6>Billed To (Customer):</h6>
                <p>
                    <strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?><br>
                    <strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?><br>
                    <strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?>
                </p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <h6>Shipping Logistics Details:</h6>
                <p>
                    <strong>City:</strong> <?php echo htmlspecialchars($order['customer_city']); ?><br>
                    <strong>ZIP/PIN Code:</strong> <?php echo htmlspecialchars($order['customer_pincode']); ?><br>
                    <strong>Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?> (<span class="fw-medium"><?php echo htmlspecialchars($order['payment_status']); ?></span>)
                </p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0 p-3" style="border-radius:10px;">
                    <h6 class="fw-bold mb-1 text-uppercase text-secondary" style="font-size:0.75rem; letter-spacing:0.5px;">Delivery Address:</h6>
                    <div style="font-size:0.9rem; white-space: pre-wrap;" class="text-dark"><?php echo $clean_shipping_address; ?></div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <table class="table table-invoice align-middle">
                    <thead>
                        <tr>
                            <th style="width: 8%;">#</th>
                            <th>Product Description</th>
                            <th class="text-center" style="width: 15%;">Rate</th>
                            <th class="text-center" style="width: 12%;">Qty</th>
                            <th class="text-end" style="width: 18%;">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($items_array)) {
                            $count = 1;
                            foreach ($items_array as $item) {
                                $line_total = $item['rate'] * $item['qty'];
                                ?>
                                <tr>
                                    <td class="text-muted font-monospace"><?php echo $count++; ?></td>
                                    <td class="fw-medium text-dark"><?php echo $item['name']; ?></td>
                                    <td class="text-center">₹<?php echo number_format($item['rate'], 2); ?></td>
                                    <td class="text-center fw-bold"><?php echo $item['qty']; ?></td>
                                    <td class="text-end fw-bold text-dark">₹<?php echo number_format($line_total, 2); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            // Fallback array handling
                            ?>
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Items parsed matrix empty. Refer to address block info logs.</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row justify-content-end mt-4">
            <div class="col-md-5 col-sm-7">
                <table class="table table-sm table-borderless align-middle small">
                    <tbody>
                        <tr>
                            <td class="text-muted text-start py-2">Subtotal Amount:</td>
                            <td class="text-dark fw-medium text-end py-2">₹<?php echo htmlspecialchars($order['total_amount']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted text-start py-2">Shipping Charges:</td>
                            <td class="text-success text-end py-2">FREE</td>
                        </tr>
                        <tr class="border-top border-dark-subtle">
                            <td class="text-dark fw-bold text-start py-3" style="font-size:1.05rem;">Grand Total Price:</td>
                            <td class="brand-color fw-bold text-end py-3" style="font-size:1.2rem;">₹<?php echo htmlspecialchars($order['grand_total'] ? $order['grand_total'] : $order['total_amount']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-5 pt-4 border-top border-light-subtle">
            <h6 class="fw-bold m-0" style="color:#2D1B18;">Thank You For Your Business!</h6>
            <small class="text-muted mt-1 d-block" style="font-size:0.8rem;">This is an electronically generated invoice token. No physical signatures are mandatory.</small>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>