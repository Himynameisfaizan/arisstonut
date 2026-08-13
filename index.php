    <?php include('inc/header.php'); ?>
   
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
                        <a href="#" class="btn btn-primary-custom">Buy  Products  <i class="bi bi-arrow-right ms-2"></i></a>
                        <a href="#" class="btn btn-outline-custom">View Products <i class="bi bi-grid ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hero-image-container text-center">
                        <img src="assets/images/hero.webp" width="100% " alt="AristoNut Makhana" class="img-fluid rounded-4">
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



                    <img src="assets/images/hero.webp" alt="Makhana" width="100%" class="img-fluid rounded">
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
    <!-- <section class="collection-section">
       <div class="container">
           <div class="section-title">
               <h2>Our Premium Collection</h2>
               <p>Handpicked makhana varieties crafted for your taste and wellness</p>
           </div>

           <div class="row mb-4">
               <div class="col-12">
                   <div class="flavor-card">
                       <span class="flavor-badge">Premium</span>
                       <div class="flavor-icon">🌸</div>
                       <h4 class="flavor-name">Premium Phool Makhana Raw</h4>
                       <p class="flavor-desc">Gluten-Free, Plant-Based & Calcium-Rich Foxnut Snack Lotus Seeds (Makhana)</p>
                       <div class="platform-btns">
                           <a href="#" class="btn btn-amazon"><i class="bi bi-amazon me-1"></i> Amazon</a>
                           <a href="#" class="btn btn-flipkart"><i class="bi bi-bag me-1"></i> Flipkart</a>
                       </div>
                   </div>
               </div>
           </div>

           <div class="row">
               <div class="col-md-6 col-lg-4">
                   <div class="flavor-card">
                       <span class="flavor-badge">Chatkara</span>
                       <div class="flavor-icon">🌶️</div>
                       <h4 class="flavor-name">Chatkara Flavored</h4>
                       <p class="flavor-desc">Tangy-spicy Chatkara makhana — bold and crunchy.</p>
                       <div class="platform-btns">
                           <a href="#" class="btn btn-amazon btn-sm">Amazon</a>
                           <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>
                       </div>
                   </div>
               </div>
               <div class="col-md-6 col-lg-4">
                   <div class="flavor-card">
                       <span class="flavor-badge">Cream & Onion</span>
                       <div class="flavor-icon">🧅</div>
                       <h4 class="flavor-name">Cream & Onion</h4>
                       <p class="flavor-desc">Creamy onion flavoured makhana — savory and smooth.</p>
                       <div class="platform-btns">
                           <a href="#" class="btn btn-amazon btn-sm">Amazon</a>
                           <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>
                       </div>
                   </div>
               </div>
               <div class="col-md-6 col-lg-4">
                   <div class="flavor-card">
                       <span class="flavor-badge">Mix Masala</span>
                       <div class="flavor-icon">🍛</div>
                       <h4 class="flavor-name">Mix Masala</h4>
                       <p class="flavor-desc">Classic Indian mix masala — aromatic and spicy.</p>
                       <div class="platform-btns">
                           <a href="#" class="btn btn-amazon btn-sm">Amazon</a>
                           <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>
                       </div>
                   </div>
               </div>
               <div class="col-md-6 col-lg-4">
                   <div class="flavor-card">
                       <span class="flavor-badge">Peri-Peri</span>
                       <div class="flavor-icon">🔥</div>
                       <h4 class="flavor-name">Peri-Peri</h4>
                       <p class="flavor-desc">Hot Peri-Peri makhana — fiery and flavorful.</p>
                       <div class="platform-btns">
                           <a href="#" class="btn btn-amazon btn-sm">Amazon</a>
                           <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>
                       </div>
                   </div>
               </div>
               <div class="col-md-6 col-lg-4">
                   <div class="flavor-card">
                       <span class="flavor-badge">Pudina</span>
                       <div class="flavor-icon">🌿</div>
                       <h4 class="flavor-name">Pudina</h4>
                       <p class="flavor-desc">Refreshing pudina mint makhana — cool and zesty.</p>
                       <div class="platform-btns">
                           <a href="#" class="btn btn-amazon btn-sm">Amazon</a>
                           <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>
                       </div>
                   </div>
               </div>
               <div class="col-md-6 col-lg-4">
                   <div class="flavor-card">
                       <span class="flavor-badge">Salt & Pepper</span>
                       <div class="flavor-icon">🧂</div>
                       <h4 class="flavor-name">Salt & Pepper</h4>
                       <p class="flavor-desc">Classic salt & pepper — simple and satisfying.</p>
                       <div class="platform-btns">
                           <a href="#" class="btn btn-amazon btn-sm">Amazon</a>
                           <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>
                       </div>
                   </div>
               </div>
               <div class="col-md-6 col-lg-4">
                   <div class="flavor-card">
                       <span class="flavor-badge">Tangy Cheese</span>
                       <div class="flavor-icon">🧀</div>
                       <h4 class="flavor-name">Tangy Cheese</h4>
                       <p class="flavor-desc">Tangy cheese coated makhana — cheesy delight.</p>
                       <div class="platform-btns">
                           <a href="#" class="btn btn-amazon btn-sm">Amazon</a>
                           <a href="#" class="btn btn-flipkart btn-sm">Flipkart</a>
                       </div>
                   </div>
               </div>
           </div>

           <div class="text-center mt-4">
               <p class="text-muted">📦 All products available in 100g packs • Free shipping on orders over ₹500</p>
           </div>
       </div>
    </section> -->

    <?php include('inc/footer.php'); ?>
</body>

</html>