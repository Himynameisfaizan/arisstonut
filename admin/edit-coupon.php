<?php
session_start();
include "db-conn.php";

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='view-coupons.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$fetch_query = mysqli_query($conn, "SELECT * FROM coupons WHERE id = '$id'");
$coupon = mysqli_fetch_assoc($fetch_query);

$msg = "";
if (isset($_POST['update_coupon'])) {
    $code = mysqli_real_escape_string($conn, strtoupper(trim($_POST['coupon_code'])));
    $type = mysqli_real_escape_string($conn, $_POST['discount_type']);
    $value = floatval($_POST['discount_value']);
    $min_cart = floatval($_POST['min_cart_value']);
    $limit = intval($_POST['usage_limit']);
    $expiry = mysqli_real_escape_string($conn, $_POST['expiry_date']);
    $status = intval($_POST['status']);

    // Check if code exists for OTHER ids
    $check_query = mysqli_query($conn, "SELECT id FROM coupons WHERE coupon_code = '$code' AND id != '$id'");
    if (mysqli_num_rows($check_query) > 0) {
        $msg = "<div class='alert alert-danger'>Coupon Code already exists! Please use a unique code.</div>";
    } else {
        $update_query = "UPDATE coupons SET 
            coupon_code = '$code', discount_type = '$type', discount_value = '$value', 
            min_cart_value = '$min_cart', usage_limit = '$limit', expiry_date = '$expiry', status = '$status' 
            WHERE id = '$id'";
            
        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Coupon Updated Successfully!'); window.location.href='view-coupons.php';</script>";
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
    <title>Admin - Edit Coupon</title>
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
                                        <h3 class="m-0 text-dark fw-bold"><i class="ti-pencil-alt text-primary me-2"></i>Update Coupon</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <?= $msg ?>
                                <form action="" method="POST">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Coupon Code <span class="text-danger">*</span></label>
                                            <input type="text" name="coupon_code" id="coupon_code" class="form-control" value="<?= $coupon['coupon_code']; ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Discount Type <span class="text-danger">*</span></label>
                                            <select name="discount_type" class="form-select" required>
                                                <option value="flat" <?= ($coupon['discount_type'] == 'flat') ? 'selected' : ''; ?>>Flat Amount (₹)</option>
                                                <option value="percentage" <?= ($coupon['discount_type'] == 'percentage') ? 'selected' : ''; ?>>Percentage (%)</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Discount Value <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="discount_value" class="form-control" value="<?= $coupon['discount_value']; ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Minimum Cart Value (₹) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="min_cart_value" class="form-control" value="<?= $coupon['min_cart_value']; ?>" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Usage Limit <span class="text-danger">*</span></label>
                                            <input type="number" name="usage_limit" class="form-control" value="<?= $coupon['usage_limit']; ?>" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Expiry Date <span class="text-danger">*</span></label>
                                            <input type="date" name="expiry_date" class="form-control" value="<?= $coupon['expiry_date']; ?>" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Status</label>
                                            <select name="status" class="form-select" required>
                                                <option value="1" <?= ($coupon['status'] == 1) ? 'selected' : ''; ?>>Active (Live)</option>
                                                <option value="0" <?= ($coupon['status'] == 0) ? 'selected' : ''; ?>>Inactive (Hidden)</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-12 mt-4 text-end">
                                            <a href="view-coupons.php" class="btn btn-light border fw-bold me-2">Cancel</a>
                                            <button type="submit" name="update_coupon" class="btn btn-primary fw-bold px-4">Update Details</button>
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
            document.getElementById('coupon_code').addEventListener('keyup', function() {
                this.value = this.value.toUpperCase().replace(/\s+/g, '');
            });
        </script>
    </section>
</body>
</html>