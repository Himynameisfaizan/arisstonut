
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AristoNut</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
<link rel="icon" type="image/webp" href="admin/uploads/logo/logo_header_6a2a43785437e.webp">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            
        }
        
        .navbar-nav .nav-link.active, .navbar-nav .nav-link.show{
            color: rgb(237 237 237);
        }
        
        .my-5{
                margin-top: 0rem !important;
    margin-bottom: 0rem !important;
        }
        
        /* Top Announcement Bar */
        .announcement-bar {
           background: linear-gradient(90deg, #ff0100, #ff0100);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .announcement-bar .badge {
           background: #7e0404 !important;
    color: #ffffff !important;
            font-weight: 600;
            margin-left: 10px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Navbar */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 20px rgba(139, 69, 19, 0.1);
            padding: 12px 0;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
        }
        
        .brand-text {
            font-size: 1.6rem;
            font-weight: 700;
            color: #8B4513;
            line-height: 1.2;
        }
        
        .brand-subtitle {
            font-size: 0.7rem;
            color: #A0522D;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .nav-link {
            color: #5D4037;
            font-weight: 500;
            margin: 0 5px;
            padding: 8px 16px !important;
            transition: all 0.3s;
            border-radius: 25px;
        }
        
        .nav-link:hover, .nav-link.active {
            color: #8B4513;
            background: #ff0100;
        }
        
        .nav-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .nav-icon {
            position: relative;
            color: #5D4037;
            font-size: 1.3rem;
            transition: color 0.3s;
            cursor: pointer;
        }
        
        .nav-icon:hover {
            color: #8B4513;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #ff0100 !important;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #FFF8F0 0%, #FFE4C4 50%, #FFF3E0 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(210, 105, 30, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .hero-tagline {
            display: inline-block;
            background: #ff0100;
            color: #ffffff !important;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 20px;
            animation: slideInLeft 0.8s ease-out;
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            color: #3E2723;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        
        .hero-title span {
            color: #ff0100;
            display: block;
        }
        
        .hero-description {
            font-size: 1.1rem;
            color: #6D4C41;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        .hero-buttons .btn {
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            margin-right: 15px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }
        
        .btn-primary-custom {
            background: #ff0100;
            color: #fff;
            border: 2px solid #8B4513;
        }
        
        .btn-primary-custom:hover {
            background: #6D3410;
            border-color: #6D3410;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
        }
        
        .btn-outline-custom {
               border: 2px solid #ff0100;
    color: #ff0100 !important;
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: #8B4513;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
        }
        
        .hero-image-container {
            position: relative;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .hero-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .hero-badge .stars {
            color: #FFD700;
            font-size: 1.2rem;
        }
        
        .hero-badge .rating {
            font-weight: 700;
            color: #8B4513;
            font-size: 1.5rem;
        }
        
        /* Quick Products Section */
        .quick-products {
            padding: 60px 0;
            background: #fff;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #3E2723;
            margin-bottom: 10px;
        }
        
        .section-title p {
            color: #8D6E63;
            font-size: 1.1rem;
        }
        
        .product-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.4s;
            margin-bottom: 30px;
            border: 2px solid #F5E6D3;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
            border-color: #8B4513;
        }
        
        .product-image {
            position: relative;
            overflow: hidden;
            height: 280px;
            background: #FFF8F0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
            transition: transform 0.5s;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
        .text-success{
            color: #ff0100 !important;
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff0100 !important;
            color: #fff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .product-info {
            padding: 25px;
            text-align: center;
        }
        
        .product-name {
            font-weight: 600;
            color: #3E2723;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .product-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: #ff0100 !important;
            margin-bottom: 15px;
        }
        
        .product-price .weight {
            font-size: 0.9rem;
            color: #8D6E63;
            font-weight: 400;
        }
        
        .btn-add-cart {
            background: #ff0100;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-add-cart:hover {
            background: #6D3410;
            transform: scale(1.05);
        }
        
        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #FFF8F0, #ffd9d9);
        }
        
        .feature-card {
            text-align: center;
            padding: 40px 20px;
            transition: all 0.3s;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: #ff0100 !important;
            box-shadow: 0 10px 30px rgba(139, 69, 19, 0.1);
        }
        
        .feature-card h4 {
            font-weight: 600;
            color: #3E2723;
            margin-bottom: 10px;
        }
        
        .feature-card p {
            color: #8D6E63;
        }
        
        /* Stats Section */
        .stats-section {
            padding: 60px 0;
            background: #fff;
        }
        
        .stat-item {
            text-align: center;
            padding: 30px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #ff0100;
        }
        
        .stat-label {
            color: #8D6E63;
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        /* Collection Section */
        .collection-section {
            padding: 80px 0;
            background: #fff;
        }
        
        .flavor-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border: 2px solid #F5E6D3;
            transition: all 0.4s;
            margin-bottom: 30px;
            position: relative;
        }
        
        .flavor-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
            border-color: #8B4513;
        }
        
        .flavor-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #8B4513;
            color: #fff;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .flavor-icon {
            width: 90px;
            height: 90px;
            background: #FFF8F0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
        }
        
        .flavor-name {
            font-weight: 700;
            color: #3E2723;
            margin-bottom: 5px;
        }
        
        .flavor-desc {
            color: #8D6E63;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .platform-btns {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-amazon {
            background: #FF9900;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-amazon:hover {
            background: #E68A00;
            color: #fff;
            transform: scale(1.05);
        }
        
        .btn-flipkart {
            background: #2874F0;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-flipkart:hover {
            background: #1E5FCC;
            color: #fff;
            transform: scale(1.05);
        }
        
        /* Footer */
        .footer {
            background: ##950201;
            color: #D7CCC8;
            padding: 60px 0 20px;
        }
        
        .footer h5 {
            color: #fff;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .footer a {
            color: #BCAAA4;
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .footer a:hover {
            color: #FFD700;
        }
        
        .footer .brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ff0100;
            margin-bottom: 15px;
        }
        
        .footer .subtitle {
            color: #BCAAA4;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        
        .whatsapp-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25D366;
            color: #fff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 5px 25px rgba(37, 211, 102, 0.4);
            transition: all 0.3s;
            z-index: 1000;
            text-decoration: none;
        }
        
        .whatsapp-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 30px rgba(37, 211, 102, 0.6);
            color: #fff;
        }
        
        .developer-credit {
            color: #BCAAA4;
            font-size: 0.85rem;
        }
        
        .developer-credit a {
            color: #FFD700;
            display: inline;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-section {
                padding: 50px 0;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>
    

    <?php include('inc/header.php');?>
    <section class="swiper-banner-section my-5">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            
            <div class="swiper-slide" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.4)), url('assets/images/1.png');">
                <div class="banner-content">
                    <h2>Mithila Ki Parampara</h2>
                    <p>Directly from the heart of Darbhanga, Bihar.</p>
                    <!--<a href="#" class="btn-swiper-custom">Explore Flavor <i class="bi bi-arrow-right ms-2"></i></a>-->
                </div>
            </div>

            <div class="swiper-slide" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.4)), url('assets/images/2.png');">
                <div class="banner-content">
                    <h2>100% Roasted, Zero Guilt</h2>
                    <p>Say goodbye to oily chips, switch to healthy Makhana.</p>
                    <!--<a href="#" class="btn-swiper-custom">Order Now <i class="bi bi-cart ms-2"></i></a>-->
                </div>
            </div>

            <div class="swiper-slide" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.4)), url('assets/images/3.png');">
                <div class="banner-content">
                    <h2>Premium Quality Superfood</h2>
                    <p>Handcrafted with love and perfection for your evening snacks.</p>
                    <!--<a href="#" class="btn-swiper-custom">View All Flavors <i class="bi bi-grid ms-2"></i></a>-->
                </div>
            </div>

            <div class="swiper-slide" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.4)), url('assets/images/4.png');">
                <div class="banner-content">
                    <h2>Crunchy & Irresistible</h2>
                    <p>Every bite is packed with nutrition and crispiness.</p>
                    <!--<a href="#" class="btn-swiper-custom">Buy Now <i class="bi bi-arrow-right ms-2"></i></a>-->
                </div>
            </div>

        </div>
        
        <div class="swiper-pagination"></div>
        
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

