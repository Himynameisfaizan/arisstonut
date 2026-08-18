<?php 
include('config/connect.php');
include('inc/header.php'); 
?>
   
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
                        <a href="product.php" class="btn btn-primary-custom">Buy Products <i class="bi bi-arrow-right ms-2"></i></a>
                        <a href="product.php" class="btn btn-outline-custom">View Products <i class="bi bi-grid ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hero-image-container text-center position-relative mt-5 mt-lg-0">
                        <img src="assets/images/hero.webp" width="100%" alt="AristoNut Makhana" class="img-fluid rounded-4">
                        
                        <!-- Fixed Hero Badge (Moved to bottom left so logo is visible) -->
                        <div class="hero-badge">
                            <div class="stars">★★★★★</div>
                            <div class="rating">4.9</div>
                            <small>RATED BEST</small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Products (Modern Design) -->
    <section class="quick-products bg-white">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2 class="fw-bold text-brown">Bestselling Makhana</h2>
                <p class="text-muted">Our most loved varieties by customers</p>
            </div>
            <div class="row g-4">
                <?php
                $product_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url FROM products WHERE status = 1 LIMIT 8";
                $product_result = $conn->query($product_query);

                if ($product_result && $product_result->num_rows > 0) {
                    while ($row = $product_result->fetch_assoc()) {
                        $p_id = $row['id'];
                        $p_name = htmlspecialchars($row['pro_name']);
                        $p_price = htmlspecialchars($row['selling_price']);
                        $p_weight = htmlspecialchars($row['qty']);
                        $p_slug = htmlspecialchars($row['slug_url']);
                        $p_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($row['pro_img']);
                ?>

                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="modern-product-card">
                                <?php
                                $is_wished = (isset($_SESSION['wishlist']) && in_array($p_id, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
                                ?>
                                <span class="modern-wishlist-btn" onclick="handleWishlist(<?php echo $p_id; ?>, this)">
                                    <i class="bi <?php echo $is_wished; ?>" style="font-size:1.1rem;"></i>
                                </span>

                                <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" class="text-decoration-none w-100">
                                    <div class="modern-img-circle">
                                        <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>">
                                    </div>
                                </a>

                                <div class="modern-product-info">
                                    <a href="<?php echo $site; ?>product/<?php echo $p_slug; ?>" class="text-decoration-none">
                                        <h5 class="modern-product-name text-truncate" title="<?php echo $p_name; ?>"><?php echo $p_name; ?></h5>
                                    </a>
                                    
                                    <p class="modern-product-weight">Net Wt: <?php echo !empty($p_weight) ? $p_weight : '100'; ?>g</p>
                                    
                                    <div class="modern-product-price">₹<?php echo $p_price; ?></div>

                                    <button class="modern-btn-add" onclick="addToCart(<?php echo $p_id; ?>)">
                                        Add to Basket
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
            
            <div class="text-center mt-5">
                <a href="<?php echo $site; ?>product.php" class="btn btn-primary-custom px-4 py-2 rounded-pill">View All Products <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- Info Banner Section -->
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
                <div class="card p-4 shadow-sm border-0 position-relative" style="background: #fffaf5; border-radius: 20px;">
                    <div class="position-absolute top-0 end-0 bg-danger text-white px-3 py-1 rounded-bl-3" style="border-radius: 0 20px 0 20px; font-weight: bold;">10% OFF</div>

                    <h2 class="text-danger text-center fw-bold mt-2">AristoNut</h2>
                    <h3 class="text-center text-danger">makhana</h3>
                    <p class="text-center text-muted small">chips ko bolo bye, makhana hai bhai!</p>

                    <img src="assets/images/hero.webp" alt="Makhana" width="100%" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-title">
                <h2>AristoNut, Taste the Excellence</h2>
                <p>India's finest premium makhana, crafted with tradition and perfection from the heart of Mithila, Darbhanga.</p>
            </div>
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-patch-check-fill"></i></div>
                        <h4>100% Premium</h4><p>Finest Quality</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-heart-pulse"></i></div>
                        <h4>Health First</h4><p>Nutritious & Tasty</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-flower2"></i></div>
                        <h4>Fresh Always</h4><p>Farm to Bowl</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-emoji-smile"></i></div>
                        <h4>Happy Customers</h4><p>A lot of them!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NEW: DYNAMIC BLOG SECTION -->
    <section class="blog-section py-5 mb-5" style="background-color: #fdfaf6;">
        <div class="container">
            <div class="section-title text-center mb-5">
                <span class="badge bg-white text-brown border mb-2 px-3 py-2 rounded-pill shadow-sm" style="color: #8B4513;">OUR BLOGS</span>
                <h2 class="fw-bold text-brown">Latest Health & Recipes</h2>
                <p class="text-muted">Stay updated with makhana recipes, health benefits, and news.</p>
            </div>
            
            <div class="row g-4">
                <?php
                // Fetch latest 3 blogs from database
                $blog_query = "SELECT blog_id, title, slug, image, description, created_at FROM blogs WHERE status = 1 ORDER BY blog_id DESC LIMIT 3";
                $blog_result = $conn->query($blog_query);

                if ($blog_result && $blog_result->num_rows > 0) {
                    while ($blog = $blog_result->fetch_assoc()) {
                        $b_title = htmlspecialchars($blog['title']);
                        $b_slug = htmlspecialchars($blog['slug']);
                        $b_date = date('d M, Y', strtotime($blog['created_at']));
                        
                        // Strip HTML tags and limit words for a clean UI
                        $b_desc = strip_tags($blog['description']);
                        $b_desc = strlen($b_desc) > 100 ? substr($b_desc, 0, 100) . '...' : $b_desc;

                        // Image fallback logic
                        $b_img = !empty($blog['image']) ? $site . 'admin/assets/img/uploads/blogs/' . htmlspecialchars($blog['image']) : $site . 'assets/images/hero.webp';
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card blog-card h-100 border-0 shadow-sm" style="border-radius: 16px;">
                        <div class="blog-img-wrapper">
                            <img src="<?php echo $b_img; ?>" alt="<?php echo $b_title; ?>">
                            <span class="blog-date-badge"><i class="bi bi-calendar3 me-1"></i> <?php echo $b_date; ?></span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title fw-bold text-brown mb-3" style="line-height: 1.4; color: #3E2723;"><?php echo $b_title; ?></h5>
                            <p class="card-text text-muted small mb-4 flex-grow-1"><?php echo $b_desc; ?></p>
                            <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $b_slug; ?>" class="btn btn-outline-brown rounded-pill fw-bold w-100 mt-auto" style="border: 2px solid #8B4513; color: #8B4513; transition: 0.3s;" onmouseover="this.style.background='#8B4513'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#8B4513';">
                                Read Full Article <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<div class='col-12 text-center'><p class='text-muted fs-5'>No blogs published yet. Stay tuned!</p></div>";
                }
                ?>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>
</body>
</html>