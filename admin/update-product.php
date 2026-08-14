<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db-conn.php";

if (isset($_POST['update-product'])) {
    
    // 1. Get Product ID & Find Main Auto-Increment Primary Key
    $pro_id = intval($_POST['pro_id']);
    
    $get_main_stmt = $conn->prepare("SELECT id, pro_img FROM products WHERE pro_id = ? LIMIT 1");
    $get_main_stmt->bind_param("i", $pro_id);
    $get_main_stmt->execute();
    $main_result = $get_main_stmt->get_result();
    
    if (!$main_result || $main_result->num_rows === 0) {
        die("Product record not found.");
    }
    
    $product_record = $main_result->fetch_assoc();
    $main_product_id = intval($product_record['id']);
    $existing_main_img = $product_record['pro_img'];
    $get_main_stmt->close();

    // 2. Sanitize Standard Form Data
    $pro_name       = mysqli_real_escape_string($conn, $_POST['pro_name']);
    $brand_name     = mysqli_real_escape_string($conn, $_POST['brand_name'] ?? '');
    $pro_cate       = intval($_POST['pro_cate']);
    $pro_sub_cate   = intval($_POST['pro_sub_cate'] ?? 0);
    $short_desc     = mysqli_real_escape_string($conn, $_POST['short_desc']);
    $description    = mysqli_real_escape_string($conn, $_POST['pro_desc']);
    $new_arrival    = intval($_POST['new_arrival']);
    $trending       = intval($_POST['trending'] ?? 0);
    $stock          = intval($_POST['stock']);
    $status         = intval($_POST['status']);
    $meta_title     = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_desc      = mysqli_real_escape_string($conn, $_POST['meta_desc']);
    $meta_key       = mysqli_real_escape_string($conn, $_POST['meta_key']);
    $slug_url       = strtolower(str_replace(" ", "-", $pro_name));

    // Base fallback price from first variation row
    $first_var_price = isset($_POST['var_price'][0]) ? floatval($_POST['var_price'][0]) : 0.00;

    // 3. Handle Main Product Image
    $target_dir = "assets/img/uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $pro_img = $existing_main_img;
    if (isset($_FILES['pro_img']) && $_FILES['pro_img']['error'] === UPLOAD_ERR_OK && !empty($_FILES['pro_img']['name'])) {
        $filename = $_FILES['pro_img']['name'];
        $tempname = $_FILES['pro_img']['tmp_name'];
        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        
        if (in_array($file_extension, $allowed_extensions)) {
            $uniqueFilename = time() . "_" . rand(1000, 9999) . "." . $file_extension;
            if (move_uploaded_file($tempname, $target_dir . $uniqueFilename)) {
                $pro_img = $uniqueFilename;
                
                // Delete old image if present
                if (!empty($existing_main_img) && file_exists($target_dir . $existing_main_img)) {
                    unlink($target_dir . $existing_main_img);
                }
            }
        }
    }

    // 4. Update Main `products` Table
    $update_main = "UPDATE `products` SET 
        `pro_name` = '$pro_name',
        `brand_name` = '$brand_name',
        `pro_cate` = '$pro_cate',
        `pro_sub_cate` = '$pro_sub_cate',
        `short_desc` = '$short_desc',
        `description` = '$description',
        `new_arrival` = '$new_arrival',
        `trending` = '$trending',
        `selling_price` = '$first_var_price',
        `stock` = '$stock',
        `pro_img` = '$pro_img',
        `status` = '$status',
        `slug_url` = '$slug_url',
        `meta_title` = '$meta_title',
        `meta_desc` = '$meta_desc',
        `meta_key` = '$meta_key'
        WHERE `id` = '$main_product_id'";
        
    mysqli_query($conn, $update_main);

    // 5. Dynamic Variations Processing (Insert/Update/Delete)
    $active_var_ids = [];

    if (isset($_POST['var_weight']) && is_array($_POST['var_weight'])) {
        $total_vars = count($_POST['var_weight']);

        for ($i = 0; $i < $total_vars; $i++) {
            $var_id     = isset($_POST['var_id'][$i]) ? intval($_POST['var_id'][$i]) : 0;
            $weight     = mysqli_real_escape_string($conn, $_POST['var_weight'][$i]);
            $price      = floatval($_POST['var_price'][$i]);
            
            $price4     = (!empty($_POST['var_price_4'][$i]) && floatval($_POST['var_price_4'][$i]) > 0) ? floatval($_POST['var_price_4'][$i]) : "NULL";
            $price5     = (!empty($_POST['var_price_5'][$i]) && floatval($_POST['var_price_5'][$i]) > 0) ? floatval($_POST['var_price_5'][$i]) : "NULL";
            $price6     = (!empty($_POST['var_price_6'][$i]) && floatval($_POST['var_price_6'][$i]) > 0) ? floatval($_POST['var_price_6'][$i]) : "NULL";
            
            $var_stock  = intval($_POST['var_stock'][$i]);
            $var_image  = isset($_POST['old_var_img'][$i]) ? mysqli_real_escape_string($conn, $_POST['old_var_img'][$i]) : '';

            // Handle per-variation new image upload
            if (isset($_FILES['var_img']['name'][$i]) && !empty($_FILES['var_img']['name'][$i])) {
                $v_filename = $_FILES['var_img']['name'][$i];
                $v_tempname = $_FILES['var_img']['tmp_name'][$i];
                $v_ext = strtolower(pathinfo($v_filename, PATHINFO_EXTENSION));
                
                if (in_array($v_ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'))) {
                    $unique_var_name = time() . "_var_" . rand(100, 999) . "." . $v_ext;
                    if (move_uploaded_file($v_tempname, $target_dir . $unique_var_name)) {
                        $var_image = $unique_var_name;
                    }
                }
            }

            if ($var_id === 0) {
                // INSERT new variation
                $var_ins = "INSERT INTO `product_variations` 
                    (`product_id`, `weight_size`, `single_price`, `price_4_plus`, `price_5_plus`, `price_6_plus`, `stock`, `image_path`) 
                    VALUES ('$main_product_id', '$weight', '$price', $price4, $price5, $price6, '$var_stock', '$var_image')";
                mysqli_query($conn, $var_ins);
                $active_var_ids[] = mysqli_insert_id($conn);
            } else {
                // UPDATE existing variation
                $var_upd = "UPDATE `product_variations` SET 
                    `weight_size` = '$weight',
                    `single_price` = '$price',
                    `price_4_plus` = $price4,
                    `price_5_plus` = $price5,
                    `price_6_plus` = $price6,
                    `stock` = '$var_stock',
                    `image_path` = '$var_image'
                    WHERE `id` = '$var_id' AND `product_id` = '$main_product_id'";
                mysqli_query($conn, $var_upd);
                $active_var_ids[] = $var_id;
            }
        }
    }

    // 6. Delete Removed Variations
    if (!empty($active_var_ids)) {
        $keep_ids = implode(',', array_map('intval', $active_var_ids));
        mysqli_query($conn, "DELETE FROM `product_variations` WHERE `product_id` = '$main_product_id' AND `id` NOT IN ($keep_ids)");
    } else {
        mysqli_query($conn, "DELETE FROM `product_variations` WHERE `product_id` = '$main_product_id'");
    }

    echo "<script type='text/javascript'>
            alert('Product and Variations Updated Successfully!');
            window.location.href = 'show-products.php';
          </script>";
    exit;
}
?>