<style>

.mySwiper {
    width: 100%;
    height: 260px; /* Pehle se height badha di hai taaki background images lambi aur saaf dikhein */
}
    .swiper-slide {
    background-size: 100% 100%; /* Image ko bina kate poora khinch kar fit karega */
    background-position: center top;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #ffffff;
    
    /* Extra spacing taaki text border se na chipke */
    padding: 40px 30px; 
    border-radius: 12px; /* Agar corners round karne hon */
}

/* Banner Content Wrapper padding fix */
.banner-content {
    width: 100%;
    max-width: 90%; /* Content ko center me badhega, corners par dabne nahi dega */
    margin: 0 auto;
}

/* 🛠️ Hide Swiper Navigation Buttons */
.swiper-button-next,
.swiper-button-prev {
    display: none !important;
}

@media (max-width: 768px) {
    .swiper-slide {
        background-size: cover; /* Mobile par automatic behtareen cropping ke liye */
        padding: 20px 15px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        speed: 800, 
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        
        // Default settings (Mobile layout)
        slidesPerView: 1,
        spaceBetween: 10,

        // Responsive Breakpoints
        breakpoints: {
            768: {
                slidesPerView: 3,         // Desktop par strict 2 slides hi aayengi
                spaceBetween: 25          // Do banners ke beech ka safe gap
            }
        }
    });
