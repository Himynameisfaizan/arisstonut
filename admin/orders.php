<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: auth/login.php");
    exit();
}

include "db-conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order_full'])) {
    $order_id_raw = intval($_POST['order_id']);
    $updated_status = htmlspecialchars(trim($_POST['order_status']));
    $payment_status = htmlspecialchars(trim($_POST['payment_status']));
    $courier = htmlspecialchars(trim($_POST['courier_partner']));
    $awb = htmlspecialchars(trim($_POST['awb_number']));

    // Update query with Tracking and Payment Status
    $update_query = "UPDATE `orders` SET `order_status` = ?, `payment_status` = ?, `courier_partner` = ?, `awb_number` = ? WHERE `id` = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $updated_status, $payment_status, $courier, $awb, $order_id_raw);

    if ($stmt->execute()) {
        echo "<script>alert('Order #$order_id_raw updated successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
    } else {
        echo "<script>alert('Failed to update order.');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin - Orders Registry | AristoNut</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">

    <?php include "links.php"; ?>
    <!-- Ensure FontAwesome is loaded -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Premium Table & Filter UI */
        .filter-bar {
            background: #F9F6F0; border-radius: 12px; padding: 15px 20px;
            margin-bottom: 20px; display: flex; gap: 15px; flex-wrap: wrap; align-items: center;
        }
        .filter-input {
            border: 1px solid #EADDCC; border-radius: 8px; padding: 10px 15px; font-size: 0.9rem; flex-grow: 1; min-width: 250px;
        }
        .filter-select {
            border: 1px solid #EADDCC; border-radius: 8px; padding: 10px 15px; font-size: 0.9rem; min-width: 180px;
        }
        
        .table-invoice thead th {
            background: #2D1B18 !important; color: #fff !important; text-transform: uppercase;
            font-size: 0.8rem; letter-spacing: 0.5px; padding: 12px; white-space: nowrap;
        }
        .table-invoice tbody td { vertical-align: middle; font-size: 0.85rem; padding: 15px 12px; }
        
        .badge-status-pill { font-size: 0.75rem; font-weight: 600; padding: 5px 12px; border-radius: 50px; letter-spacing: 0.5px; }
        
        /* Action Buttons (Fixed with FontAwesome) */
        .action-btn {
            width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;
            border-radius: 8px; border: none; transition: 0.3s; color: #fff; font-size: 1rem; cursor: pointer; text-decoration: none;
        }
        .btn-view { background: #3498db; } .btn-view:hover { background: #2980b9; transform: translateY(-2px); color: #fff; }
        .btn-edit { background: #f39c12; } .btn-edit:hover { background: #d35400; transform: translateY(-2px); color: #fff; }
        .btn-invoice { background: #2ecc71; } .btn-invoice:hover { background: #27ae60; transform: translateY(-2px); color: #fff; }

        /* Modal Custom UI */
        .modal-backdrop { z-index: 1040 !important; }
        .modal { z-index: 1050 !important; }
        .modal-header-custom { background: #FCFAF8; border-bottom: 2px dashed #eaddcf; }
        .info-card { background: #FFFFFF; border: 1px solid #EEEEEE; border-radius: 12px; padding: 15px; height: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
        .info-label { font-size: 0.75rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; display: block; }
        .info-data { font-size: 0.95rem; color: #333; font-weight: 600; margin-bottom: 12px; }
        .items-pre { background: #FDFBF8; border: 1px dashed #D2B48C; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; white-space: pre-wrap; color: #4A3326; }
    </style>
</head>

<body class="crm_body_bg">

<?php include "header.php"; ?>
    
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0 ">
                    <div class="header_iner d-flex justify-content-between align-items-center">
                        <div class="sidebar_icon d-lg-none"><i class="ti-menu"></i></div>
                        <div class="serach_field-area d-flex align-items-center">
                            <div class="search_inner">
                                <form action="#"><div class="search_field"><input type="text" placeholder="Global search..."></div><button type="submit"> <img src="assets/img/icon/icon_search.svg" alt> </button></form>
                            </div>
                        </div>
                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="profile_info">
                                <img src="assets/img/client_img.png" alt="#">
                                <div class="profile_info_iner">
                                    <div class="profile_author_name"><p>Store Manager</p><h5>Admin Panel</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main_content_iner ">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="white_card card_height_100 mb_30">
                            
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h3 class="m-0 fw-bold" style="color:#8B4513;"><i class="fa-solid fa-box-open me-2"></i> Master Orders Sheet</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="white_card_body">
                                
                                <!-- ================= SEARCH & FILTER BAR ================= -->
                                <div class="filter-bar">
                                    <input type="text" id="orderSearch" class="filter-input" placeholder="🔍 Search by Order ID, Name, or Phone...">
                                    
                                    <select id="statusFilter" class="filter-select">
                                        <option value="">All Statuses</option>
                                        <option value="Pending">⏳ Pending</option>
                                        <option value="Processing">⚙️ Processing</option>
                                        <option value="Shipped">🚚 Shipped</option>
                                        <option value="Delivered">✅ Delivered</option>
                                        <option value="Cancelled">❌ Cancelled</option>
                                    </select>

                                    <select id="paymentFilter" class="filter-select">
                                        <option value="">All Payments</option>
                                        <option value="Paid">💳 Paid</option>
                                        <option value="Pending">🕒 Unpaid (COD)</option>
                                    </select>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-invoice table-hover align-middle mb-0" id="ordersTable">
                                        <thead>
                                            <tr>
                                                <th>Order Info</th>
                                                <th>Customer</th>
                                                <th>Amount & Payment</th>
                                                <th>Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 200";
                                            $result = mysqli_query($conn, $sql);
                                            
                                            // Array to hold all modals HTML so they render OUTSIDE the table
                                            $modals_html = ""; 

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while($row = mysqli_fetch_assoc($result)){
                                                    
                                                    // Status Colors
                                                    $o_status = htmlspecialchars($row['order_status']);
                                                    $o_color = "bg-warning text-dark"; 
                                                    if (in_array($o_status, ['Completed', 'Delivered'])) $o_color = "bg-success text-white";
                                                    if ($o_status == 'Cancelled') $o_color = "bg-danger text-white";
                                                    if (in_array($o_status, ['Processing', 'Shipped', 'Dispatched'])) $o_color = "bg-info text-dark";

                                                    $p_status = htmlspecialchars($row['payment_status']);
                                                    $p_color = ($p_status == 'Paid') ? "text-success" : "text-danger";

                                                    // Split Address and Items
                                                    $raw_block = $row['customer_address'];
                                                    $parts = explode('--- Items List ---', $raw_block);
                                                    $clean_address = str_replace('--- Shipping Address ---', '', $parts[0]);
                                                    $clean_items = isset($parts[1]) ? trim($parts[1]) : 'No items listed';
                                            ?>
                                            <tr class="order-row" data-status="<?php echo $o_status; ?>" data-payment="<?php echo $p_status; ?>">
                                                
                                                <!-- Order Info -->
                                                <td>
                                                    <span class="fw-bold text-dark d-block search-target">#<?php echo htmlspecialchars($row['order_number']); ?></span>
                                                    <small class="text-muted"><i class="fa-regular fa-clock"></i> <?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></small>
                                                </td>
                                                
                                                <!-- Customer Info -->
                                                <td>
                                                    <span class="fw-bold text-dark d-block search-target"><?php echo htmlspecialchars($row['customer_name']); ?></span>
                                                    <small class="text-muted search-target"><?php echo htmlspecialchars($row['customer_phone']); ?></small>
                                                </td>

                                                <!-- Amount & Payment -->
                                                <td>
                                                    <span class="fw-bold fs-6 d-block">₹<?php echo htmlspecialchars($row['total_amount']); ?></span>
                                                    <small class="fw-bold <?php echo $p_color; ?>"><?php echo htmlspecialchars($row['payment_method']); ?> (<?php echo $p_status; ?>)</small>
                                                </td>

                                                <!-- Status -->
                                                <td>
                                                    <span class="badge badge-status-pill <?php echo $o_color; ?>"><?php echo $o_status; ?></span>
                                                    <?php if(!empty($row['awb_number'])): ?>
                                                        <small class="d-block mt-1 text-muted"><i class="fa-solid fa-truck-fast"></i> <?php echo htmlspecialchars($row['awb_number']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <!-- Actions -->
                                                <td class="text-center">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <button type="button" class="action-btn btn-view" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>" title="View Details">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>" title="Edit Order">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                        <a href="generate_bill.php?order_id=<?php echo $row['id']; ?>" class="action-btn btn-invoice" title="Download Invoice">
                                                            <i class="fa-solid fa-file-invoice-dollar"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <?php
                                            // ================= BUILD MODALS IN VARIABLE (To prevent dark screen issue) =================
                                            $modals_html .= '
                                            <!-- VIEW MODAL -->
                                            <div class="modal fade" id="viewModal'.$row['id'].'" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header modal-header-custom">
                                                            <h5 class="modal-title fw-bold" style="color:#8B4513;"><i class="fa-solid fa-receipt me-2"></i>Order Details: #'.htmlspecialchars($row['order_number']).'</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <div class="info-card">
                                                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-user me-2"></i>Customer Info</h6>
                                                                        <span class="info-label">Name</span><div class="info-data">'.htmlspecialchars($row['customer_name']).'</div>
                                                                        <span class="info-label">Email</span><div class="info-data"><a href="mailto:'.htmlspecialchars($row['customer_email']).'" class="text-decoration-none">'.htmlspecialchars($row['customer_email']).'</a></div>
                                                                        <span class="info-label">Phone</span><div class="info-data"><a href="tel:'.htmlspecialchars($row['customer_phone']).'" class="text-decoration-none">'.htmlspecialchars($row['customer_phone']).'</a></div>
                                                                        '.(!empty($row['alternate_phone']) ? '<span class="info-label">Alt Phone</span><div class="info-data">'.htmlspecialchars($row['alternate_phone']).'</div>' : '').'
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="info-card">
                                                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-location-dot me-2"></i>Shipping Address</h6>
                                                                        <div class="info-data" style="white-space: pre-wrap; font-size: 0.85rem; line-height: 1.6;">'.trim(htmlspecialchars($clean_address)).'</div>
                                                                        '.(!empty($row['order_notes']) ? '<span class="info-label text-danger mt-2">Order Notes</span><div class="info-data text-danger">'.htmlspecialchars($row['order_notes']).'</div>' : '').'
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="info-card">
                                                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-box-open me-2"></i>Items Ordered</h6>
                                                                        <pre class="items-pre">'.htmlspecialchars($clean_items).'</pre>
                                                                        <h5 class="text-end fw-bold text-success mt-2">Total Paid: ₹'.htmlspecialchars($row['total_amount']).'</h5>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="info-card">
                                                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-credit-card me-2"></i>Payment & Tracking Data</h6>
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <span class="info-label">Payment Mode</span><div class="info-data">'.htmlspecialchars($row['payment_method']).' ('.$p_status.')</div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <span class="info-label">Razorpay Order ID</span><div class="info-data">'.(!empty($row['razorpay_order_id']) ? htmlspecialchars($row['razorpay_order_id']) : 'N/A').'</div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <span class="info-label">Razorpay Payment ID</span><div class="info-data">'.(!empty($row['razorpay_payment_id']) ? htmlspecialchars($row['razorpay_payment_id']) : 'N/A').'</div>
                                                                            </div>
                                                                            <div class="col-md-6 mt-2">
                                                                                <span class="info-label">Courier Partner</span><div class="info-data text-primary fw-bold">'.(!empty($row['courier_partner']) ? htmlspecialchars($row['courier_partner']) : 'Not Assigned').'</div>
                                                                            </div>
                                                                            <div class="col-md-6 mt-2">
                                                                                <span class="info-label">Tracking AWB</span><div class="info-data text-primary fw-bold">'.(!empty($row['awb_number']) ? htmlspecialchars($row['awb_number']) : 'Not Assigned').'</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 bg-light">
                                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- EDIT MODAL -->
                                            <div class="modal fade" id="editModal'.$row['id'].'" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header modal-header-custom bg-light">
                                                            <h5 class="modal-title fw-bold"><i class="fa-solid fa-truck-fast me-2"></i>Update Order #'.htmlspecialchars($row['order_number']).'</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="POST">
                                                            <div class="modal-body text-start">
                                                                <input type="hidden" name="order_id" value="'.$row['id'].'">
                                                                
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-muted text-uppercase">Order Fulfillment Status</label>
                                                                    <select name="order_status" class="form-select">
                                                                        <option value="Pending" '.($o_status == 'Pending' ? 'selected' : '').'>⏳ Pending</option>
                                                                        <option value="Processing" '.($o_status == 'Processing' ? 'selected' : '').'>⚙️ Processing</option>
                                                                        <option value="Shipped" '.($o_status == 'Shipped' ? 'selected' : '').'>🚚 Shipped / Dispatched</option>
                                                                        <option value="Delivered" '.($o_status == 'Delivered' ? 'selected' : '').'>✅ Delivered</option>
                                                                        <option value="Cancelled" '.($o_status == 'Cancelled' ? 'selected' : '').'>❌ Cancelled</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-muted text-uppercase">Payment Status</label>
                                                                    <select name="payment_status" class="form-select">
                                                                        <option value="Pending" '.($p_status == 'Pending' ? 'selected' : '').'>Pending (Unpaid)</option>
                                                                        <option value="Paid" '.($p_status == 'Paid' ? 'selected' : '').'>Paid Successfully</option>
                                                                        <option value="Failed" '.($p_status == 'Failed' ? 'selected' : '').'>Failed / Refunded</option>
                                                                    </select>
                                                                </div>

                                                                <hr class="my-4 border-secondary-subtle">
                                                                <h6 class="fw-bold mb-3"><i class="fa-solid fa-box me-2"></i>Logistics & Tracking</h6>

                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold small text-muted text-uppercase">Courier Partner</label>
                                                                    <input type="text" name="courier_partner" class="form-control" placeholder="e.g. BlueDart, Delhivery" value="'.htmlspecialchars($row['courier_partner'] ?? '').'">
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold small text-muted text-uppercase">Tracking AWB Number</label>
                                                                    <input type="text" name="awb_number" class="form-control" placeholder="Enter Tracking Number" value="'.htmlspecialchars($row['awb_number'] ?? '').'">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light border-0">
                                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="update_order_full" class="btn btn-success rounded-pill px-4">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>';
                                            ?>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center py-5 text-muted fw-bold'><i class='fa-solid fa-inbox fs-1 d-block mb-2'></i>No orders found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php 
        // PRINT MODALS HERE - THIS FIXES THE DARK SCREEN ISSUE!
        echo $modals_html; 
        
        include "footer.php"; 
        ?>
    </section>

    <!-- Essential Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- REAL-TIME JS FILTER ENGINE -->
    <script>
        $(document).ready(function() {
            // Function to filter rows
            function filterOrders() {
                var searchText = $('#orderSearch').val().toLowerCase();
                var statusFilter = $('#statusFilter').val();
                var paymentFilter = $('#paymentFilter').val();

                $('.order-row').each(function() {
                    var row = $(this);
                    var textContent = row.find('.search-target').text().toLowerCase();
                    var rowStatus = row.data('status');
                    var rowPayment = row.data('payment');

                    var matchesSearch = textContent.indexOf(searchText) > -1;
                    var matchesStatus = statusFilter === "" || rowStatus === statusFilter;
                    var matchesPayment = paymentFilter === "" || rowPayment === paymentFilter;

                    if (matchesSearch && matchesStatus && matchesPayment) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            }

            // Bind events
            $('#orderSearch').on('keyup', filterOrders);
            $('#statusFilter, #paymentFilter').on('change', filterOrders);
        });
    </script>
</body>
</html>