<?php
session_start();
include "db-conn.php";

if(isset($_GET['img_id']) && isset($_GET['pro_id'])) {
    $img_id = intval($_GET['img_id']);
    $pro_id = intval($_GET['pro_id']); // Just for redirection

    // Pehle image ka naam nikalo taaki folder se delete kar sakein
    $check = mysqli_query($conn, "SELECT image_path FROM product_images WHERE id = '$img_id'");
    
    if($check && mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $file_path = "assets/img/uploads/" . $row['image_path'];
        
        // Database se delete
        mysqli_query($conn, "DELETE FROM product_images WHERE id = '$img_id'");
        
        // Folder se delete
        if(file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Wapas edit product page par bhej do
    header("Location: edit-product.php?edit_product_details=" . $pro_id);
    exit();
} else {
    header("Location: show-products.php");
    exit();
}
?>