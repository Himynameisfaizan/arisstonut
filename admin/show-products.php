<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "db-conn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Product Management | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .product-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .search-box {
            position: relative;
            max-width: 400px;
        }

        .search-box input {
            padding-left: 40px;
            border-radius: 20px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 3px;
        }

        .table thead th {
            background-color: #2c3e50;
            color: white;
            font-weight: 500;
        }

        .price-highlight {
            font-weight: 600;
            color: #2e7d32;
        }

        .pagination .page-item.active .page-link {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
    </style>
</head>

<body class="crm_body_bg">
    <?php include "header.php"; ?>

    <section class="main_content dashboard_part">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <?php include "top_nav.php"; ?>
                </div>
            </div>
        </div>

        <div class="main_content_iner">
            <div class="container-fluid p-3">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="white_card mb_30">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="mb-3 mb-md-0">
                                        <h2 class="mb-0 fw-bold">Product Management</h2>
                                        <p class="text-muted mb-0 small">Manage your product inventory</p>
                                    </div>
                                    <div class="d-flex">
                                        <form method="GET" class="position-relative me-3">
                                            <input type="text" class="form-control" name="search"
                                                placeholder="Search products..."
                                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                        </form>
                                        <a href="add-products.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add Product
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="white_card_body">
                                <div class="QA_section">
                                    <div class="QA_table mb_30">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered align-middle text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>ID</th>
                                                        <th>Product</th>
                                                        <th>Category</th>
                                                        <th>Image</th>
                                                        <th>Add More Image</th>
                                                        <th>MRP</th>
                                                        <th>Sale Price</th>
                                                        <th>Status</th>

                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sno = 1;
                                                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                                                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $perPage = 10;
                                                    $offset = ($page - 1) * $perPage;

                                                    $sql = "SELECT * FROM products";
                                                    $countSql = "SELECT COUNT(*) as total FROM products";

                                                    if (!empty($search)) {
                                                        $searchTerm = mysqli_real_escape_string($conn, $search);
                                                        $sql .= " WHERE pro_name LIKE '%$searchTerm%' OR pro_id LIKE '%$searchTerm%'";
                                                        $countSql .= " WHERE pro_name LIKE '%$searchTerm%' OR pro_id LIKE '%$searchTerm%'";
                                                    }

                                                    $sql .= " ORDER BY pro_id DESC LIMIT $offset, $perPage";

                                                    $result = mysqli_query($conn, $sql);
                                                    $countResult = mysqli_query($conn, $countSql);
                                                    $totalRows = mysqli_fetch_assoc($countResult)['total'];
                                                    $totalPages = ceil($totalRows / $perPage);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $p_db_id = intval($row['id']);
        $status_text = $row['status'] == "1" ? "Active" : "Inactive";
        $status_color = $row['status'] == "1" ? "text-success" : "text-danger";

        $image = $row['pro_img'];
        $images = explode(",", $image);
        $first_image = !empty($images[0]) ? $images[0] : 'no-image.png';

        // 1. Fetch Category Name dynamically instead of raw cate_id
        $cate_name_display = "N/A";
        $c_id = $row['pro_cate'];
        $cat_lookup = mysqli_query($conn, "SELECT categories FROM categories WHERE cate_id = '$c_id' LIMIT 1");
        if ($cat_lookup && mysqli_num_rows($cat_lookup) > 0) {
            $cate_name_display = mysqli_fetch_assoc($cat_lookup)['categories'];
        }

        // 2. Fetch Variation Pricing Details
        $var_check = mysqli_query($conn, "SELECT MIN(single_price) as min_p, MAX(single_price) as max_p, COUNT(id) as total_var FROM product_variations WHERE product_id = '$p_db_id'");
        $var_data = mysqli_fetch_assoc($var_check);

        $price_display = "₹" . number_format((float)$row['selling_price'], 2);
        if ($var_data && $var_data['total_var'] > 0) {
            if ($var_data['min_p'] == $var_data['max_p']) {
                $price_display = "₹" . number_format((float)$var_data['min_p'], 2);
            } else {
                $price_display = "₹" . number_format((float)$var_data['min_p'], 2) . " - ₹" . number_format((float)$var_data['max_p'], 2);
            }
            $price_display .= " <br><span class='badge bg-light text-dark border'>" . $var_data['total_var'] . " Packs</span>";
        }
        ?>
        <tr>
            <td><?= $sno++ ?></td>
            <td class="fw-bold"><?= htmlspecialchars($row['pro_id']) ?></td>
            <td class="fw-bold text-start"><?= htmlspecialchars($row['pro_name']) ?></td>
            <td><span class="badge bg-secondary"><?= htmlspecialchars($cate_name_display) ?></span></td>
            <td>
                <img src="assets/img/uploads/<?= htmlspecialchars($first_image) ?>"
                    alt="<?= htmlspecialchars($row['pro_name']) ?>"
                    style="width: 60px; height: 60px; object-fit: contain;" class="img-thumbnail"
                    onerror="this.src='assets/img/no-image.png'">
            </td>
            <td>
                <a href="multiple_img.php?id=<?= $row['pro_id'] ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-images me-1"></i> Images
                </a>
            </td>
            <td>
                <del class="text-muted">₹<?= number_format((float)$row['mrp'], 2) ?></del>
            </td>
            <td class="price-highlight">
                <?= $price_display ?>
            </td>
            <td class="<?= $status_color ?> fw-bold"><?= $status_text ?></td>
            <td>
                <div class="d-flex justify-content-center">
                    <a href="edit_products.php?edit_product_details=<?= $row['pro_id'] ?>"
                        class="btn btn-outline-info btn-sm me-2" title="Edit Product">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="product_delete.php?delete=<?= $row['pro_id'] ?>"
                        class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this product?')" title="Delete Product">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            </td>
        </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="10" class="text-center text-muted py-4">No products found</td></tr>';
}
?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <?php if ($totalPages > 1): ?>
                                            <div class="d-flex justify-content-between align-items-center mt-4">
                                                <div class="text-muted small">
                                                    Showing <?= $offset + 1 ?> to <?= min($offset + $perPage, $totalRows) ?>
                                                    of <?= $totalRows ?> entries
                                                </div>
                                                <nav>
                                                    <ul class="pagination mb-0">
                                                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                                            <a class="page-link"
                                                                href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                                                        </li>
                                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                                <a class="page-link"
                                                                    href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                                            </li>
                                                        <?php endfor; ?>
                                                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                                            <a class="page-link"
                                                                href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </section>
    <?php include "footer.php"; ?>


    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Focus search input when search icon is clicked
        document.querySelector('.search-box i').addEventListener('click', function() {
            this.parentElement.querySelector('input').focus();
        });
    </script>
</body>

</html>