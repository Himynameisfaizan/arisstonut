<?php
include "db-conn.php";

$sql = "SELECT * FROM `categories` ORDER BY id DESC";
$check = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin - Add Product</title>
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
                            <!-- Notifications / Profile (Kept Intact) -->
                            <div class="header_notification_warp d-flex align-items-center">
                                <li>
                                    <a class="bell_notification_clicker nav-link-notify" href="#"> <img src="assets/img/icon/bell.svg" alt></a>
                                </li>
                                <li>
                                    <a class="CHATBOX_open nav-link-notify" href="#"> <img src="assets/img/icon/msg.svg" alt></a>
                                </li>
                            </div>
                            <div class="profile_info">
                                <img src="assets/img/client_img.png" alt="#">
                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <p>Neurologist</p>
                                        <h5>Dr. Robar Smith</h5>
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
                        <div class="main_content_iner">
                            <div class="container-fluid p-0 sm_padding_15px">
                                <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <div class="white_card card_height_100 mb_30">
                                            <div class="white_card_header">
                                                <div class="box_header m-0">
                                                    <div class="main-title">
                                                        <h3 class="m-0">Fill the Product details</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="white_card_body">
                                                <div class="card-body">
                                                    <form id="myform" action="functions.php" method="post" enctype="multipart/form-data">

                                                        <div class="row mb-3">
                                                            <!-- Product Name -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="pro_name">Product Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="pro_name" id="pro_name" placeholder="Product name" required />
                                                            </div>

                                                            <!-- NEW: Slug URL Auto Generator -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="slug_url">Product Slug URL <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control text-lowercase" name="slug_url" id="slug_url" placeholder="product-slug-url" required />
                                                                <small class="text-muted"><i class="ti-info-alt"></i> Auto-generates from Product Name. Automatically handles '+' symbols.</small>
                                                            </div>

                                                            <!-- Brand Name -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="brand_name">Brand Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Brand name" required />
                                                            </div>

                                                            <!-- Category -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="pro_cate">Parent Category Name <span class="text-danger">*</span></label>
                                                                <select class="form-control" name="pro_cate" id="category" required onchange="get_subcategory(this.value)">
                                                                    <option value="">--select--</option>
                                                                    <?php foreach ($check as $val) { ?>
                                                                        <option value="<?= $val['cate_id'] ?>"><?= ucwords($val['categories']) ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <!-- Sub Category -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="subcate_id">Sub Category</label>
                                                                <select class="form-control" name="pro_sub_cate" id="subcate_id">
                                                                    <option value="select">Select</option>
                                                                </select>
                                                            </div>

                                                            <!-- Stock -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="stock">Stock <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="stock" id="stock" placeholder="Stock" required />
                                                            </div>

                                                            <!-- Main Product Image -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="pro_img">Main Product Image <span class="text-danger">*</span></label>
                                                                <input type="file" class="form-control" name="pro_img" id="pro_img" accept="image/*" required />
                                                            </div>

                                                            <!-- 🔥 NEW: MULTIPLE GALLERY IMAGES FIELD 🔥 -->
                                                            <div class="col-md-12 mb-3 mt-3 p-3" style="background: #f8f9fa; border: 1px dashed #ccc; border-radius: 8px;">
                                                                <label class="form-label fw-bold" for="gallery_images">Product Gallery Images (Optional - Multiple Images)</label>
                                                                <input type="file" class="form-control" name="gallery_images[]" id="gallery_images" accept="image/*" multiple />
                                                                <small class="text-muted"><i class="ti-info-alt"></i> You can select multiple images at once by holding CTRL.</small>
                                                            </div>

                                                            <!-- Exclusive Deal -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="new_arrival">Exclusive Deal & Offers</label>
                                                                <select id="new_arrival" name="new_arrival" class="form-control" required>
                                                                    <option value="0" selected>No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>

                                                            <!-- Trending -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="trending">Special Offers</label>
                                                                <select id="trending" name="trending" class="form-control" required>
                                                                    <option value="0" selected>No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>

                                                            <!-- NEW: Product Gallery Images -->
                                                            <div class="col-md-12 mb-3 mt-3 p-3" style="background: #f8f9fa; border: 1px dashed #ccc; border-radius: 8px;">
                                                                <label class="form-label fw-bold" for="gallery_images">Product Gallery Images (Optional - Multiple Images)</label>
                                                                <input type="file" class="form-control" name="gallery_images[]" id="gallery_images" accept="image/*" multiple />
                                                                <small class="text-muted"><i class="ti-info-alt"></i> You can select multiple images at once by holding CTRL. These will appear in the slider.</small>
                                                            </div>

                                                            <!-- Descriptions -->
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label" for="short_desc">Short Description <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="short_desc" required></textarea>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label" for="pro_desc">Product Description <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="pro_desc" required></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Variations Section -->
                                                        <style>
                                                            .variation-card {
                                                                background: #ffffff;
                                                                border: 1px solid #e1e5eb;
                                                                border-radius: 8px;
                                                                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                                                            }

                                                            .variation-header {
                                                                background: #f8f9fa;
                                                                border-bottom: 2px solid #dee2e6;
                                                                padding: 15px 20px;
                                                                border-radius: 8px 8px 0 0;
                                                            }

                                                            .table-variations thead th {
                                                                background-color: #343a40 !important;
                                                                color: #ffffff !important;
                                                                font-weight: 500;
                                                                text-align: center;
                                                                border: none;
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
                                                                                <tr>
                                                                                    <input type="hidden" name="var_id[]" value="0">
                                                                                    <td><input type="text" name="var_weight[]" class="form-control" placeholder="e.g. 100g" required></td>
                                                                                    <td><input type="number" step="0.01" name="var_price[]" class="form-control" required></td>
                                                                                    <td><input type="number" step="0.01" name="var_price_4[]" class="form-control"></td>
                                                                                    <td><input type="number" step="0.01" name="var_price_5[]" class="form-control"></td>
                                                                                    <td><input type="number" step="0.01" name="var_price_6[]" class="form-control"></td>
                                                                                    <td><input type="number" name="var_stock[]" class="form-control" required></td>
                                                                                    <td><input type="file" name="var_img[]" class="form-control" accept="image/*"></td>
                                                                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row fw-bold"><i class="ti-minus"></i></button></td>
                                                                                </tr>
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
                                                                <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="meta_key">Meta Keyword</label>
                                                                <input type="text" class="form-control" name="meta_key" placeholder="Meta Keyword" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="meta_desc">Meta Description</label>
                                                                <input type="text" class="form-control" name="meta_desc" placeholder="Meta Description" />
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label" for="status">Status</label>
                                                                <select id="status" name="status" class="form-control" required>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Deactive</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary" name="add-product">
                                                            Add Product
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

            const form = document.getElementById('myform');
            form.addEventListener('submit', function(event) {
                const select = document.getElementById('category');
                if (!select.value) {
                    alert('Please select a valid category.');
                    event.preventDefault();
                }
            });

            // Ajax function for sub category
            function get_subcategory(cate_id) {
                $.ajax({
                    url: 'functions.php',
                    method: 'post',
                    data: {
                        cate_id: cate_id
                    },
                    error: function() {
                        alert("something went wrong");
                    },
                    success: function(data) {
                        $("#subcate_id").html(data);
                    }
                })
            }
        </script>

        <script>
            $(document).ready(function() {
                // Add new variation row dynamically
                $(document).on('click', '.add-row', function() {
                    var html = `<tr>
                    <input type="hidden" name="var_id[]" value="0">
                    <td><input type="text" name="var_weight[]" class="form-control" placeholder="e.g. 200g" required></td>
                    <td><input type="number" step="0.01" name="var_price[]" class="form-control" required></td>
                    <td><input type="number" step="0.01" name="var_price_4[]" class="form-control"></td>
                    <td><input type="number" step="0.01" name="var_price_5[]" class="form-control"></td>
                    <td><input type="number" step="0.01" name="var_price_6[]" class="form-control"></td>
                    <td><input type="number" name="var_stock[]" class="form-control" required></td>
                    <td><input type="file" name="var_img[]" class="form-control" accept="image/*"></td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row fw-bold"><i class="ti-minus"></i></button></td>
                </tr>`;
                    $('#variation_body').append(html);
                });

                // Remove variation row
                $(document).on('click', '.remove-row', function() {
                    $(this).closest('tr').remove();
                });
            });
        </script>

        <!-- 🔥 SMART SLUG GENERATOR JS 🔥 -->
        <script>
            document.getElementById("pro_name").addEventListener("keyup", function() {
                let name = this.value;
                let slug = name.toLowerCase()
                    .replace(/\+/g, '-plus-') // Handle '+' sign
                    .replace(/&/g, '-and-') // Handle '&' sign
                    .replace(/[^a-z0-9]+/g, '-') // Remove special characters
                    .replace(/(^-|-$)+/g, ''); // Clean trailing hyphens

                document.getElementById("slug_url").value = slug;
            });
        </script>

    </section>
</body>

</html>