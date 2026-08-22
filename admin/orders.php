<?php
// 1. Top Processing Event Trigger Module Block (Sabse pehle load hona zaroori hai)
include "db-conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_fulfillment'])) {
    $order_id_raw = intval($_POST['order_id']);
    $updated_status = htmlspecialchars(trim($_POST['status_select']));

    // Agar status 'Completed' hota hai, to rule ke mutabik payment_status ko bhi 'Paid' update karenge
    if ($updated_status == 'Completed') {
        $update_query = "UPDATE `orders` SET `order_status` = ?, `payment_status` = 'Paid' WHERE `id` = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $updated_status, $order_id_raw);
    } else {
        $update_query = "UPDATE `orders` SET `order_status` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $updated_status, $order_id_raw);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Fulfillment status updated successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
    } else {
        echo "<script>alert('Failed to compile execution metrics parameters.');</script>";
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
    <style>
        /* Table data alignment custom adjustments */
        .address-text-box {
            max-width: 250px;
            white-space: pre-wrap;
            font-size: 0.85rem;
            line-height: 1.5;
        }
        .badge-status-pill {
            font-size: 0.8rem;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 30px;
            display: inline-block;
        }
        .table-invoice thead th {
            background: #2D1B18 !important;
            color: #fff !important;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body class="crm_body_bg">

<?php include "header.php"; ?>
    
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0 ">
                    <div class="header_iner d-flex justify-content-between align-items-center">
                        <div class="sidebar_icon d-lg-none">
                            <i class="ti-menu"></i>
                        </div>
                        <div class="serach_field-area d-flex align-items-center">
                            <div class="search_inner">
                                <form action="#">
                                    <div class="search_field">
                                        <input type="text" placeholder="Search orders registry...">
                                    </div>
                                    <button type="submit"> <img src="assets/img/icon/icon_search.svg" alt> </button>
                                </form>
                            </div>
                            <span class="f_s_14 f_w_400 ml_25 white_text text_white">Apps</span>
                        </div>
                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="header_notification_warp d-flex align-items-center">
                                <li>
                                    <a class="bell_notification_clicker nav-link-notify" href="#"> <img src="assets/img/icon/bell.svg" alt></a>
                                    <div class="Menu_NOtification_Wrap">
                                        <div class="notification_Header">
                                            <h4>Notifications</h4>
                                        </div>
                                        <div class="Notification_body">
                                            <div class="single_notify d-flex align-items-center">
                                                <div class="notify_thumb">
                                                    <a href="#"><img src="assets/img/staf/2.png" alt></a>
                                                </div>
                                                <div class="notify_content">
                                                    <a href="#"><h5>New Order Placed</h5></a>
                                                    <p>Check the latest pending arrivals</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nofity_footer">
                                            <div class="submit_button text-center pt_20">
                                                <a href="#" class="btn_1">See More</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a class="CHATBOX_open nav-link-notify" href="#"> <img src="assets/img/icon/msg.svg" alt> </a>
                                </li>
                            </div>
                            <div class="profile_info">
                                <img src="assets/img/client_img.png" alt="#">
                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <p>Store Manager</p>
                                        <h5>Admin Panel</h5>
                                    </div>
                                    <div class="profile_info_details">
                                        <a href="#">My Profile</a>
                                        <a href="#">Settings</a>
                                        <a href="#">Log Out</a>
                                    </div>
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
                                        <h3 class="m-0 fw-bold" style="color:#8B4513;">AristoNut Master Orders Sheet</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <div style="overflow-x: auto; width: 100%; padding-top: 10px;">
                                    <table class="table table-invoice table-striped table-hover align-middle">
                                        <thead class="text-uppercase small">
                                            <tr>
                                                <th scope="col">#ID</th>
                                                <th scope="col">Order Number</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Email ID</th>
                                                <th scope="col">Phone No.</th>
                                                <th scope="col">Shipping Address & Products</th>
                                                <th scope="col">City</th>
                                                <th scope="col">Postal Code</th>
                                                <th scope="col">Payment Mode</th>
                                                <th scope="col">Total Amount</th>
                                                <th scope="col">Order Date</th>
                                                <th scope="col" class="text-center" style="min-width: 180px;">Fulfillment Option</th>
                                                <th scope="col" class="text-center">Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            <?php
                                            // Exact tracking limit mapping rule sequence
                                            $sql = "SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 100";
                                            $result = mysqli_query($conn, $sql);
                                            
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while($row = mysqli_fetch_assoc($result)){
                                                    $order_status = htmlspecialchars($row['order_status']);
                                                    
                                                    // Dynamic Badge Class Filter Assignment Rules Matrix
                                                    $badge_color = "bg-warning text-dark"; // Default Pending
                                                    if ($order_status == 'Completed' || $order_status == 'Delivered') {
                                                        $badge_color = "bg-success text-white";
                                                    } elseif ($order_status == 'Cancelled') {
                                                        $badge_color = "bg-danger text-white";
                                                    } elseif ($order_status == 'Processing') {
                                                        $badge_color = "bg-info text-dark";
                                                    }
                                            ?>
                                            <tr>
                                                <th scope="row" class="text-secondary">#<?php echo $row['id']; ?></th>
                                                <td><span class="badge bg-secondary font-monospace"><?php echo htmlspecialchars($row['order_number']); ?></span></td>
                                                <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                                <td><span class="text-lowercase"><?php echo htmlspecialchars($row['customer_email']); ?></span></td>
                                                <td><?php echo htmlspecialchars($row['customer_phone']); ?></td>
                                                <td>
                                                    <div class="address-text-box text-muted"><?php echo htmlspecialchars($row['customer_address']); ?></div>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['customer_city']); ?></td>
                                                <td><span class="font-monospace"><?php echo htmlspecialchars($row['customer_pincode']); ?></span></td>
                                                <td class="fw-medium text-dark"><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                                <td class="fw-bold text-success fs-6">₹<?php echo htmlspecialchars($row['total_amount']); ?></td>
                                                <td class="text-nowrap text-muted"><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                
                                                <td class="text-center">
                                                    <form action="" method="POST" class="d-flex align-items-center gap-1 justify-content-center">
                                                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                        
                                                        <select name="status_select" class="form-select form-select-sm border-secondary-subtle py-1" style="width: 115px; font-size: 0.8rem; border-radius: 6px; cursor: pointer;">
                                                            <option value="Pending" <?php echo $order_status == 'Pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                                            <option value="Processing" <?php echo $order_status == 'Processing' ? 'selected' : ''; ?>>⚙️ Process</option>
                                                            <option value="Completed" <?php echo $order_status == 'Completed' ? 'selected' : ''; ?>>✅ Complete</option>
                                                            <option value="Cancelled" <?php echo $order_status == 'Cancelled' ? 'selected' : ''; ?>>❌ Cancel</option>
                                                        </select>
                                                        
                                                        <button type="submit" name="update_fulfillment" class="btn btn-sm btn-dark px-2 py-1" style="font-size: 0.75rem; border-radius: 6px; background: #8B4513; border: none;" title="Save Fulfillment Status">
                                                            <i class="fa-solid fa-floppy-disk"></i>
                                                        </button>
                                                    </form>
                                                    <div class="mt-1">
                                                        <span class="badge badge-status-pill <?php echo $badge_color; ?>" style="font-size: 0.7rem; padding: 3px 8px;"><?php echo $order_status; ?></span>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <a href="generate_bill.php?order_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-dark border-0" title="Generate Invoice Bill">
                                                        <i class="fa-solid fa-money-bill-wave fs-4 text-success"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='13' class='text-center py-4 text-muted fw-bold'>No order entries mapped inside database registry.</td></tr>";
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

        <?php include "footer.php"; ?>
    </section>
</body>
</html>