</script>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-tagline">
                        🥜 Chips ko bolo bye, makhana hai bhai!
                    </div>
                    <h1 class="hero-title">
                        Premium Roasted
                        <span>Makhana Superfood</span>
                    </h1>
                    <p class="hero-description">
                        Indulge in our hand-roasted varieties – from classic plain to exotic flavors. 
                        Pure, nutritious, and irresistibly crunchy! India's finest premium makhana, 
                        crafted with tradition and perfection from the heart of Mithila, Darbhanga.
                    </p>
                    <div class="hero-buttons">
                        <a href="#" class="btn btn-primary-custom">Buy Now <i class="bi bi-arrow-right ms-2"></i></a>
                        <a href="#" class="btn btn-outline-custom">View Products <i class="bi bi-grid ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hero-image-container text-center">
                        <img src="assets/images/hero.webp" width="100% "alt="AristoNut Makhana" class="img-fluid rounded-4">
                        <div class="hero-badge">
                            <div class="stars">★★★★★</div>
                            <div class="rating">4.9</div>
                            <small style="color: #8D6E63;">Rated Best</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Quick Products -->
 <section class="quick-products">
    <div class="container">
        <div class="section-title">
            <h2>Bestselling Makhana</h2>
            <p>Our most loved varieties by customers</p>
        </div>
        <div class="row">
            <?php
            // Database se products fetch karna aur slug_url ko select query mein shamil rakhna
            $product_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url FROM products WHERE status = 1 LIMIT 6"; 
            $product_result = $conn->query($product_query);

            if ($product_result && $product_result->num_rows > 0) {
                while ($row = $product_result->fetch_assoc()) {
                    $p_id = $row['id'];
                    $p_name = htmlspecialchars($row['pro_name']);
                    $p_price = htmlspecialchars($row['selling_price']);
                    $p_weight = htmlspecialchars($row['qty']); 
                    $p_slug = htmlspecialchars($row['slug_url']);
                    
                    // Database standard asset paths sequence tracking configuration
                    $p_img = 'admin/assets/img/uploads/' . htmlspecialchars($row['pro_img']);
                    ?>
                    
                   <div class="col-md-6 col-lg-4">
    <div class="product-card">
        <div class="product-image position-relative">
            
            <?php 
            $is_wished = (isset($_SESSION['wishlist']) && in_array($p_id, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
            ?>
            <span class="position-absolute" style="top:15px; left:15px; background:#fff; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 4px 10px rgba(0,0,0,0.05); z-index:10;" onclick="handleWishlist(<?php echo $p_id; ?>, this)">
                <i class="bi <?php echo $is_wished; ?>" style="font-size:1.1rem;"></i>
            </span>

            <a href="product/<?php echo $p_slug; ?>">
                <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>">
            </a>
            <span class="product-badge">Best Seller</span>
        </div>
        <div class="product-info">
            <h5 class="product-name">
                <a href="product/<?php echo $p_slug; ?>" class="text-decoration-none text-dark hover-brown">
                    <?php echo $p_name; ?>
                </a>
            </h5>
            <div class="product-price">₹<?php echo $p_price; ?> <span class="weight">/ <?php echo $p_weight; ?>g</span></div>
            
            <button class="btn btn-add-cart" onclick="addToCart(<?php echo $p_id; ?>)">
                Add to Cart <i class="bi bi-cart-plus ms-2"></i>
            </button>
        </div>
    </div>
</div>

                    <?php
                }
            } else {
                echo "<div class='col-12 text-center'><p class='text-muted'>No products available at the moment.</p></div>";
            }
            ?>
        </div>
    </div>
</section>
    
 

<section class="container py-5">
  <div class="row align-items-center">
    
    <div class="col-md-6">
      <span class="badge bg-light text-dark border mb-2">✨ PREMIUM QUALITY</span>
      <h1 class="fw-bold mb-3">AristoNut, Taste the Excellence</h1>
      <p class="text-muted">
        India's finest AristoNut premium makhana, crafted with tradition and perfection from the heart of 
        <span class="text-success fw-bold">Mithila, Darbhanga</span>.
      </p>
      <p class="text-muted">
        Experience the perfect crunch and rich flavors of our handpicked makhana. A healthy snack that brings together taste, nutrition, and tradition in every bite.
      </p>
      
      <div class="row text-center mt-4 g-2">
        <div class="col-4 border-end">
          <p class="mb-0 fw-bold">100% Premium</p>
          <small class="text-muted">Finest Quality</small>
        </div>
        <div class="col-4 border-end">
          <p class="mb-0 fw-bold">Health First</p>
          <small class="text-muted">Nutritious & Tasty</small>
        </div>
        <div class="col-4">
          <p class="mb-0 fw-bold">Fresh Always</p>
          <small class="text-muted">Farm to Bowl</small>
        </div>
      </div>

      <div class="row mt-5 text-center">
        <div class="col-3">
          <h4 class="mb-0">A lot of</h4><small>Happy Customers</small>
        </div>
        <div class="col-3">
          <h4 class="mb-0">100%</h4><small>Premium Quality</small>
        </div>
        <div class="col-3">
          <h4 class="mb-0">7+</h4><small>Flavors</small>
        </div>
        <div class="col-3">
          <h4 class="mb-0">serves</h4><small>pan-India</small>
        </div>
      </div>
    </div>

    <div class="col-md-4 offset-md-1 mt-4 mt-md-0">
      <div class="card p-4 shadow-sm border-0 position-relative">
        <div class="position-absolute top-0 end-0 bg-danger text-white p-2 rounded-start">10% OFF</div>
        
        <h2 class="text-danger text-center fw-bold">AristoNut</h2>
        <h3 class="text-center text-danger">makhana</h3>
        <p class="text-center text-muted small">chips ko bolo bye, makhana hai bhai!</p>
        
       
        
        <img src="assets/images/hero.webp" alt="Makhana" width="100%"class="img-fluid rounded">
      </div>
    </div>
  </div>
</section>
    
    <!-- Features -->
    <section class="features-section">
        <div class="container">
            <div class="section-title">
                <h2>AristoNut, Taste the Excellence</h2>
                <p>India's finest premium makhana, crafted with tradition and perfection from the heart of Mithila, Darbhanga.</p>
            </div>
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <h4>100% Premium</h4>
                        <p>Finest Quality</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h4>Health First</h4>
                        <p>Nutritious & Tasty</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-flower2"></i>
                        </div>
                        <h4>Fresh Always</h4>
                        <p>Farm to Bowl</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <h4>Happy Customers</h4>
                        <p>A lot of them!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Premium Quality</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">7+</div>
                        <div class="stat-label">Flavors</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number">Pan-India</div>
                        <div class="stat-label">Serves</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Premium Collection -->
    <!--<section class="collection-section">-->
    <!--    <div class="container">-->
    <!--        <div class="section-title">-->
    <!--            <h2>Our Premium Collection</h2>-->
    <!--            <p>Handpicked makhana varieties crafted for your taste and wellness</p>-->
    <!--        </div>-->
            
            <!-- Raw Makhana -->
    <!--        <div class="row mb-4">-->
    <!--            <div class="col-12">-->
    <!--                <div class="flavor-card">-->
    <!--                    <span class="flavor-badge">Premium</span>-->
    <!--                    <div class="flavor-icon">🌸</div>-->
    <!--                    <h4 class="flavor-name">Premium Phool Makhana Raw</h4>-->
    <!--                    <p class="flavor-desc">Gluten-Free, Plant-Based & Calcium-Rich Foxnut Snack Lotus Seeds (Makhana)</p>-->
    <!--                    <div class="platform-btns">-->
    <!--                        <a href="#" class="btn btn-amazon"><i class="bi bi-amazon me-1"></i> Amazon</a>-->
    <!--                        <a href="#" class="btn btn-flipkart"><i class="bi bi-bag me-1"></i> Flipkart</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
            
            <!-- Flavored Makhana -->
    <!--        <div class="row">-->
    <!--            <div class="col-md-6 col-lg-4">-->
    <!--                <div class="flavor-card">-->
    <!--                    <span class="flavor-badge">Chatkara</span>-->
    <!--                    <div class="flavor-icon">🌶️</div>-->
    <!--                    <h4 class="flavor-name">Chatkara Flavored</h4>-->
    <!--                    <p class="flavor-desc">Tangy-spicy Chatkara makhana — bold and crunchy.</p>-->
    <!--                    <div class="platform-btns">-->
    <!--                        <a href="#" class="btn btn-amazon btn-sm">Amazon</a>-->
    <!--                        <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-6 col-lg-4">-->
    <!--                <div class="flavor-card">-->
    <!--                    <span class="flavor-badge">Cream & Onion</span>-->
    <!--                    <div class="flavor-icon">🧅</div>-->
    <!--                    <h4 class="flavor-name">Cream & Onion</h4>-->
    <!--                    <p class="flavor-desc">Creamy onion flavoured makhana — savory and smooth.</p>-->
    <!--                    <div class="platform-btns">-->
    <!--                        <a href="#" class="btn btn-amazon btn-sm">Amazon</a>-->
    <!--                        <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-6 col-lg-4">-->
    <!--                <div class="flavor-card">-->
    <!--                    <span class="flavor-badge">Mix Masala</span>-->
    <!--                    <div class="flavor-icon">🍛</div>-->
    <!--                    <h4 class="flavor-name">Mix Masala</h4>-->
    <!--                    <p class="flavor-desc">Classic Indian mix masala — aromatic and spicy.</p>-->
    <!--                    <div class="platform-btns">-->
    <!--                        <a href="#" class="btn btn-amazon btn-sm">Amazon</a>-->
    <!--                        <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-6 col-lg-4">-->
    <!--                <div class="flavor-card">-->
    <!--                    <span class="flavor-badge">Peri-Peri</span>-->
    <!--                    <div class="flavor-icon">🔥</div>-->
    <!--                    <h4 class="flavor-name">Peri-Peri</h4>-->
    <!--                    <p class="flavor-desc">Hot Peri-Peri makhana — fiery and flavorful.</p>-->
    <!--                    <div class="platform-btns">-->
    <!--                        <a href="#" class="btn btn-amazon btn-sm">Amazon</a>-->
    <!--                        <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-6 col-lg-4">-->
    <!--                <div class="flavor-card">-->
    <!--                    <span class="flavor-badge">Pudina</span>-->
    <!--                    <div class="flavor-icon">🌿</div>-->
    <!--                    <h4 class="flavor-name">Pudina</h4>-->
    <!--                    <p class="flavor-desc">Refreshing pudina mint makhana — cool and zesty.</p>-->
    <!--                    <div class="platform-btns">-->
    <!--                        <a href="#" class="btn btn-amazon btn-sm">Amazon</a>-->
    <!--                        <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-6 col-lg-4">-->
    <!--                <div class="flavor-card">-->
    <!--                    <span class="flavor-badge">Salt & Pepper</span>-->
    <!--                    <div class="flavor-icon">🧂</div>-->
    <!--                    <h4 class="flavor-name">Salt & Pepper</h4>-->
    <!--                    <p class="flavor-desc">Classic salt & pepper — simple and satisfying.</p>-->
    <!--                    <div class="platform-btns">-->
    <!--                        <a href="#" class="btn btn-amazon btn-sm">Amazon</a>-->
    <!--                        <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-6 col-lg-4">-->
    <!--                <div class="flavor-card">-->
    <!--                    <span class="flavor-badge">Tangy Cheese</span>-->
    <!--                    <div class="flavor-icon">🧀</div>-->
    <!--                    <h4 class="flavor-name">Tangy Cheese</h4>-->
    <!--                    <p class="flavor-desc">Tangy cheese coated makhana — cheesy delight.</p>-->
    <!--                    <div class="platform-btns">-->
    <!--                        <a href="#" class="btn btn-amazon btn-sm">Amazon</a>-->
    <!--                        <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
            
    <!--        <div class="text-center mt-4">-->
    <!--            <p class="text-muted">📦 All products available in 100g packs • Free shipping on orders over ₹500</p>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
    
   <?php include('inc/footer.php');?>
</body>
</html>