<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database context mapping structure load line 1
include('config/connect.php'); 

// 1. Search Query Param Catch aur Sanitize karna
$search_query = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = $conn->real_escape_string(trim($_GET['search']));
}

// 2. Dynamic SQL Query Execution Setup
if (!empty($search_query)) {
    // Agar search parameters active hain, to filters execute honge
    $product_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url 
                      FROM products 
                      WHERE status = 1 
                      AND (pro_name LIKE '%$search_query%' OR description LIKE '%$search_query%' OR meta_title LIKE '%$search_query%') 
                      ORDER BY id DESC";
} else {
    // Default system trace par saare active products load honge
    $product_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url 
                      FROM products 
                      WHERE status = 1 
                      ORDER BY id DESC";
}

$product_result = $conn->query($product_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Premium Collection - AristoNut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
     <link rel="icon" type="image/png" href="assets/images/logo.webp">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; overflow-x: hidden; background: #fffaf5; }
        .text-brown { color: #8B4513; }
        
        /* Layout Header Context Design Elements */
        .announcement-bar { background: linear-gradient(90deg, #8B4513, #A0522D); color: #fff; text-align: center; padding: 10px 0; font-size: 0.9rem; font-weight: 500; letter-spacing: 0.5px; }
        .announcement-bar .badge { background: #FFD700; color: #8B4513; font-weight: 600; margin-left: 10px; animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        
        /* Products Core Presentation Dynamic Styles */
        .quick-products { padding: 60px 0; background: #fff; }
        .section-title { text-align: center; margin-bottom: 50px; }
        .section-title h2 { font-size: 2.5rem; font-weight: 700; color: #3E2723; margin-bottom: 10px; }
        .section-title p { color: #8D6E63; font-size: 1.1rem; }
        .product-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: all 0.4s; margin-bottom: 30px; border: 2px solid #F5E6D3; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15); border-color: #8B4513; }
        .product-image { position: relative; overflow: hidden; height: 280px; background: #FFF8F0; display: flex; align-items: center; justify-content: center; }
        .product-image img { max-width: 80%; max-height: 80%; object-fit: contain; transition: transform 0.5s; }
        .product-card:hover .product-image img { transform: scale(1.1); }
        .product-badge { position: absolute; top: 15px; right: 15px; background: #8B4513; color: #fff; padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .product-info { padding: 25px; text-align: center; }
        .product-name { font-weight: 600; color: #3E2723; margin-bottom: 10px; font-size: 1.1rem; }
        .product-price { font-size: 1.4rem; font-weight: 700; color: #8B4513; margin-bottom: 15px; }
        .product-price .weight { font-size: 0.9rem; color: #8D6E63; font-weight: 400; }
        .btn-add-cart { background: #8B4513; color: #fff; border: none; padding: 10px 30px; border-radius: 25px; font-weight: 500; transition: all 0.3s; width: 100%; cursor: pointer; }
        .btn-add-cart:hover { background: #6D3410; transform: scale(1.02); }
        .hover-brown { transition: color 0.2s; }
        .hover-brown:hover { color: #8B4513 !important; }
        
        @media (max-width: 768px) {
            .section-title h2 { font-size: 2rem; }
        }
    </style>
     <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: #fffaf5;
        }
        
        /* Top Announcement Bar */
        .announcement-bar {
            background: linear-gradient(90deg, #8B4513, #A0522D);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .announcement-bar .badge {
            background: #FFD700;
            color: #8B4513;
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
            background: #FFF3E0;
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
            background: #D2691E;
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
            background: #8B4513;
            color: #FFD700;
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
            color: #8B4513;
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
            background: #8B4513;
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
            border: 2px solid #8B4513;
            color: #8B4513;
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
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #8B4513;
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
            color: #8B4513;
            margin-bottom: 15px;
        }
        
        .product-price .weight {
            font-size: 0.9rem;
            color: #8D6E63;
            font-weight: 400;
        }
        
        .btn-add-cart {
            background: #8B4513;
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
            background: linear-gradient(135deg, #FFF8F0, #FFE4C4);
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
            color: #8B4513;
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
            color: #8B4513;
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
            background: #3E2723;
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
            color: #FFD700;
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
</head>
<body>

    <?php include('inc/header.php'); ?>

    <section class="quick-products">
        <div class="container">
            
            <?php if (!empty($search_query)): ?>
                <div class="mb-5 p-4 rounded-4 text-start" style="background:#FFF8F0; border: 2px dashed #F5E6D3;">
                    <h3 class="text-brown fw-bold mb-1">Search Results for: <span class="text-muted">"<?php echo htmlspecialchars($search_query); ?>"</span></h3>
                    <p class="text-muted small mb-3">Found <?php echo $product_result ? $product_result->num_rows : 0; ?> organic makhana varieties matching your parameters.</p>
                    <a href="<?php echo $site; ?>product.php" class="btn btn-sm text-white px-4 py-2" style="background:#8B4513; border-radius:20px; font-size:0.85rem; text-decoration:none;">Clear Filter & View All <i class="bi bi-x-circle ms-1"></i></a>
                </div>
            <?php else: ?>
                <div class="section-title">
                    <h2>Our Premium Collection</h2>
                    <p>Explore our healthy, authentic and delicious Makhana range</p>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php
                if ($product_result && $product_result->num_rows > 0) {
                    while ($row = $product_result->fetch_assoc()) {
                        $p_id = $row['id'];
                        $p_name = htmlspecialchars($row['pro_name']);
                        $p_price = htmlspecialchars($row['selling_price']);
                        $p_weight = htmlspecialchars($row['qty']); 
                        $p_slug = htmlspecialchars($row['slug_url']);
                        
                        // Absolute dynamic image logic setup to prevent sub-folder directory breaking
                        $p_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($row['pro_img']);
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

                                    <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" style="display:flex; width:100%; height:100%; align-items:center; justify-content:center;">
                                        <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>">
                                    </a>
                                    <span class="product-badge">Best Seller</span>
                                </div>
                                <div class="product-info">
                                    <h5 class="product-name">
                                        <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" class="text-decoration-none text-dark hover-brown">
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
                    // Empty Search Fallback State Interface Block Card
                    ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search-heart text-muted display-1 d-block mb-3"></i>
                        <h4 class="fw-bold text-muted">No Delicious Makhana Found!</h4>
                        <p class="text-muted small">We couldn't find anything matching "<strong><?php echo htmlspecialchars($search_query); ?></strong>". Try another keyword or clear filter.</p>
                        <a href="<?php echo $site; ?>product.php" class="btn text-white px-4 py-2 mt-3" style="background:#8B4513; border-radius:20px; text-decoration:none; font-size:0.9rem;">View All Premium Products</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>