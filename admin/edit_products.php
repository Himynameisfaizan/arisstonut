<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db-conn.php"; // Ensure this includes your database connection
include "functions.php";

if (!isset($_GET['edit_product_details'])) {
    die("Product ID is missing from the URL.");
}

$product_id = intval($_GET['edit_product_details']);

// Fetch product details using mysqli_query()
$query = "SELECT * FROM products WHERE pro_id = $product_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
    $db_primary_id = $product['id']; // Main auto-increment ID
    
    // Naya Code: Fetch existing variations
    $var_query = "SELECT * FROM product_variations WHERE product_id = '$db_primary_id'";
    $variations_result = mysqli_query($conn, $var_query);
} else {
    die("Product not found.");
}

// Fetch categories
$category_query = "SELECT * FROM `categories` ORDER BY id DESC";
$categories = $conn->query($category_query);

$sql = "SELECT * FROM `categories` ORDER BY id DESC";
$check = mysqli_query($conn, $sql);

// Fetch subcategories for the selected parent category
$parent_cate_id = $product['pro_cate'];
// CORRECT - Use 'parent_id' not 'parent_cate'
$sub_cate_query = "SELECT * FROM `sub_categories` WHERE parent_id = '$parent_cate_id'";
$sub_categories = mysqli_query($conn, $sub_cate_query);
?>


