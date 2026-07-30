<?php
session_start();
include('config/connect.php'); // Database connection entry point

// Agar cart empty hai to safety checkout bypass restriction rule apply karein
if (empty($_SESSION['cart'])) { 
    header("Location: cart.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - AristoNut Premium Makhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
     <link rel="icon" type="image/png" href="assets/images/logo.webp">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #fffaf5;
            color: #3E2723;
        }

        .text-brown {
            color: #8B4513;
        }

        /* Form Custom Design Card UI Layers */
        .checkout-card {
            background: #ffffff;
            border-radius: 20px;
            border: 2px solid #F5E6D3;
            box-shadow: 0 5px 20px rgba(139, 69, 19, 0.03);
            transition: all 0.3s ease;
        }

        .checkout-card:hover {
            border-color: #8B4513;
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.06);
        }

        .card-title-header {
            font-weight: 700;
            color: #3E2723;
            border-bottom: 2px dashed #F5E6D3;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        /* Custom Input Components */
        .form-control, .form-select {
            border: 2px solid #F5E6D3;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 0.95rem;
            background-color: #fff;
            color: #3E2723;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
            background-color: #fff;
        }

        /* Order Summary Line Items Grid elements */
        .product-summary-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #fff3e0;
        }

        .product-summary-item img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            background: #fffaf5;
            border: 1px solid #F5E6D3;
            border-radius: 8px;
        }

        .summary-total-block {
            background: #FFF8F0;
            border-radius: 12px;
            padding: 15px;
            border: 1px solid #FFE4C4;
        }

        /* Premium Actions Button UI */
        .btn-checkout-submit {
            background: #8B4513;
            color: #fff;
            border: 2px solid #8B4513;
            padding: 14px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-checkout-submit:hover {
            background: #6D3410;
            border-color: #6D3410;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 69, 19, 0.25);
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

    <?php include('inc/header.php');?>

    <main class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-brown"><i class="bi bi-shield-check me-2"></i>Secure Checkout</h2>
                <p class="text-muted small">Confirm your shipping credentials to parse your delicious premium Makhana boxes.</p>
            </div>
        </div>

        <form action="process_checkout.php" method="POST">
            <div class="row g-4">
                
                <div class="col-lg-7">
                    <div class="card checkout-card p-4">
                        <h5 class="card-title-header"><i class="bi bi-truck me-2 text-brown"></i>Shipping Address</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium small">First Name</label>
                                <input type="text" name="first_name" class="form-control" placeholder="John" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium small">Last Name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Doe" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium small">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="johndoe@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium small">Contact Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-medium small">Complete Address</label>
                            <textarea name="address" class="form-control" rows="4" placeholder="House/Flat No, Street Name, Landmark, City, State" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card checkout-card p-4">
                        <h5 class="card-title-header"><i class="bi bi-bag-check me-2 text-brown"></i>Order Basket</h5>
                        
                        <div class="product-summary-list-scroll mb-4" style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                            <?php 
                            $total = 0;
                            foreach ($_SESSION['cart'] as $pid => $item) {
                                // Dynamic wildcard evaluation matching product context schema specifications
                                $query = $conn->query("SELECT pro_name, selling_price, pro_img FROM products WHERE id = '$pid'");
                                $p = $query->fetch_assoc();
                                $subtotal = $p['selling_price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                            <div class="product-summary-item">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="admin/assets/img/uploads/<?php echo htmlspecialchars($p['pro_img']); ?>" alt="<?php echo htmlspecialchars($p['pro_name']); ?>">
                                    <div>
                                        <h6 class="mb-0 fw-bold small text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($p['pro_name']); ?></h6>
                                        <small class="text-muted">Qty: <?php echo $item['quantity']; ?> x ₹<?php echo $p['selling_price']; ?></small>
                                    </div>
                                </div>
                                <span class="fw-bold small text-brown">₹<?php echo $subtotal; ?></span>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="summary-total-block mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Cart Subtotal</span>
                                <span class="fw-medium small">₹<?php echo $total; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Delivery Charges</span>
                                <span class="text-success fw-medium small">FREE</span>
                            </div>
                            <hr class="my-2" style="border-top: 1px dashed #FFE4C4;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Amount</span>
                                <span class="fw-bold fs-4 text-brown">₹<?php echo $total; ?></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-brown"><i class="bi bi-credit-card me-2"></i>Select Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="COD">💵 Cash on Delivery (COD)</option>
                                <option value="Credit Card">💳 Secure Online Card Transaction</option>
                            </select>
                        </div>

                        <button type="submit" name="place_order" class="btn btn-checkout-submit">
                            Confirm & Place Order <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <?php include('inc/footer.php');?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>