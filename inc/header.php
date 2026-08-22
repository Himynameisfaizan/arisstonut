<?php

include('config/connect.php'); // Global variable dependencies and cookie session path check

// Standard persistent session evaluation rules setup on page refresh
$total_cart_items = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['quantity'])) {
            $total_cart_items += intval($item['quantity']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AristoNut - Premium Quality Makhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Google Fonts & Bootstrap Icons waise hi rahenge -->
    <link rel="icon" type="image/webp" href="admin/uploads/logo/logo_header_6a2a43785437e.webp">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- YAHAN CHANGES KARNE HAIN: Har CSS file path mein $site add karo -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href="assets/css/category.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="assets/css/privacy.css">
    <link rel="stylesheet" href="assets/css/product.css">
    <link rel="stylesheet" href="assets/css/wishlist.css">
    <link rel="stylesheet" href="assets/css/blog.css">
=======
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Google Fonts & Bootstrap Icons waise hi rahenge -->
    <link rel="icon" type="image/webp" href="<?php echo $site; ?>admin/uploads/logo/logo_header_6a2a43785437e.webp">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- YAHAN CHANGES KARNE HAIN: Har CSS file path mein $site add karo -->
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/cart.css">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/category.css">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/contact.css">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/privacy.css">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/product.css">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/wishlist.css">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/blog.css">
>>>>>>> 9f70d3fd0e71bcc96fe8fa18851eb6a1b9dc0084
</head>

<body>

    <div class="announcement-bar">
        🎉 Free shipping on orders over ₹500 <span class="badge">10% OFF First Order</span>
    </div>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container position-relative">

            <?php
            // Default Fallback Logo Path Setup
            $logo_img = $site . "assets/images/logo.webp";

            // Exact schema mapped table registry match query
            $logo_table_query = "SELECT `logo_path` FROM `logos` WHERE `location` = 'header' AND `is_active` = 1 LIMIT 1";
            $logo_table_res = $conn->query($logo_table_query);

            if ($logo_table_res && $logo_table_res->num_rows > 0) {
                $logo_row = $logo_table_res->fetch_assoc();
                if (!empty($logo_row['logo_path'])) {
                    $logo_img = $site . "admin/uploads/" . htmlspecialchars($logo_row['logo_path']);
                }
            }
            ?>
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $site; ?>index.php">
                <img src="<?php echo $logo_img; ?>" alt="AristoNut Logo" class="brand-logo-img">
                <div class="d-none d-sm-block">
                    <div class="brand-text">AristoNut</div>
                    <div class="brand-subtitle">Premium Quality</div>
                </div>
            </a>

            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo $site; ?>wishlist.php" class="nav-icon-link d-lg-none text-decoration-none">
                    <i class="bi bi-heart" style="font-size: 1.5rem;"></i>
                </a>

<<<<<<< HEAD
                <a href="<?php echo $site; ?>cart.php"
                    class="nav-icon-link d-lg-none position-relative text-decoration-none">
=======
                <a href="<?php echo $site; ?>cart.php" class="nav-icon-link d-lg-none position-relative text-decoration-none">
>>>>>>> 9f70d3fd0e71bcc96fe8fa18851eb6a1b9dc0084
                    <i class="bi bi-bag" style="font-size: 1.5rem;"></i>
                    <span class="cart-count" id="header-mobile-cart-badge"><?php echo $total_cart_items; ?></span>
                </a>

                <button class="navbar-toggler border-0" type="button" id="custom-mobile-toggler-btn">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="customNavbarCollapseMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo $site; ?>index.php">HOME</a></li>

                    <li class="nav-item dropdown">
<<<<<<< HEAD
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            style="cursor: pointer;">
                            PRODUCTS
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg" aria-labelledby="navbarDropdown"
                            style="border-radius: 12px; min-width: 240px; padding: 10px 0;">
                            <li>
                                <a class="dropdown-item fw-bold border-bottom mb-2 py-2"
                                    href="<?php echo $site; ?>product.php" style="color: #8B4513;">
=======
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" style="cursor: pointer;">
                            PRODUCTS
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg" aria-labelledby="navbarDropdown" style="border-radius: 12px; min-width: 240px; padding: 10px 0;">
                            <li>
                                <a class="dropdown-item fw-bold border-bottom mb-2 py-2" href="<?php echo $site; ?>product.php" style="color: #8B4513;">
>>>>>>> 9f70d3fd0e71bcc96fe8fa18851eb6a1b9dc0084
                                    <i class="bi bi-grid-fill me-2"></i>All Products
                                </a>
                            </li>
                            <?php
                            $cate_nav_query = "SELECT `categories`, `slug_url`, `image` FROM `categories` WHERE `status` = 1 ORDER BY `id` ASC";
                            $cate_nav_res = $conn->query($cate_nav_query);

                            if ($cate_nav_res && $cate_nav_res->num_rows > 0) {
                                while ($cat_row = $cate_nav_res->fetch_assoc()) {
                                    $cat_name = htmlspecialchars($cat_row['categories']);
                                    $cat_slug = htmlspecialchars($cat_row['slug_url']);
                                    $cat_img = !empty($cat_row['image']) ? $site . 'admin/uploads/category/' . htmlspecialchars($cat_row['image']) : $site . 'assets/images/hero.webp';

                                    echo '<li>
                        <a class="dropdown-item d-flex align-items-center gap-3 py-2" href="' . $site . 'category/' . $cat_slug . '">
                            <img src="' . $cat_img . '" alt="' . $cat_name . '" style="width: 38px; height: 38px; object-fit: contain; border-radius: 8px; background: #FFF8F0; border: 1px solid #F5E6D3; padding: 2px;">
                            <span class="fw-medium" style="color: #5D4037; font-size: 0.95rem;">' . $cat_name . '</span>
                        </a>
                      </li>';
                                }
                            } else {
                                echo '<li><a class="dropdown-item text-muted" href="#">No Categories Mapped</a></li>';
                            }
                            ?>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="<?php echo $site; ?>about.php">ABOUT</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $site; ?>blog.php">BLOG</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $site; ?>contact.php">CONTACT</a></li>
                </ul>

                <div class="nav-icons position-relative">
                    <span class="nav-icon-link me-3" id="search-trigger-btn" style="cursor:pointer;">
                        <i class="bi bi-search"></i>
                    </span>

                    <div class="search-overlay-form" id="search-dropdown-box">
                        <form action="<?php echo $site; ?>product.php" method="GET" class="d-flex gap-2">
<<<<<<< HEAD
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search delicious Makhana..." required>
                            <button type="submit" class="btn btn-sm btn-dark"
                                style="background:#8B4513; border:none;"><i class="bi bi-search"></i></button>
                        </form>
                    </div>

                    <a href="<?php echo $site; ?>wishlist.php" class="nav-icon-link me-3"><i
                            class="bi bi-heart"></i></a>
=======
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search delicious Makhana..." required>
                            <button type="submit" class="btn btn-sm btn-dark" style="background:#8B4513; border:none;"><i class="bi bi-search"></i></button>
                        </form>
                    </div>

                    <a href="<?php echo $site; ?>wishlist.php" class="nav-icon-link me-3"><i class="bi bi-heart"></i></a>
>>>>>>> 9f70d3fd0e71bcc96fe8fa18851eb6a1b9dc0084
                    <a href="<?php echo $site; ?>cart.php" class="nav-icon-link">
                        <i class="bi bi-bag"></i>
                        <span class="cart-count" id="header-cart-badge"><?php echo $total_cart_items; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>