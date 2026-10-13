<?php
session_start();
include "db-conn.php";

// Update Logic
$msg = "";
$msg_class = "";

if (isset($_POST['update_seo'])) {
    $id = intval($_POST['seo_id']);

    // Sanitize inputs
    $meta_title = mysqli_real_escape_string($conn, trim($_POST['meta_title']));
    $meta_key = mysqli_real_escape_string($conn, trim($_POST['meta_key']));
    $meta_desc = mysqli_real_escape_string($conn, trim($_POST['meta_desc']));
    $schema_markup = mysqli_real_escape_string($conn, trim($_POST['schema_markup']));
    $og_title = mysqli_real_escape_string($conn, trim($_POST['og_title']));
    $og_desc = mysqli_real_escape_string($conn, trim($_POST['og_desc']));

    // OG Image Upload Logic
    $og_image_update = "";
    if (isset($_FILES['og_image']['name']) && !empty($_FILES['og_image']['name'])) {
        $target_dir = "uploads/seo/";
        // Create directory if not exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($_FILES["og_image"]["name"], PATHINFO_EXTENSION));
        $new_img_name = "og_" . time() . "." . $imageFileType;
        $target_file = $target_dir . $new_img_name;

        if (move_uploaded_file($_FILES["og_image"]["tmp_name"], $target_file)) {
            $og_image_update = ", og_image='$new_img_name'";
        }
    }

    // Dynamic Update Query based on whether a new image was uploaded
    $update_query = "UPDATE page_seo SET 
                    meta_title='$meta_title', 
                    meta_key='$meta_key', 
                    meta_desc='$meta_desc', 
                    schema_markup='$schema_markup', 
                    og_title='$og_title', 
                    og_desc='$og_desc' 
                    $og_image_update 
                    WHERE id='$id'";

    if (mysqli_query($conn, $update_query)) {
        $msg = "SEO, Schema & OG details updated successfully!";
        $msg_class = "alert-success";
    } else {
        $msg = "Failed to update details. Error: " . mysqli_error($conn);
        $msg_class = "alert-danger";
    }
}

