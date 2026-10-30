<?php
include('config/connect.php');

$current_page = basename($_SERVER['PHP_SELF']);
$current_full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$total_cart_items = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['quantity'])) {
            $total_cart_items += intval($item['quantity']);
        }
    }
}

$meta_title = isset($meta_title) ? $meta_title : "AristoNut - Premium Quality Makhana";
$meta_desc = isset($meta_desc) ? $meta_desc : "AristoNut offers premium quality makhana and flavoured makhana from the heart of Mithila.";
$meta_keyword = isset($meta_keyword) ? $meta_keyword : "makhana, premium makhana, flavoured makhana, aristonut, fox nuts";
$schema_markup = isset($schema_markup) ? $schema_markup : "";

$og_title = isset($og_title) ? $og_title : $meta_title;
$og_desc = isset($og_desc) ? $og_desc : $meta_desc;
$og_image = isset($og_image) ? $og_image : $site . "admin/uploads/logo/logo_favicon_6accbb652f38b.png";

$seo_query = "SELECT meta_title, meta_desc, meta_key, schema_markup, og_title, og_desc, og_image FROM page_seo WHERE page_url = '$current_page' LIMIT 1";
$seo_res = @$conn->query($seo_query);

if ($seo_res && $seo_res->num_rows > 0) {
    $seo_row = $seo_res->fetch_assoc();

    if (!empty($seo_row['meta_title'])) $meta_title = $seo_row['meta_title'];
    if (!empty($seo_row['meta_desc'])) $meta_desc = $seo_row['meta_desc'];
    if (!empty($seo_row['meta_key'])) $meta_keyword = $seo_row['meta_key'];
    if (!empty($seo_row['schema_markup'])) $schema_markup = $seo_row['schema_markup'];

    if (!empty($seo_row['og_title'])) $og_title = $seo_row['og_title'];
    if (!empty($seo_row['og_desc'])) $og_desc = $seo_row['og_desc'];
    if (!empty($seo_row['og_image'])) {
        $og_image = $site . "admin/uploads/seo/" . $seo_row['og_image'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($meta_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_keyword); ?>">

    <link rel="canonical" href="<?php echo htmlspecialchars($current_full_url); ?>" />

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($current_full_url); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($og_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($og_desc); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($og_image); ?>">

    <?php
    if (!empty($schema_markup)) {
        if (strpos(trim($schema_markup), '<script') === 0) {
            echo $schema_markup;
        } else {
            echo "<!-- ERROR: Schema must be enclosed in <script type=\"application/ld+json\"> tags -->\n";
        }
    }
    ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/webp" href="<?php echo $site; ?>admin/uploads/logo/logo_favicon_6accbb652f38b.png">
    <link rel="stylesheet" href="https://cloudflare.com">

    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/cart.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $site; ?>assets/css/about.css?v=<?php echo time(); ?>">
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

            <div class="topbar-content">
                <span class="topbar-text">🎉 Free shipping on orders over ₹699</span>
                <span class="topbar-badge">10% OFF FIRST ORDER</span>
            </div>

            <div class="topbar-content mobile-duplicate" aria-hidden="true">
                <span class="topbar-text">🎉 Free shipping on orders over ₹699</span>
                <span class="topbar-badge">10% OFF FIRST ORDER</span>
            </div>

        </div>
    </div>

    <nav class="navbar navbar-expand-lg sticky-top modern-navbar">
        <div class="container d-flex justify-content-between align-items-center">

            <?php
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
            <a class="navbar-brand m-0 p-0" href="<?php echo $site; ?>./">
                <img src="<?php echo $logo_img; ?>" alt="AristoNut Logo" class="brand-logo-img">
            </a>

            <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex desktop-nav">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php' || $current_page == '') ? 'active-link' : ''; ?>"
                            href="<?php echo $site; ?>./">HOME</a>
                    </li>

                    <li class="nav-item dropdown premium-dropdown">
                        <!-- Main Link -->
                        <a class="nav-link dropdown-toggle" href="<?php echo $site; ?>product.php" id="navbarProducts"
                            role="button" aria-expanded="false">
                            Products
                        </a>

                        <!-- Hover Dropdown Menu -->
                        <ul class="dropdown-menu" aria-labelledby="navbarProducts">
                            <?php
                            $head_cat_query = "SELECT categories, slug_url, image FROM categories WHERE status = 1 ORDER BY id ASC LIMIT 5";
                            $head_cat_res = $conn->query($head_cat_query);

                            if ($head_cat_res && $head_cat_res->num_rows > 0) {
                                while ($h_cat = $head_cat_res->fetch_assoc()) {
                                    $h_name = htmlspecialchars($h_cat['categories']);
                                    $h_slug = htmlspecialchars($h_cat['slug_url']);

                                    $h_img = !empty($h_cat['image']) ? $site . 'admin/uploads/category/' . htmlspecialchars($h_cat['image']) : $site . 'assets/images/default-cat.png';

                            ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo $site; ?>category/<?php echo $h_slug; ?>">
                                    <div class="nav-cat-img-box">
                                        <!-- Image included here -->
                                        <img src="<?php echo $h_img; ?>" alt="<?php echo $h_name; ?>"
                                            onerror="this.src='<?php echo $site; ?>assets/images/default-cat.png';">
                                    </div>
                                    <span><?php echo $h_name; ?></span>
                                </a>
                            </li>
                            <?php
                                }
                            } else {
                                echo '<li><a class="dropdown-item" href="' . $site . 'product.php"><div class="nav-cat-img-box"><i class="bi bi-box-seam text-muted"></i></div><span>Premium Makhana</span></a></li>';
                            }
                            ?>

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
                <a href="<?php echo $site; ?>track-order.php"
                    class="btn btn-light border text-start rounded-pill px-4 shadow-sm" style="font-weight: 500;">
                    <i class="bi bi-geo-alt"></i> Track Your Order
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