<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin - Edit Product</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">

    <?php include "links.php"; ?>
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
                                        <input type="text" placeholder="Search here...">
                                    </div>
                                    <button type="submit"> <img src="assets/img/icon/icon_search.svg" alt> </button>
                                </form>
                            </div>
                            <span class="f_s_14 f_w_400 ml_25 white_text text_white">Apps</span>
                        </div>
                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="header_notification_warp d-flex align-items-center">
                                <li>
                                    <a class="bell_notification_clicker nav-link-notify" href="#"> 
                                        <img src="assets/img/icon/bell.svg" alt>
                                    </a>
                                    <!-- Notification dropdown - keep as is -->
                                </li>
                                <li>
                                    <a class="CHATBOX_open nav-link-notify" href="#"> 
                                        <img src="assets/img/icon/msg.svg" alt> 
                                    </a>
                                </li>
                            </div>
                            <div class="profile_info">
                                <img src="assets/img/client_img.png" alt="#">
                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <p>Neurologist </p>
                                        <h5>Dr. Robar Smith</h5>
                                    </div>
                                    <div class="profile_info_details">
                                        <a href="#">My Profile </a>
                                        <a href="#">Settings</a>
                                        <a href="#">Log Out </a>
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
                                        <h2 class="m-0">Update Product Details</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <br />
                                <div class="card-body">
                                    <form action="update-product.php" method="POST" enctype="multipart/form-data">
                                        <!-- Hidden Input for Product ID -->
                                        <input type="hidden" name="pro_id" value="<?= $product['pro_id'] ?>" />

                                        <div class="row mb-3">
                                            <!-- Product Name -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_name">Product Name</label>
                                                <input type="text" class="form-control" name="pro_name"
                                                    id="pro_name" value="<?= $product['pro_name'] ?>"
                                                    placeholder="Product Name" required />
                                            </div>

                                            <!-- Brand Name -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="brand_name">Brand Name</label>
                                                <input type="text" class="form-control" name="brand_name"
                                                    id="brand_name" value="<?= $product['brand_name'] ?? '' ?>"
                                                    placeholder="Brand Name" />
                                            </div>

                                            <!-- Parent Category -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_cate">Parent Category Name</label>
                                                <select class="form-control" name="pro_cate" id="pro_cate" required
                                                    onchange="get_subcategory(this.value)">
                                                    <option value="select">--select--</option>
                                                    <?php foreach ($check as $val) { ?>
                                                        <option value="<?= $val['cate_id'] ?>"
                                                            <?= ($product['pro_cate'] == $val['cate_id']) ? 'selected' : '' ?>>
                                                            <?= ucwords($val['categories']) ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <!-- Sub Category - FIXED AND ADDED -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_sub_cate">Sub Category</label>
                                                <select class="form-control" name="pro_sub_cate" id="subcate_id" required>
                                                    <option value="select">Select</option>
                                                    <?php 
                                                    // Show subcategories for the current parent category
                                                    if ($sub_categories && mysqli_num_rows($sub_categories) > 0) {
                                                        while ($sub_cate = mysqli_fetch_assoc($sub_categories)) {
                                                            $selected = ($product['pro_sub_cate'] == $sub_cate['id']) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $sub_cate['id'] ?>" <?= $selected ?>>
                                                            <?= ucwords($sub_cate['categories']) ?>
                                                        </option>
                                                    <?php 
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <!-- Stock -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="stock">Stock</label>
                                                <input type="text" class="form-control" name="stock"
                                                    id="stock" value="<?= $product['stock'] ?>"
                                                    placeholder="Stock" required />
                                            </div>

                                            <!-- Product Image(s) -->
                                         <!-- Product Image -->
<div class="col-md-6 mb-3">
    <label class="form-label" for="pro_img">Product Image</label>
    <input type="file" class="form-control" name="pro_img" id="pro_img" accept="image/*" />
    
    <?php if(!empty($product['pro_img'])): ?>
    <div class="mt-2">
        <small>Current Image:</small>
        <div class="mt-2">
            <img src="assets/img/uploads/<?= trim($product['pro_img']) ?>"
                style="height: 200px; width: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #eee;"
                alt="Product Image"
                onerror="this.src='assets/img/no-image.png'">
        </div>
    </div>
    <?php else: ?>
    <div class="mt-2">
        <small>No image uploaded</small>
    </div>
    <?php endif; ?>
</div>

                                            <!-- New Arrival -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="new_arrival">Exclusive Deal & Offers</label>
                                                <select id="new_arrival" name="new_arrival" class="form-control" required>
                                                    <option value="0" <?= $product['new_arrival'] == 0 ? 'selected' : '' ?>>No</option>
                                                    <option value="1" <?= $product['new_arrival'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                </select>
                                            </div>

                                            <!-- Trending / Special Offers -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="trending">Special Offers</label>
                                                <select id="trending" name="trending" class="form-control" required>
                                                    <option value="0" <?= $product['trending'] == 0 ? 'selected' : '' ?>>No</option>
                                                    <option value="1" <?= $product['trending'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                </select>
                                            </div>

                                            <!-- Short Description -->
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="short_desc">Product Short Description</label>
                                                <textarea class="form-control" name="short_desc" id="short_desc"
                                                    required><?= $product['short_desc'] ?></textarea>
                                            </div>

                                            <!-- Long Description -->
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="pro_desc">Product Long Description</label>
                                                <textarea class="form-control" name="pro_desc" id="pro_desc"
                                                    required><?= $product['description'] ?></textarea>
                                            </div>

                                            <!-- MRP -->
                                           <!-- Custom CSS for clean UI & Contrast -->
<style>
    .variation-card {
        background: #ffffff;
        border: 1px solid #e1e5eb;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .variation-header {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
    }
    .table-variations thead th {
        background-color: #343a40 !important; /* Dark background fixing visibility issue */
        color: #ffffff !important; /* Pure white text */
        font-weight: 500;
        text-align: center;
        border: none;
        white-space: nowrap;
    }
    .table-variations td {
        vertical-align: middle;
    }
</style>

<div class="col-md-12 mb-4 mt-3">
    <div class="variation-card">
        <div class="variation-header">
            <h5 class="m-0 text-dark fw-bold"><i class="ti-layers text-primary"></i> Product Variations (Weight, Price & Images)</h5>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-bordered table-variations" id="variation_table">
                    <thead>
                        <tr>
                            <th>Weight/Size <span class="text-danger">*</span></th>
                            <th>Single Price (₹) <span class="text-danger">*</span></th>
                            <th>4+ Price (₹)</th>
                            <th>5+ Price (₹)</th>
                            <th>6+ Price (₹)</th>
                            <th>Stock <span class="text-danger">*</span></th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="variation_body">
                        <?php
                        // Agar pehle se variations exist karte hain, to loop chalayenge
                        if (isset($variations_result) && mysqli_num_rows($variations_result) > 0) {
                            while ($var = mysqli_fetch_assoc($variations_result)) {
                                ?>
                                <tr>
                                    <!-- Hidden ID taaki backend update samajh sake -->
                                    <input type="hidden" name="var_id[]" value="<?= $var['id'] ?>">
                                    
                                    <td><input type="text" name="var_weight[]" class="form-control" value="<?= htmlspecialchars($var['weight_size']) ?>" required></td>
                                    <td><input type="number" step="0.01" name="var_price[]" class="form-control" value="<?= $var['single_price'] ?>" required></td>
                                    <td><input type="number" step="0.01" name="var_price_4[]" class="form-control" value="<?= $var['price_4_plus'] ?>"></td>
                                    <td><input type="number" step="0.01" name="var_price_5[]" class="form-control" value="<?= $var['price_5_plus'] ?>"></td>
                                    <td><input type="number" step="0.01" name="var_price_6[]" class="form-control" value="<?= $var['price_6_plus'] ?>"></td>
                                    <td><input type="number" name="var_stock[]" class="form-control" value="<?= $var['stock'] ?>" required></td>
                                    <td class="text-center">
                                        <input type="file" name="var_img[]" class="form-control mb-1" accept="image/*">
                                        <!-- Hidden input purani image store karne ke liye -->
                                        <input type="hidden" name="old_var_img[]" value="<?= htmlspecialchars($var['image_path']) ?>">
                                        
                                        <?php if(!empty($var['image_path'])): ?>
                                            <div class="mt-1">
                                                <img src="assets/img/uploads/<?= $var['image_path'] ?>" width="40" height="40" style="object-fit:cover; border-radius:5px; border:1px solid #ddd;">
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row fw-bold"><i class="ti-minus"></i></button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            // Agar purana product hai aur koi variation nahi hai
                            ?>
                            <tr>
                                <input type="hidden" name="var_id[]" value="0"> <!-- 0 means new insert -->
                                <td><input type="text" name="var_weight[]" class="form-control" placeholder="e.g. 100g" required></td>
                                <td><input type="number" step="0.01" name="var_price[]" class="form-control" required></td>
                                <td><input type="number" step="0.01" name="var_price_4[]" class="form-control"></td>
                                <td><input type="number" step="0.01" name="var_price_5[]" class="form-control"></td>
                                <td><input type="number" step="0.01" name="var_price_6[]" class="form-control"></td>
                                <td><input type="number" name="var_stock[]" class="form-control" required></td>
                                <td>
                                    <input type="file" name="var_img[]" class="form-control" accept="image/*">
                                    <input type="hidden" name="old_var_img[]" value="">
                                </td>
                                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row fw-bold"><i class="ti-minus"></i></button></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <div class="mt-3 text-end">
                    <button type="button" class="btn btn-success add-row fw-bold"><i class="ti-plus"></i> Add New Variation</button>
                </div>
            </div>
            <small class="text-muted mt-2 d-block"><i class="ti-info-alt text-primary"></i> Note: Leave 4+, 5+, 6+ price empty if you don't want to give bulk discount for a specific weight.</small>
        </div>
    </div>
</div>

                                        <!-- SEO Section -->
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_title">Meta Title</label>
                                                <input type="text" class="form-control" name="meta_title"
                                                    id="meta_title" value="<?= $product['meta_title'] ?>"
                                                    placeholder="Meta Title" />
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_key">Meta Keyword</label>
                                                <input type="text" class="form-control" name="meta_key"
                                                    id="meta_key" value="<?= $product['meta_key'] ?>"
                                                    placeholder="Meta Keyword" />
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_desc">Meta Description</label>
                                                <input type="text" class="form-control" name="meta_desc"
                                                    id="meta_desc" value="<?= $product['meta_desc'] ?>"
                                                    placeholder="Meta Description" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="status">Status</label>
                                                <select id="status" name="status" class="form-control" required>
                                                    <option value="1" <?= $product['status'] == 1 ? 'selected' : '' ?>>
                                                        Active</option>
                                                    <option value="0" <?= $product['status'] == 0 ? 'selected' : '' ?>>
                                                        Deactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" name="update-product" class="btn btn-primary">
                                            Update Product
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php"; ?>

        <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

        <script>
            CKEDITOR.replace('pro_desc');
            CKEDITOR.replace('short_desc');
        </script>

        <!-- AJAX function for selecting category then automatically show sub category -->
        <script type="text/javascript">
            function get_subcategory(cate_id) {
                if (cate_id === '') {
                    $("#subcate_id").html('<option value="select">Select</option>');
                    return;
                }
                
                $.ajax({
                    url: 'functions.php',
                    method: 'post',
                    data: { cate_id: cate_id },
                    error: function () {
                        alert("Something went wrong while fetching subcategories");
                    },
                    success: function (data) {
                        $("#subcate_id").html(data);
                    }
                });
            }
        </script>

        <script>
    $(document).ready(function() {
        // Add new variation row dynamically
        $(document).on('click', '.add-row', function() {
            var html = `<tr>
                <input type="hidden" name="var_id[]" value="0"> <!-- New entry logic -->
                <td><input type="text" name="var_weight[]" class="form-control" placeholder="e.g. 200g" required></td>
                <td><input type="number" step="0.01" name="var_price[]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="var_price_4[]" class="form-control"></td>
                <td><input type="number" step="0.01" name="var_price_5[]" class="form-control"></td>
                <td><input type="number" step="0.01" name="var_price_6[]" class="form-control"></td>
                <td><input type="number" name="var_stock[]" class="form-control" required></td>
                <td class="text-center">
                    <input type="file" name="var_img[]" class="form-control mb-1" accept="image/*">
                    <input type="hidden" name="old_var_img[]" value="">
                </td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row fw-bold"><i class="ti-minus"></i></button></td>
            </tr>`;
            $('#variation_body').append(html);
        });

        // Remove variation row from UI
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>

    </section>
</body>
</html>