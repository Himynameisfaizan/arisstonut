<?php
session_start();
include "db-conn.php";

$msg = "";
if (isset($_POST['add_coupon'])) {
    $code = mysqli_real_escape_string($conn, strtoupper(trim($_POST['coupon_code'])));
    $type = mysqli_real_escape_string($conn, $_POST['discount_type']);
    $value = floatval($_POST['discount_value']);
    $min_cart = floatval($_POST['min_cart_value']);
    $limit = intval($_POST['usage_limit']);
    $expiry = mysqli_real_escape_string($conn, $_POST['expiry_date']);
    $status = intval($_POST['status']);

    // Check if code already exists
    $check_query = mysqli_query($conn, "SELECT id FROM coupons WHERE coupon_code = '$code'");
    if (mysqli_num_rows($check_query) > 0) {
        $msg = "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle'></i> Coupon Code already exists! Please use a unique code.</div>";
    } else {
        $insert_query = "INSERT INTO coupons (coupon_code, discount_type, discount_value, min_cart_value, usage_limit, expiry_date, status) 
                         VALUES ('$code', '$type', '$value', '$min_cart', '$limit', '$expiry', '$status')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Coupon Added Successfully!'); window.location.href='view-coupons.php';</script>";
        } else {
            $msg = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - Add Coupon</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
</head>
<body class="crm_body_bg">
    <?php include "header.php"; ?>
    <section class="main_content dashboard_part large_header_bg">
        <div class="main_content_iner ">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h3 class="m-0 text-dark fw-bold"><i class="ti-ticket text-primary me-2"></i>Create New Coupon</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <?= $msg ?>
                                <form action="" method="POST">
                                    <div class="row g-3">
                                        <!-- Coupon Code -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Coupon Code <span class="text-danger">*</span></label>
                                            <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="e.g. FESTIVAL50" style="text-transform: uppercase;" required>
                                            <small class="text-muted">Enter a unique code without spaces.</small>
                                        </div>

                                        <!-- Discount Type -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Discount Type <span class="text-danger">*</span></label>
                                            <select name="discount_type" class="form-select" required>
                                                <option value="flat">Flat Amount (₹)</option>
                                                <option value="percentage">Percentage (%)</option>
                                            </select>
                                        </div>

                                        <!-- Discount Value -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Discount Value <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="discount_value" class="form-control" placeholder="e.g. 100 or 15" required>
                                        </div>

                                        <!-- Min Cart Value -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Minimum Cart Value (₹) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="min_cart_value" class="form-control" placeholder="e.g. 999" required>
                                        </div>

                                        <!-- Usage Limit -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Usage Limit (Max Users) <span class="text-danger">*</span></label>
                                            <input type="number" name="usage_limit" class="form-control" value="100" required>
                                            <small class="text-muted">How many times this can be used.</small>
                                        </div>

                                        <!-- Expiry Date -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Expiry Date <span class="text-danger">*</span></label>
                                            <input type="date" name="expiry_date" class="form-control" required>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Status</label>
                                            <select name="status" class="form-select" required>
                                                <option value="1">Active (Live)</option>
                                                <option value="0">Inactive (Hidden)</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-12 mt-4 text-end">
                                            <a href="view-coupons.php" class="btn btn-light border fw-bold me-2">Cancel</a>
                                            <button type="submit" name="add_coupon" class="btn btn-primary fw-bold px-4">Save & Create Coupon</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php"; ?>
        <script>
            // Force uppercase for coupon input dynamically
            document.getElementById('coupon_code').addEventListener('keyup', function() {
                this.value = this.value.toUpperCase().replace(/\s+/g, '');
            });
        </script>
    </section>
</body>
</html>