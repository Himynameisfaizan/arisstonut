<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

<style>
    /* Base Reset & Core Styles */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Poppins', sans-serif; overflow-x: hidden; }
    .brand-logo-img { width: auto; height: 52px; object-fit: contain; border-radius: 8px; }
    .nav-icon-link { color: #5D4037; font-size: 1.3rem; transition: color 0.3s; text-decoration: none; position: relative; display: inline-block; }
    .nav-icon-link:hover { color: #8B4513; }
    
    /* Search Form Expansion UI Layout */
    .search-overlay-form { display: none; position: absolute; top: 100%; right: 0; background: #ffffff; padding: 12px; border-radius: 12px; box-shadow: 0 8px 25px rgba(139, 69, 19, 0.15); border: 1px solid #F5E6D3; z-index: 1001; width: 300px; }
    
    /* Top Announcement Bar */
    .announcement-bar { background: linear-gradient(90deg, #ff0100, #ff0100); color: #fff; text-align: center; padding: 10px 0; font-size: 0.9rem; font-weight: 500; letter-spacing: 0.5px; }
    .announcement-bar .badge { background: #7e0404 !important; color: #ffffff !important; font-weight: 600; margin-left: 10px; animation: pulse 2s infinite; }
    @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
    
    /* Navbar Containment Layer */
    .navbar { background: #fff; box-shadow: 0 2px 20px rgba(139, 69, 19, 0.1); padding: 12px 0; }
    .navbar-brand { display: flex; align-items: center; gap: 12px; }
    .brand-text { font-size: 1.6rem; font-weight: 700; color: #8B4513; line-height: 1.2; }
    .brand-subtitle { font-size: 0.7rem; color: #A0522D; font-weight: 500; letter-spacing: 2px; text-transform: uppercase; }
    .nav-link { color: #5D4037; font-weight: 500; margin: 0 5px; padding: 8px 16px !important; transition: all 0.3s; border-radius: 25px; }
    .nav-link:hover, .nav-link.active { color: #8B4513 !important; background: rgba(245, 230, 211, 0.5); }

    /* Premium Dropdown Manual Controls */
    .dropdown-menu { border: 1px solid #F5E6D3; box-shadow: 0 8px 25px rgba(139, 69, 19, 0.1); border-radius: 12px; padding: 10px 0; }
    
    /* Dropdown Display Force Manual Fix Trigger */
    .dropdown-menu.show { display: block !important; }
    .dropdown-item { color: #5D4037; font-weight: 500; padding: 8px 20px; transition: all 0.2s; }
    .dropdown-item:hover, .dropdown-item.active { background-color: #ff0100; color: #ffffff !important; }
    .nav-icons { display: flex; gap: 20px; align-items: center; }
    .cart-count { position: absolute; top: -8px; right: -12px; background: #ff0100 !important; color: #fff; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; display: flex; align-items: center; justify-content: center; font-weight: 600; }
    
    /* 📱 MOBILE RESPONSIVE ENGINE REBUILD OVERRIDES */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            display: none; /* jQuery maps show transition smoothly */
            width: 100%;
            padding-top: 15px;
        }
        .navbar-collapse.show-mobile-menu {
            display: block !important;
        }
        .navbar-nav {
            padding-bottom: 15px;
        }
        .nav-link {
            border-radius: 10px;
            margin: 4px 0;
            padding: 10px 15px !important;
        }
        .dropdown-menu {
            position: static !important;
            float: none;
            box-shadow: none;
            background-color: #fffaf5;
            border: 1px inset #F5E6D3;
            margin-top: 5px;
            padding: 5px 0;
        }
        .nav-icons {
            justify-content: flex-start;
            padding-left: 15px;
            padding-top: 10px;
            border-top: 1px dashed #F5E6D3;
        }
        .search-overlay-form {
            right: auto;
            left: 0;
            top: auto;
            bottom: 50px;
            width: 100%;
            max-width: 300px;
        }
    }
    
    @media (max-width: 768px) { 
        .hero-title { font-size: 2.2rem; } 
        .hero-section { padding: 50px 0; } 
        .section-title h2 { font-size: 2rem; } 
    }
</style>

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
        
        <button class="navbar-toggler" type="button" id="custom-mobile-toggler-btn">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="customNavbarCollapseMenu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo $site; ?>index.php">HOME</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" style="cursor: pointer;">
                        PRODUCTS
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item fw-bold text-dark border-bottom mb-1" href="<?php echo $site; ?>product.php">All Products</a></li>
                        <?php
                        $cate_nav_query = "SELECT `categories`, `slug_url` FROM `categories` WHERE `status` = 1 ORDER BY `id` ASC";
                        $cate_nav_res = $conn->query($cate_nav_query);

                        if ($cate_nav_res && $cate_nav_res->num_rows > 0) {
                            while($cat_row = $cate_nav_res->fetch_assoc()) {
                                $cat_name = htmlspecialchars($cat_row['categories']);
                                $cat_slug = htmlspecialchars($cat_row['slug_url']);
                                
                                echo '<li><a class="dropdown-item" href="' . $site . 'category/' . $cat_slug . '">' . $cat_name . '</a></li>';
                            }
                        } else {
                            echo '<li><a class="dropdown-item text-muted" href="#">No Categories Mapped</a></li>';
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="<?php echo $site; ?>about.php">ABOUT</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $site; ?>contact.php">CONTACT</a></li>
            </ul>
            
            <div class="nav-icons position-relative">
                <span class="nav-icon-link me-3" id="search-trigger-btn" style="cursor:pointer;">
                    <i class="bi bi-search"></i>
                </span>
                
                <div class="search-overlay-form" id="search-dropdown-box">
                    <form action="<?php echo $site; ?>product.php" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search delicious Makhana..." required>
                        <button type="submit" class="btn btn-sm btn-dark" style="background:#8B4513; border:none;"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <a href="<?php echo $site; ?>wishlist.php" class="nav-icon-link me-3"><i class="bi bi-heart"></i></a>
                <a href="<?php echo $site; ?>cart.php" class="nav-icon-link">
                    <i class="bi bi-bag"></i>
                    <span class="cart-count" id="header-cart-badge"><?php echo $total_cart_items; ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function(){
    // 1. 📱 MOBILE HAMBURGER TOGGLER BUTTON ACTION
    $('#custom-mobile-toggler-btn').on('click', function(e) {
        e.stopPropagation();
        $('#customNavbarCollapseMenu').toggleClass('show-mobile-menu');
    });

    // 2. Search Overlay Input Controller Node
    $('#search-trigger-btn').on('click', function(e){
        e.stopPropagation();
        $('#search-dropdown-box').fadeToggle(200);
    });

    $(document).on('click', function(e){
        if (!$(e.target).closest('#search-dropdown-box, #search-trigger-btn').length) {
            $('#search-dropdown-box').fadeOut(150);
        }
    });

    // 3. BULLETPROOF DROPDOWN CLICK HANDLER (Mobile + Desktop Manual Override)
    $('.dropdown-toggle').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        var targetDropdown = $(this).next('.dropdown-menu');
        
        // Kisi dusre open dropdown state ko close karna
        $('.dropdown-menu').not(targetDropdown).removeClass('show');
        
        // Toggle current dropdown
        targetDropdown.toggleClass('show');
    });

    // Outer screen parameters click reset actions
    $(document).on('click', function(e){
        // Agar mobile menu ke bahar click ho to menu aur dropdown dono close karein
        if (!$(e.target).closest('#customNavbarCollapseMenu, #custom-mobile-toggler-btn').length) {
            $('#customNavbarCollapseMenu').removeClass('show-mobile-menu');
            $('.dropdown-menu').removeClass('show');
        }
    });

    // 4. Navigation Dynamic Page Active Highlighters Setup
    var currentUrl = window.location.pathname.split("/").pop();
    if(currentUrl == "") currentUrl = "index.php";
    
    $('.navbar-nav .nav-link').each(function(){
        var hrefVal = $(this).attr('href').split("/").pop();
        if(hrefVal == currentUrl && hrefVal !== "#" && hrefVal !== ""){
            $('.navbar-nav .nav-link').removeClass('active');
            $(this).addClass('active');
        }
    });
});
</script>