// Fetch all pages data from new table
$seo_data = mysqli_query($conn, "SELECT * FROM page_seo ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Global SEO | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
</head>

<body class="crm_body_bg">
    <?php include "header.php"; ?>

    <section class="main_content dashboard_part">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0"><?php include "top_nav.php"; ?></div>
            </div>
        </div>

        <div class="main_content_iner">
            <div class="container-fluid p-3">

                <?php if (!empty($msg)): ?>
                    <div class="alert <?= $msg_class ?> alert-dismissible fade show" role="alert">
                        <?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="white_card card_height_100 mb_30 shadow-sm">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h3 class="m-0">Global Pages SEO & Schema Management</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Page Name</th>
                                                <th>Page URL</th>
                                                <th>Meta Title</th>
                                                <th>Schema Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            while ($row = mysqli_fetch_assoc($seo_data)):
                                                $has_schema = !empty(trim($row['schema_markup'])) ? '<span class="badge bg-success">Added</span>' : '<span class="badge bg-danger">Empty</span>';
                                            ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><strong><?= htmlspecialchars($row['page_name']); ?></strong></td>
                                                    <td><span class="badge bg-primary"><?= htmlspecialchars($row['page_url']); ?></span></td>
                                                    <td><?= htmlspecialchars($row['meta_title']); ?></td>
                                                    <td><?= $has_schema; ?></td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-sm btn-primary edit-seo-btn"
                                                            data-id="<?= $row['id'] ?? ''; ?>"
                                                            data-name="<?= htmlspecialchars($row['page_name'] ?? ''); ?>"
                                                            data-title="<?= htmlspecialchars($row['meta_title'] ?? ''); ?>"
                                                            data-key="<?= htmlspecialchars($row['meta_key'] ?? ''); ?>"
                                                            data-desc="<?= htmlspecialchars($row['meta_desc'] ?? ''); ?>"
                                                            data-schema="<?= htmlspecialchars($row['schema_markup'] ?? ''); ?>"
                                                            data-ogtitle="<?= htmlspecialchars($row['og_title'] ?? ''); ?>"
                                                            data-ogdesc="<?= htmlspecialchars($row['og_desc'] ?? ''); ?>"
                                                            data-ogimg="<?= htmlspecialchars($row['og_image'] ?? ''); ?>">
                                                            <i class="fas fa-edit"></i> Edit SEO
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- EDIT SEO MODAL -->
    <div class="modal fade" id="editSeoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title text-white">Update SEO Settings: <span id="display_page_name" class="text-warning"></span></h5>
                    <button type="button" class="btn-close btn-close-white close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Added enctype for image upload -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="seo_id" id="edit_seo_id">

                        <div class="row">
                            <!-- Left Column: Standard Meta Tags -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-primary mb-3"><i class="fas fa-tags"></i> Standard Meta Tags</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title" id="edit_meta_title" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_key" id="edit_meta_key">
                                    <small class="text-muted">Separate keywords with commas</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Meta Description</label>
                                    <textarea class="form-control" name="meta_desc" id="edit_meta_desc" rows="3"></textarea>
                                </div>
                            </div>

                            <!-- Right Column: Open Graph Tags -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-share-alt"></i> Open Graph (Social Sharing)</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">OG Title</label>
                                    <input type="text" class="form-control" name="og_title" id="edit_og_title">
                                    <small class="text-muted">Leave empty to use Meta Title</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">OG Description</label>
                                    <textarea class="form-control" name="og_desc" id="edit_og_desc" rows="2"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">OG Image (WhatsApp/FB Thumbnail)</label>
                                    <input type="file" class="form-control" name="og_image" accept="image/*">
                                    <div class="mt-2" id="current_og_image_wrapper" style="display:none;">
                                        <p class="mb-1 text-muted"><small>Current Image:</small></p>
                                        <img id="current_og_image" src="" alt="OG Image" style="max-height: 80px; border-radius: 5px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Full Width: Schema Markup -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-code"></i> Schema Markup (JSON-LD)</h6>
                                <div class="mb-3">
                                    <textarea class="form-control" name="schema_markup" id="edit_schema_markup" rows="6" placeholder="Paste full <script type='application/ld+json'>...</script> here..."></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_seo" class="btn btn-success">Save All Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var editButtons = document.querySelectorAll('.edit-seo-btn');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Fetch data from button attributes
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');
                    var title = this.getAttribute('data-title');
                    var key = this.getAttribute('data-key');
                    var desc = this.getAttribute('data-desc');
                    var schema = this.getAttribute('data-schema');
                    var ogTitle = this.getAttribute('data-ogtitle');
                    var ogDesc = this.getAttribute('data-ogdesc');
                    var ogImg = this.getAttribute('data-ogimg');

                    // Populate modal fields
                    document.getElementById('edit_seo_id').value = id;
                    document.getElementById('display_page_name').innerText = name;
                    document.getElementById('edit_meta_title').value = title;
                    document.getElementById('edit_meta_key').value = key;
                    document.getElementById('edit_meta_desc').value = desc;
                    document.getElementById('edit_schema_markup').value = schema;
                    document.getElementById('edit_og_title').value = ogTitle;
                    document.getElementById('edit_og_desc').value = ogDesc;

                    // Handle Image Preview
                    var imgWrapper = document.getElementById('current_og_image_wrapper');
                    var imgTag = document.getElementById('current_og_image');

                    if (ogImg && ogImg !== "") {
                        imgTag.src = 'uploads/seo/' + ogImg;
                        imgWrapper.style.display = 'block';
                    } else {
                        imgWrapper.style.display = 'none';
                    }

                    // Fallback Modal Toggle Logic (Works on all Bootstrap versions)
                    var myModalEl = document.getElementById('editSeoModal');

                    // Try Bootstrap 5 method first
                    if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined') {
                        var modal = new bootstrap.Modal(myModalEl);
                        modal.show();
                    }
                    // Fallback to Bootstrap 4 / jQuery method
                    else if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
                        $('#editSeoModal').modal('show');
                    }
                    // Ultimate Fallback (Manual CSS display)
                    else {
                        myModalEl.classList.add('show');
                        myModalEl.style.display = 'block';
                        myModalEl.setAttribute('aria-modal', 'true');

                        // Add backdrop manually if needed
                        var backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        backdrop.id = 'manual-backdrop';
                        document.body.appendChild(backdrop);
                        document.body.classList.add('modal-open');
                    }
                });
            });

            // Handle close buttons for the ultimate fallback
            var closeButtons = document.querySelectorAll('[data-dismiss="modal"], [data-bs-dismiss="modal"]');
            closeButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var myModalEl = document.getElementById('editSeoModal');
                    myModalEl.classList.remove('show');
                    myModalEl.style.display = 'none';
                    myModalEl.removeAttribute('aria-modal');
                    document.body.classList.remove('modal-open');

                    var backdrop = document.getElementById('manual-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                });
            });
        });
    </script>
</body>

</html>