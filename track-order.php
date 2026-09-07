<?php
session_start();
include('config/connect.php');

$pageTitle = "Track Order";
include('inc/header.php');
include('inc/breadcrumb.php');

$search_query = "";
$orders_found = false;
$order_results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_track'])) {
    $search_query = $conn->real_escape_string(trim($_POST['track_input']));
    
    // Check if input matches email OR phone
    $query = $conn->prepare("SELECT * FROM `orders` WHERE `customer_email` = ? OR `customer_phone` = ? ORDER BY `id` DESC");
    $query->bind_param("ss", $search_query, $search_query);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $orders_found = true;
        while ($row = $result->fetch_assoc()) {
            $order_results[] = $row;
        }
    }
}
?>

<style>
    .track-wrapper { padding: 80px 0 120px 0; background-color: #FCFAF8; min-height: 65vh; }
    
    .track-search-box {
        background: #FFFFFF; border-radius: 24px; padding: 40px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02);
        max-width: 600px; margin: 0 auto; text-align: center;
    }
    
    .track-input {
        background: #F9F9F9; border: 1px solid transparent; border-radius: 50px;
        padding: 16px 25px; font-family: 'Inter', sans-serif; width: 100%; transition: 0.3s;
    }
    .track-input:focus { background: #FFFFFF; border-color: #9C5521; box-shadow: 0 0 0 4px rgba(156, 85, 33, 0.1); outline: none; }
    
    .btn-track {
        background: #9C5521; color: #FFFFFF; border: none; padding: 16px 35px; border-radius: 50px;
        font-family: 'Inter', sans-serif; font-weight: 700; transition: 0.3s; width: 100%; margin-top: 15px;
    }
    .btn-track:hover { background: #7A4219; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(156, 85, 33, 0.2); }

    /* Order Card UI */
    .order-card {
        background: #FFFFFF; border-radius: 16px; padding: 25px; margin-bottom: 20px;
        border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 5px 20px rgba(0,0,0,0.02);
    }
    .order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed rgba(0,0,0,0.1); padding-bottom: 15px; margin-bottom: 15px; }
    .order-no { font-family: 'Poppins', sans-serif; font-size: 1.2rem; font-weight: 700; color: #2C1E16; }
    .order-date { font-family: 'Inter', sans-serif; font-size: 0.85rem; color: #6B5B53; }
    
    .badge-status { padding: 6px 15px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    .status-pending { background: #FFF3CD; color: #D35400; }
    .status-processing { background: #D1ECF1; color: #2980B9; }
    .status-shipped { background: #E8DAEF; color: #8E44AD; }
    .status-delivered { background: #D5F5E3; color: #27AE60; }

    .order-details pre { font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #4A3326; background: #F9F6F0; padding: 15px; border-radius: 8px; white-space: pre-wrap; }
</style>

<main class="track-wrapper container">
    
    <?php if (!$orders_found && empty($search_query)): ?>
        <!-- Search Box -->
        <div class="track-search-box">
            <i class="bi bi-box-seam text-brown display-3 mb-3 d-block"></i>
            <h2 class="fw-bold text-brown mb-2" style="font-family: 'Poppins', sans-serif;">Track Your Orders</h2>
            <p class="text-muted mb-4">Enter your registered Email or Phone Number to view your current and past orders.</p>
            
            <form method="POST" action="">
                <input type="text" name="track_input" class="track-input" placeholder="Email Address or Phone Number" required>
                <button type="submit" name="search_track" class="btn-track">Find My Orders <i class="bi bi-search ms-2"></i></button>
            </form>
        </div>
    <?php endif; ?>

    <?php if (!empty($search_query)): ?>
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h3 class="fw-bold text-brown">Results for "<?php echo htmlspecialchars($search_query); ?>"</h3>
            <a href="track-order.php" class="btn btn-sm btn-outline-secondary rounded-pill">Search Another</a>
        </div>

        <?php if ($orders_found): ?>
            <div class="row">
                <?php foreach ($order_results as $order): 
                    // Set Status Badge Color
                    $status_class = "status-pending";
                    $status = strtolower($order['order_status']);
                    if ($status == 'processing') $status_class = "status-processing";
                    if ($status == 'shipped' || $status == 'dispatched') $status_class = "status-shipped";
                    if ($status == 'delivered') $status_class = "status-delivered";
                ?>
                    <div class="col-lg-6">
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <div class="order-no"><?php echo $order['order_number']; ?></div>
                                    <div class="order-date">Ordered on: <?php echo date("F j, Y", strtotime($order['created_at'])); ?></div>
                                </div>
                                <span class="badge-status <?php echo $status_class; ?>"><?php echo $order['order_status']; ?></span>
                            </div>
                            
                            <div class="order-details">
                                <div class="d-flex justify-content-between mb-2 fw-bold text-dark">
                                    <span>Total Amount:</span>
                                    <span>₹<?php echo number_format($order['grand_total'], 2); ?> (<?php echo $order['payment_method']; ?> - <?php echo $order['payment_status']; ?>)</span>
                                </div>
                                <!-- Address & Item List -->
                                <pre><?php echo htmlspecialchars($order['customer_address']); ?></pre>
                                
                                <!-- Courier Tracking if Available -->
                                <?php if (!empty($order['awb_number'])): ?>
                                    <div class="mt-3 p-3 bg-light rounded border border-success">
                                        <p class="mb-1 fw-bold text-success"><i class="bi bi-truck me-2"></i>Tracking Details</p>
                                        <small class="d-block text-muted">Courier: <strong><?php echo $order['courier_partner']; ?></strong></small>
                                        <small class="d-block text-muted">AWB/Tracking No: <strong><?php echo $order['awb_number']; ?></strong></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- No Orders Found -->
            <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                <i class="bi bi-search text-muted display-1 mb-3 d-block"></i>
                <h3 class="fw-bold text-dark">No Orders Found</h3>
                <p class="text-muted">We couldn't find any orders linked to "<?php echo htmlspecialchars($search_query); ?>".</p>
                <a href="product.php" class="btn text-white px-4 py-2 mt-3 rounded-pill" style="background: #9C5521;">Start Shopping</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php include('inc/footer.php'); ?>