<?php
session_start();
include "db-conn.php";

// Delete Logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM coupons WHERE id = '$id'");
    echo "<script>alert('Coupon deleted successfully!'); window.location.href='view-coupons.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - View Coupons</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .table thead th { background: #2D1B18 !important; color: #fff !important; }
        .coupon-code { font-family: monospace; font-size: 1.1rem; color: #9C5521; font-weight: 700; letter-spacing: 1px; background: #FFF0E5; padding: 5px 10px; border-radius: 5px; border: 1px dashed #9C5521; }
    </style>
</head>
<body class="crm_body_bg">
    <?php include "header.php"; ?>
    <section class="main_content dashboard_part large_header_bg">
        <div class="main_content_iner ">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header d-flex justify-content-between align-items-center">
                                <div class="main-title">
                                    <h3 class="m-0 text-dark fw-bold"><i class="ti-ticket text-primary me-2"></i>Coupon & Discounts Manager</h3>
                                </div>
                                <a href="add-coupon.php" class="btn btn-primary fw-bold"><i class="ti-plus"></i> Add New Coupon</a>
                            </div>
                            <div class="white_card_body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Coupon Code</th>
                                                <th>Discount</th>
                                                <th>Min. Cart Value</th>
                                                <th>Usage (Used/Total)</th>
                                                <th>Expiry Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $today = date('Y-m-d');
                                            $sno = 1;
                                            $query = mysqli_query($conn, "SELECT * FROM coupons ORDER BY id DESC");
                                            if(mysqli_num_rows($query) > 0) {
                                                while($row = mysqli_fetch_assoc($query)) {
                                                    // Dynamic Status
                                                    if($row['expiry_date'] < $today) {
                                                        $status_badge = '<span class="badge bg-danger">Expired</span>';
                                                    } elseif($row['status'] == 0) {
                                                        $status_badge = '<span class="badge bg-secondary">Inactive</span>';
                                                    } elseif($row['used_count'] >= $row['usage_limit']) {
                                                        $status_badge = '<span class="badge bg-warning text-dark">Limit Reached</span>';
                                                    } else {
                                                        $status_badge = '<span class="badge bg-success">Active Live</span>';
                                                    }

                                                    $discount_text = ($row['discount_type'] == 'flat') ? '₹'.$row['discount_value'] : $row['discount_value'].'%';
                                            ?>
                                            <tr>
                                                <td><?= $sno++; ?></td>
                                                <td><span class="coupon-code"><?= $row['coupon_code']; ?></span></td>
                                                <td class="fw-bold text-success"><?= $discount_text; ?></td>
                                                <td>₹<?= $row['min_cart_value']; ?></td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <?php $percent = ($row['used_count'] / $row['usage_limit']) * 100; ?>
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $row['used_count'] ?>" aria-valuemin="0" aria-valuemax="<?= $row['usage_limit'] ?>">
                                                            <span class="text-dark fw-bold"><?= $row['used_count']; ?> / <?= $row['usage_limit']; ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold"><?= date('d M Y', strtotime($row['expiry_date'])); ?></td>
                                                <td><?= $status_badge; ?></td>
                                                <td>
                                                    <a href="edit-coupon.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="ti-pencil"></i></a>
                                                    <a href="view-coupons.php?delete=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this coupon?')" class="btn btn-sm btn-danger"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php } } else { ?>
                                                <tr><td colspan="8" class="text-muted py-4">No coupons created yet.</td></tr>
                                            <?php } ?>
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