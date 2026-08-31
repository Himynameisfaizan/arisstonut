<?php
include('config/connect.php'); // Global variable dependencies and cookie session path check

// Get current page name for dynamic active links
$current_page = basename($_SERVER['PHP_SELF']);

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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Modern Fonts: Inter for UI, Playfair for headings if needed later -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="icon" type="image/webp" href="<?php echo $site; ?>admin/uploads/logo/logo_header_6a2a43785437e.webp">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/cart.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/category.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/contact.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/privacy.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/product.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/include.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/wishlist.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/blog.css?v=<?php echo time(); ?>">


</head>

<body>

<div class="premium-topbar">
    <div class="marquee-container">
        
        <!-- Main Content -->
        <div class="topbar-content">
            <span class="topbar-text">🎉 Free shipping on orders over ₹699</span>
            <span class="topbar-badge">10% OFF FIRST ORDER</span>
        </div>

        <!-- Duplicate Content (Only visible on mobile for infinite seamless scroll) -->
        <div class="topbar-content mobile-duplicate" aria-hidden="true">
            <span class="topbar-text">🎉 Free shipping on orders over ₹699</span>
            <span class="topbar-badge">10% OFF FIRST ORDER</span>
        </div>

    </div>
</div>

    <!-- 2. NAVBAR START -->
    <nav class="navbar navbar-expand-lg sticky-top modern-navbar">
        <div class="container d-flex justify-content-between align-items-center">

            <?php
            // PHP Logic for Logo
            $logo_img = $site . "assets/images/logo.webp";
            $logo_table_query = "SELECT `logo_path` FROM `logos` WHERE `location` = 'header' AND `is_active` = 1 LIMIT 1";
            $logo_table_res = $conn->query($logo_table_query);
            if ($logo_table_res && $logo_table_res->num_rows > 0) {
                $logo_row = $logo_table_res->fetch_assoc();
                if (!empty($logo_row['logo_path'])) {
                    $logo_img = $site . "admin/uploads/" . htmlspecialchars($logo_row['logo_path']);
                }
            }
            ?>
            <!-- LEFT: LOGO -->
            <a class="navbar-brand m-0 p-0" href="<?php echo $site; ?>index.php">
                <img src="<?php echo $logo_img; ?>" alt="AristoNut Logo" class="brand-logo-img">
            </a>

            <!-- CENTER: DESKTOP MENU WITH DYNAMIC ACTIVE STATE -->
            <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex desktop-nav">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php' || $current_page == '') ? 'active-link' : ''; ?>"
                            href="<?php echo $site; ?>index.php">HOME</a>
                    </li>

                    <li class="nav-item dropdown premium-dropdown">
                        <!-- Main Link -->
                        <a class="nav-link dropdown-toggle" href="<?php echo $site; ?>product.php" id="navbarProducts" role="button" aria-expanded="false">
                            Products
                        </a>

                        <!-- Hover Dropdown Menu -->
                        <ul class="dropdown-menu" aria-labelledby="navbarProducts">
                            <?php
                            // Fetch Top Categories & IMAGES dynamically from database
                            $head_cat_query = "SELECT categories, slug_url, image FROM categories WHERE status = 1 ORDER BY id ASC LIMIT 5";
                            $head_cat_res = $conn->query($head_cat_query);

                            if ($head_cat_res && $head_cat_res->num_rows > 0) {
                                while ($h_cat = $head_cat_res->fetch_assoc()) {
                                    $h_name = htmlspecialchars($h_cat['categories']);
                                    $h_slug = htmlspecialchars($h_cat['slug_url']);

                                    // Image Handling with Fallback
                                    $h_img = !empty($h_cat['image']) ? $site . 'admin/uploads/category/' . htmlspecialchars($h_cat['image']) : $site . 'assets/images/default-cat.png';

                                    // Print individual rich category link
                            ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo $site; ?>category/<?php echo $h_slug; ?>">
                                            <div class="nav-cat-img-box">
                                                <!-- Image included here -->
                                                <img src="<?php echo $h_img; ?>" alt="<?php echo $h_name; ?>" onerror="this.src='<?php echo $site; ?>assets/images/default-cat.png';">
                                            </div>
                                            <span><?php echo $h_name; ?></span>
                                        </a>
                                    </li>
                            <?php
                                }
                            } else {
                                // Fallback just in case DB is empty
                                echo '<li><a class="dropdown-item" href="' . $site . 'product.php"><div class="nav-cat-img-box"><i class="bi bi-box-seam text-muted"></i></div><span>Premium Makhana</span></a></li>';
                            }
                            ?>

                            <!-- View All Products Link at the bottom -->
                            <li class="dropdown-view-all">
                                <a class="dropdown-item" href="<?php echo $site; ?>product.php">
                                    View All Range <i class="bi bi-arrow-right"></i>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active-link' : ''; ?>"
                            href="<?php echo $site; ?>about.php">ABOUT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'blog.php') ? 'active-link' : ''; ?>"
                            href="<?php echo $site; ?>blog.php">BLOG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active-link' : ''; ?>"
                            href="<?php echo $site; ?>contact.php">CONTACT</a>
                    </li>
                </ul>
            </div>

            <!-- RIGHT: ICONS (Clean & Spacious) -->
            <div class="header-actions">
                <!-- Search -->
                <a href="#" class="nav-icon-link d-none d-sm-block" id="search-trigger-btn">
                    <i class="bi bi-search"></i>
                </a>

                <!-- Wishlist -->
                <a href="<?php echo $site; ?>wishlist.php" class="nav-icon-link d-sm-block">
                    <i class="bi bi-heart"></i>
                </a>

                <!-- Cart -->
                <a href="<?php echo $site; ?>cart.php" class="nav-icon-link">
                    <i class="bi bi-bag"></i>
                    <span class="cart-badge-circle"><?php echo $total_cart_items; ?></span>
                </a>

                <!-- Hamburger Icon (Mobile Only) -->
                <button class="menu-toggle-btn d-lg-none m-0 p-0" id="openSidebarBtn">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </nav>
    <!-- NAVBAR END -->

    <!-- 3. MODERN SIDEBAR FOR MOBILE -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="modern-sidebar" id="mobileSidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="<?php echo $site; ?>index.php">
                <img src="<?php echo $logo_img; ?>" alt="AristoNut" style="max-height: 35px;">
            </a>
            <button class="close-sidebar" id="closeSidebarBtn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Sidebar Navigation (With Dynamic Active State) -->
        <div class="sidebar-links">
            <a href="<?php echo $site; ?>index.php"
                class="<?php echo ($current_page == 'index.php' || $current_page == '') ? 'active-mobile' : ''; ?>">
                <i class="bi bi-house"></i> Home
            </a>
            <a href="<?php echo $site; ?>product.php"
                class="<?php echo ($current_page == 'product.php') ? 'active-mobile' : ''; ?>">
                <i class="bi bi-box-seam"></i> Shop Products
            </a>
            <a href="<?php echo $site; ?>about.php"
                class="<?php echo ($current_page == 'about.php') ? 'active-mobile' : ''; ?>">
                <i class="bi bi-info-circle"></i> About Us
            </a>
            <a href="<?php echo $site; ?>blog.php"
                class="<?php echo ($current_page == 'blog.php') ? 'active-mobile' : ''; ?>">
                <i class="bi bi-journal-text"></i> Blog
            </a>
            <a href="<?php echo $site; ?>contact.php"
                class="<?php echo ($current_page == 'contact.php') ? 'active-mobile' : ''; ?>">
                <i class="bi bi-envelope"></i> Contact
            </a>
        </div>

        <!-- Sidebar Bottom -->
        <div class="sidebar-bottom">
            <div class="d-flex flex-column gap-2">
                <a href="<?php echo $site; ?>profile.php"
                    class="btn btn-light border text-start rounded-pill px-4 shadow-sm" style="font-weight: 500;">
                    <i class="bi bi-person me-2"></i> Profile & Addresses
                </a>
                <a href="<?php echo $site; ?>wishlist.php"
                    class="btn btn-light border text-start rounded-pill px-4 shadow-sm" style="font-weight: 500;">
                    <i class="bi bi-heart me-2"></i> My Wishlist
                </a>
                <a href="<?php echo $site; ?>cart.php" class="btn text-white text-start rounded-pill px-4 shadow-sm"
                    style="background: var(--top-bar-bg); font-weight: 500;">
                    <i class="bi bi-bag me-2"></i> View Cart (<?php echo $total_cart_items; ?>)
                </a>
            </div>
        </div>
    </div>

    <!-- Vanilla JS for Sidebar -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const openBtn = document.getElementById('openSidebarBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
        });
    </script>