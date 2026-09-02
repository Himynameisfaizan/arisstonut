<?php
include('config/connect.php');
include('inc/header.php');
?>
<style>
    /* Modern Button Styles for Index Page */
    .modern-btn-add {
        background-color: #FFF8F0;
        color: #8B4513;
        border: 1.5px solid #8B4513;
        border-radius: 20px;
        padding: 8px 5px;
        font-size: 0.85rem;
        font-weight: 700;
        transition: all 0.3s;
        width: 50%;
    }

    .modern-btn-add:hover {
        background-color: #8B4513;
        color: #FFFFFF;
    }

    .modern-btn-buy {
        background-color: #8B4513;
        color: #FFFFFF;
        border: 1.5px solid #8B4513;
        border-radius: 20px;
        padding: 8px 5px;
        font-size: 0.85rem;
        font-weight: 700;
        transition: all 0.3s;
        width: 50%;
    }

    .modern-btn-buy:hover {
        background-color: #6D3410;
        border-color: #6D3410;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(139, 69, 19, 0.2);
    }
</style>

<section class="premium-hero">
    <div class="container">
        <!-- Swiper Start -->
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">

                <!-- SLIDE 1: Premium Foxnuts -->
                <div class="swiper-slide">
                    <div class="row align-items-center">
                        <div class="col-lg-6 order-2 order-lg-1">
                            <div class="hero-content-box">
                                <div class="hero-tagline">Born in Mithila</div>
                                <h1 class="hero-title">Mithila's Premium <span>Makhana</span> Reimagined.</h1>
                                <p class="hero-description">From the heart of Darbhanga, Bihar - premium foxnuts and
                                    innovative makhana-based foods crafted for today's lifestyle.</p>
                                <div class="hero-buttons-wrapper">
                                    <a href="product.php" class="hero-btn-primary">Shop Now <i
                                            class="bi bi-arrow-right ms-2"></i></a>
                                    <a href="about.php" class="hero-btn-outline">Our Story</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <!-- Image placed first on mobile via order classes -->
                            <img src="assets/images/raw-makhana/1.png" alt="Premium Foxnuts"
                                class="img-fluid floating-product">
                        </div>
                    </div>
                </div>

                <!-- SLIDE 2: Flavoured Snacks -->
                <div class="swiper-slide">
                    <div class="row align-items-center">
                        <div class="col-lg-6 order-2 order-lg-1">
                            <div class="hero-content-box">
                                <div class="hero-tagline">Bold Flavours</div>
                                <h1 class="hero-title">Big Crunch, <span>Zero Guilt.</span></h1>
                                <p class="hero-description">Explore our flavoured makhana range. From Chatkara to
                                    Peri-Peri and Cream & Onion, discover your favorite healthy snack.</p>
                                <div class="hero-buttons-wrapper">
                                    <a href="product.php?category=flavoured" class="hero-btn-primary">Shop Flavours <i
                                            class="bi bi-arrow-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="assets/images/flavoure/pack1/cream-onion/cream.png" alt="Flavoured Makhana"
                                class="img-fluid floating-product" style="animation-delay: 0.5s;">
                        </div>
                    </div>
                </div>

                <!-- SLIDE 3: Cookies & Pasta -->
                <div class="swiper-slide">
                    <div class="row align-items-center">
                        <div class="col-lg-6 order-2 order-lg-1">
                            <div class="hero-content-box">
                                <div class="hero-tagline">Modern Twist</div>
                                <h1 class="hero-title">More Than Just a <span>Snack.</span></h1>
                                <p class="hero-description">Bring the goodness of makhana into everyday meals with our
                                    gluten-free Cookies, Pasta, and flour.</p>
                                <div class="hero-buttons-wrapper">
                                    <a href="product.php?category=cookies-pasta" class="hero-btn-primary">Explore Range
                                        <i class="bi bi-arrow-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="assets/images/flavoure/pack1/chatkara/3.png" alt="Makhana Cookies and Pasta"
                                class="img-fluid floating-product" style="animation-delay: 1s;">
                        </div>
                    </div>
                </div>

            </div>
            <!-- Pagination Dots -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- SMALL TRUST LINE -->
    <div class="hero-trust-strip">
        <div class="container">
            <div class="row text-center m-0">
                <div class="col-lg-4 col-4 trust-item p-0">
                    <i class="bi bi-check2-circle"></i> Mithila Origin
                </div>
                <div class="col-lg-4 col-4 trust-item p-0">
                    <i class="bi bi-shield-check"></i> Premium Quality
                </div>
                <div class="col-lg-4 col-4 trust-item p-0">
                    <i class="bi bi-emoji-smile"></i> Crafted with Care
                </div>
            </div>
        </div>
    </div>
</section>

<section class="why-section">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <span class="section-subtitle">The AristoNut Promise</span>
            <h2 class="section-title">Why Choose AristoNut?</h2>
            <p class="section-desc">We bring you the purest, most nutritious makhana, crafted with uncompromising
                quality and deep respect for our heritage.</p>
        </div>

        <div class="row g-4">

            <!-- Point 1: Mithila Origin -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-card delay-1">
                    <div class="icon-box">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h3 class="feature-title">Mithila Origin</h3>
                    <p class="feature-text">Proudly rooted in Darbhanga, Bihar. Sourced directly from the heart of the
                        world's finest makhana farms.</p>
                </div>
            </div>

            <!-- Point 2: Food Safety & Quality Focused (Enhanced as per user demand) -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-card delay-2">
                    <div class="verified-badge"><i class="bi bi-shield-check"></i> 100% Pure</div>
                    <div class="icon-box safety-box">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <h3 class="feature-title">Quality Focused</h3>
                    <p class="feature-text">Strictly tested for food safety. Zero adulteration, unpolished, and
                        hygienically packed to preserve authentic taste.</p>
                </div>
            </div>

            <!-- Point 3: Makhana Specialists -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-card delay-3">
                    <div class="icon-box">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h3 class="feature-title">Makhana Specialists</h3>
                    <p class="feature-text">From premium foxnuts to innovative modern foods like cookies and pasta. We
                        know makhana best.</p>
                </div>
            </div>

            <!-- Point 4: Delivered To Your Door -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-card delay-4">
                    <div class="icon-box">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <h3 class="feature-title">Doorstep Delivery</h3>
                    <p class="feature-text">From our traditional homes in Mithila straight to your modern kitchen,
                        delivered fresh and crunchy.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SHOP BY CATEGORY (REFINED BENTO GRID) -->
<section class="category-section">
    <div class="container">
        <!-- Section Header -->
        <div class="cat-section-header">
            <span class="cat-subtitle">MORE THAN MAKHANA</span>
            <h2 class="cat-title">A World of Makhana.</h2>
            <p class="cat-desc">One traditional ingredient. Endless possibilities.</p>
        </div>

        <div class="row g-4">
            <?php
            // LIMIT 5 ensures the layout stays perfect (2 on top, 3 on bottom)
            $cat_query = "SELECT id, categories, slug_url, image FROM categories WHERE status = 1 ORDER BY id ASC LIMIT 5";
            $cat_result = $conn->query($cat_query);

            $pdf_content_map = [
                'raw-makhana' => [
                    'tagline' => 'Pure. Natural. Versatile.',
                    'desc' => 'Premium foxnuts from Mithila perfect for snacking, roasting and cooking.',
                    'bg_class' => 'bg-peach',
                ],
                'flavour-makhana' => [
                    'tagline' => 'BOLD FLAVOURS.',
                    'desc' => 'From Chatkara to Peri-Peri, discover your favourite crunch.',
                    'bg_class' => 'bg-mint',
                ],
                'cookies' => [
                    'tagline' => 'A MODERN TWIST.',
                    'desc' => 'Deliciously crunchy cookies with makhana at the heart of the recipe.',
                    'bg_class' => 'bg-sand',
                ],
                'pasta microni' => [
                    'tagline' => 'YOUR FAVOURITE MEALS.',
                    'desc' => 'Bring the goodness of makhana into everyday meals with our pasta.',
                    'bg_class' => 'bg-lavender',
                ],
                'makhana-aata-powder' => [
                    'tagline' => 'FOXNUTS TO FLOUR.',
                    'desc' => 'Finely ground makhana for cooking, baking and everyday recipes.',
                    'bg_class' => 'bg-rose',
                ]
            ];

            if ($cat_result && $cat_result->num_rows > 0) {
                $count = 0;
                while ($row = $cat_result->fetch_assoc()) {
                    $c_name = htmlspecialchars($row['categories']);
                    $c_slug = htmlspecialchars($row['slug_url']);

                    $mapped_data = isset($pdf_content_map[$c_slug]) ? $pdf_content_map[$c_slug] : [
                        'tagline' => 'PREMIUM QUALITY',
                        'desc' => 'Explore our delicious range of ' . $c_name,
                        'bg_class' => 'bg-peach',
                    ];

                    $c_img = !empty($row['image']) ? $site . 'admin/uploads/category/' . htmlspecialchars($row['image']) : $site . 'assets/images/default-cat.png';

                    // Layout Logic: Top 2 cards are 6-col (Horizontal layout), Bottom 3 cards are 4-col (Vertical layout)
                    if ($count < 2) {
                        $col_class = "col-lg-6 col-md-6";
                        $layout_class = "bento-horizontal";
                    } else {
                        $col_class = "col-lg-4 col-md-6";
                        $layout_class = "bento-vertical";
                    }
                    ?>
                    <div class="<?php echo $col_class; ?>">
                        <a href="<?php echo $site; ?>category/<?php echo $c_slug; ?>"
                            class="bento-card <?php echo $mapped_data['bg_class']; ?> <?php echo $layout_class; ?>">

                            <!-- Content Section -->
                            <div class="bento-content">
                                <div class="bento-tagline"><?php echo $mapped_data['tagline']; ?></div>
                                <h3 class="bento-title"><?php echo $c_name; ?></h3>
                                <p class="bento-desc"><?php echo $mapped_data['desc']; ?></p>
                                <div class="bento-arrow"><i class="bi bi-arrow-right"></i></div>
                            </div>

                            <!-- Image Section (Safely contained) -->
                            <div class="img-wrapper">
                                <img src="<?php echo $c_img; ?>" alt="<?php echo $c_name; ?>" class="bento-img">
                            </div>

                        </a>
                    </div>
                    <?php
                    $count++;
                }
            } else {
                echo "<div class='col-12 text-center'><p class='text-muted'>Categories coming soon.</p></div>";
            }
            ?>
        </div>

        <!-- 🔥 NEW: VIEW ALL CATEGORIES BUTTON 🔥 -->
        <div class="view-all-wrapper">
            <!-- Maine yaha 'product.php' link diya hai. Agar tumhara category page alag hai, toh isse update kar lena -->
            <a href="<?php echo $site; ?>product.php" class="btn-view-all">
                Explore All Categories <i class="bi bi-arrow-right"></i>
            </a>
        </div>

    </div>
</section>

<!-- SECTION 5: BRAND STORY (PREMIUM EDITORIAL DESIGN) -->
<section class="brand-story-section">
    <div class="container">
        <div class="row align-items-center">

            <!-- Left Side: Editorial Image -->
            <div class="col-lg-5 col-md-12 reveal-up" id="storyImage">
                <div class="story-image-wrapper">
                    <!-- SUGGESTION: Yahan koi achhi farm ki ya traditional makhana processing ki real image lagana -->
                    <!-- YEH NAYA SAHI CODE HAI -->
                    <img src="<?php echo $site; ?>assets/images/flavoure/pack1/chatkara/2.jpg"
                        alt="Mithila Origin Makhana" class="story-image">

                    <!-- Trust Badge Floating over image -->
                    <div class="story-badge">
                        <i class="bi bi-geo-alt-fill"></i>
                        <div class="story-badge-text">
                            Darbhanga, Bihar
                            <span>Origin of AristoNut</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Story Content -->
            <div class="col-lg-7 col-md-12">
                <div class="story-content-wrapper reveal-up" id="storyContent" style="transition-delay: 0.2s;">

                    <span class="story-subtitle">From the Heart of Mithila</span>
                    <h2 class="story-title">Born in Darbhanga.<br>Made for the World.</h2>

                    <p class="story-desc">
                        For generations, makhana has been an inseparable part of the land, culture, and livelihoods of
                        Mithila. The fertile lands of Darbhanga nurture the finest foxnuts, harvested with tradition and
                        care.
                    </p>

                    <p class="story-desc">
                        AristoNut was born in 2024 with a simple, genuine ambition: to take this treasured heritage
                        ingredient beyond traditional snacking and seamlessly bring it into modern, health-conscious
                        kitchens. From carefully selected raw foxnuts to our innovative makhana-based foods, we are
                        creating exciting new ways for people to discover and enjoy true nutrition.
                    </p>

                    <div class="story-highlight">
                        THIS IS OUR ORIGIN. THIS IS OUR PRIDE.<br>THIS IS ARISTONUT.
                    </div>

                    <a href="<?php echo $site; ?>about.php" class="btn-story">
                        Discover Our Story <i class="bi bi-arrow-right"></i>
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION 6: THE BIG BRAND STATEMENT (EVOLUTION TIMELINE DESIGN) -->
<section class="evolution-section">
    <div class="container">

        <!-- Headers -->
        <div class="evolve-header">
            <h2 class="evolve-title">
                ONE INGREDIENT.<br>
                <span>ENDLESS POSSIBILITIES.</span>
            </h2>
            <p class="evolve-desc">
                Makhana has been enjoyed in India for generations. At AristoNut, we are redefining how you experience
                this traditional superfood, taking it from farm to future with modern expressions.
            </p>
        </div>

        <!-- The Journey Grid (3 Columns on Desktop, elegantly wrapping) -->
        <div class="row g-4 justify-content-center">

            <!-- Step 1 -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="journey-card">
                    <div class="step-number-bg">01</div>
                    <div class="journey-icon"><i class="bi bi-flower2"></i></div>
                    <h3 class="journey-title">RAW Makhana</h3>
                    <p class="journey-text">We start with the finest, handpicked premium foxnuts directly from the
                        wetlands of Darbhanga, retaining their pure, unadulterated nutritional profile.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="journey-card">
                    <div class="step-number-bg">02</div>
                    <div class="journey-icon"><i class="bi bi-fire"></i></div>
                    <h3 class="journey-title">ROASTED & Flavoured</h3>
                    <p class="journey-text">Slowly air-roasted to achieve the perfect crunch and coated with bold,
                        guilt-free spices to satisfy your everyday snack cravings perfectly.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="journey-card">
                    <div class="step-number-bg">03</div>
                    <div class="journey-icon"><i class="bi bi-wind"></i></div>
                    <h3 class="journey-title">FLOUR (Aata)</h3>
                    <p class="journey-text">Finely milled into nutrient-dense makhana flour, bringing gluten-free
                        goodness to your baking, thick gravies, and traditional home cooking.</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="journey-card">
                    <div class="step-number-bg">04</div>
                    <div class="journey-icon"><i class="bi bi-cookie"></i></div>
                    <h3 class="journey-title">COOKIES & PASTA</h3>
                    <p class="journey-text">Transforming makhana into your favorite meals and sweet treats, making
                        healthy eating fun, accessible, and delicious for the whole family.</p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="journey-card future-card">
                    <div class="step-number-bg">05</div>
                    <div class="journey-icon"><i class="bi bi-stars"></i></div>
                    <h3 class="journey-title">MORE TO COME</h3>
                    <p class="journey-text">Our innovation never stops. We are continuously exploring new ways to bring
                        this heritage ingredient into the next generation of food.</p>
                </div>
            </div>

        </div>

        <!-- Footer Highlight strictly from PDF -->
        <div class="evolve-footer">
            From a traditional ingredient to a new generation of food.
        </div>

    </div>
</section>

<!-- SECTION 6 END -->
<section class="why-makhana-section">
    <div class="container">
        <div class="row align-items-center">

            <!-- Left Side: Lifestyle Image -->
            <div class="col-lg-5 col-md-12 slide-in-left" id="wmImgBox">
                <div class="wm-image-wrapper">
                    <!-- Note for admin: Use a high-quality image of raw makhana in a wooden bowl or similar aesthetic -->
                    <img src="<?php echo $site; ?>assets/images/why.jpeg"
                        alt="Mithila Origin Makhana" class="story-image">
                </div>
            </div>

            <!-- Right Side: Content & Features -->
            <div class="col-lg-7 col-md-12 slide-in-right" id="wmContentBox">
                <div class="wm-content-box">

                    <span class="wm-subtitle">THE SUPER INGREDIENT</span>
                    <h2 class="wm-title">Why Makhana?</h2>
                    <p class="wm-desc">
                        A traditional Indian ingredient with a rightful place in the modern kitchen. Packed with
                        nutrients and endless culinary potential.
                    </p>

                    <!-- 2x2 Grid from PDF -->
                    <div class="row">
                        <!-- 1. Versatile -->
                        <div class="col-md-6 col-sm-6">
                            <div class="wm-feature-item">
                                <div class="wm-icon-box"><i class="bi bi-arrow-repeat"></i></div>
                                <h4 class="wm-feature-title">Versatile</h4>
                                <p class="wm-feature-text">Snack it. Roast it. Cook it. Create with it. From sweet kheer
                                    to savory daily snacks.</p>
                            </div>
                        </div>

                        <!-- 2. Plant-Based (Green Hover) -->
                        <div class="col-md-6 col-sm-6">
                            <div class="wm-feature-item green-hover">
                                <div class="wm-icon-box"><i class="bi bi-flower2"></i></div>
                                <h4 class="wm-feature-title">Plant-Based</h4>
                                <p class="wm-feature-text">A naturally plant-based, gluten-free superfood loaded with
                                    protein and essential minerals.</p>
                            </div>
                        </div>

                        <!-- 3. Everyday Friendly -->
                        <div class="col-md-6 col-sm-6">
                            <div class="wm-feature-item">
                                <div class="wm-icon-box"><i class="bi bi-sun"></i></div>
                                <h4 class="wm-feature-title">Everyday Friendly</h4>
                                <p class="wm-feature-text">From quick office snacks to creative home recipes, it
                                    seamlessly fits your daily routine.</p>
                            </div>
                        </div>

                        <!-- 4. Indian Tradition -->
                        <div class="col-md-6 col-sm-6">
                            <div class="wm-feature-item">
                                <div class="wm-icon-box"><i class="bi bi-brightness-high"></i></div>
                                <h4 class="wm-feature-title">Indian Tradition</h4>
                                <p class="wm-feature-text">A cherished heritage ingredient, rooted in Indian tradition
                                    with a healthy modern future.</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <a href="<?php echo $site; ?>about.php" class="wm-btn mt-4">
                        Discover Makhana <i class="bi bi-arrow-right"></i>
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>
<!-- SECTION 7 END -->

<!-- SECTION 8: BESTSELLERS & LATEST BLOGS (EXACT VIDEO MATCH & MODERN UI) -->
<section class="homepage-tail-section">
    <div class="container">

       <div class="tail-header">
            <span class="tail-subtitle">YOUR NEXT FAVOURITE IS HERE</span>
            <h2 class="tail-title">AristoNut Bestsellers</h2>
            <p class="tail-desc">Not sure where to start? Start with what everyone loves.</p>
        </div>

        <div class="row g-4">
            <?php
            // Strict 8 products limit as requested by user
            $bestseller_query = "SELECT id, pro_name, selling_price, qty, pro_img, slug_url FROM products WHERE status = 1 ORDER BY id DESC LIMIT 8";
            $bestseller_result = $conn->query($bestseller_query);

            if ($bestseller_result && $bestseller_result->num_rows > 0) {
                while ($prod = $bestseller_result->fetch_assoc()) {
                    $pid = $prod['id'];
                    $pname = htmlspecialchars($prod['pro_name']);
                    $pprice = htmlspecialchars($prod['selling_price']);
                    $pweight = htmlspecialchars($prod['qty']);
                    $pslug = htmlspecialchars($prod['slug_url']);

                    // Correct image path from database
                    $pimg = !empty($prod['pro_img']) ? $site . 'admin/assets/img/uploads/' . htmlspecialchars($prod['pro_img']) : $site . 'assets/images/hero.webp';

                    // Wishlist check
                    $is_wished = (isset($_SESSION['wishlist']) && in_array($pid, $_SESSION['wishlist'])) ? 'bi-heart-fill text-danger' : 'bi-heart';
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="video-prod-card">

                            <!-- Wishlist Toggle -->
                            <div class="v-wish-btn" onclick="handleWishlist(<?php echo $pid; ?>, this)">
                                <i class="bi <?php echo $is_wished; ?>"></i>
                            </div>

                            <!-- Product Image with Hover Rotation -->
                            <a href="<?php echo $site; ?>product/<?php echo $pslug; ?>" class="v-img-box">
                                <img src="<?php echo $pimg; ?>" alt="<?php echo $pname; ?>">
                            </a>

                            <!-- Rating Stars -->
                            <div class="v-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <span class="text-muted ms-1">(4.9)</span>
                            </div>

                            <!-- Product Title & Weight -->
                            <a href="<?php echo $site; ?>product/<?php echo $pslug; ?>" class="v-title"
                                title="<?php echo $pname; ?>">
                                <?php echo $pname; ?>
                            </a>
                            <div class="v-weight">Net Wt: <?php echo !empty($pweight) ? $pweight : '100g'; ?></div>

                            <!-- Price & Side-by-Side Cart/Buy Now Buttons (Exact Video Match) -->
                            <div class="v-bottom-section">
                                <div class="v-price">₹<?php echo $pprice; ?></div>
                                <div class="v-action-buttons">
                                    <button class="v-btn-cart" onclick="addToCart(<?php echo $pid; ?>)">
                                        Cart
                                    </button>
                                    <button class="v-btn-buy" onclick="buyNow(<?php echo $pid; ?>)">
                                        Buy Now
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12 text-center'><p class='text-muted'>No bestsellers found right now.</p></div>";
            }
            ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?php echo $site; ?>product.php" class="btn btn-dark rounded-pill px-5 py-3 shadow-sm"
                style="background: var(--text-dark); font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px;">
                Shop All Bestsellers <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>


        <!-- Divider -->
        <hr class="section-divider">


        <!-- ================= LATEST BLOGS SECTION (IMPROVED CLEAN MAGAZINE STYLE) ================= -->
        <div class="tail-header">
            <span class="tail-subtitle">STAY UPDATED</span>
            <h2 class="tail-title">Latest Health & Recipes</h2>
            <p class="tail-desc">Discover delicious ways to bring makhana into your everyday kitchen.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            // Fetching active blogs from database
            $blog_query = "SELECT blog_id, title, slug, image, description, created_at FROM blogs WHERE status = 1 ORDER BY blog_id DESC LIMIT 3";
            $blog_result = $conn->query($blog_query);

            if ($blog_result && $blog_result->num_rows > 0) {
                while ($blog = $blog_result->fetch_assoc()) {
                    $btitle = htmlspecialchars($blog['title']);
                    $bslug = htmlspecialchars($blog['slug']);
                    $bdesc = strip_tags($blog['description']);
                    $bdate = date('d M, Y', strtotime($blog['created_at']));

                    // Image mapping
                    $bimg = !empty($blog['image']) ? $site . 'admin/assets/img/uploads/blogs/' . htmlspecialchars($blog['image']) : $site . 'assets/images/hero.webp';
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . parse_url($bimg, PHP_URL_PATH))) {
                        $bimg = $site . 'admin/assets/img/uploads/blogs/' . htmlspecialchars($blog['image']);
                    }
                    ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $bslug; ?>" class="modern-blog-card">

                            <div class="blog-img-box">
                                <img src="<?php echo $bimg; ?>" alt="<?php echo $btitle; ?>"
                                    onerror="this.src='https://thumbs.dreamstime.com/b/roasted-lotus-seed-makhana-22764990.jpg?w=768';">
                            </div>

                            <div class="blog-content">
                                <div class="blog-date"><i class="bi bi-calendar3 me-1"></i> <?php echo $bdate; ?></div>
                                <h3 class="blog-title"><?php echo $btitle; ?></h3>
                                <p class="blog-snippet"><?php echo $bdesc; ?></p>

                                <div class="blog-read-more">
                                    Read Full Article <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12 text-center'><p class='text-muted'>No health articles or recipes posted yet.</p></div>";
            }
            ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?php echo $site; ?>blog.php" class="btn btn-outline-dark rounded-pill px-5 py-3"
                style="font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px;">
                Explore All Articles <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

    </div>
</section>

<!-- Info Banner Section
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
                Experience the perfect crunch and rich flavors of our handpicked makhana. A healthy snack that brings
                together taste, nutrition, and tradition in every bite.
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
            <div class="card p-4 shadow-sm border-0 position-relative"
                style="background: #fffaf5; border-radius: 20px;">
                <div class="position-absolute top-0 end-0 bg-danger text-white px-3 py-1 rounded-bl-3"
                    style="border-radius: 0 20px 0 20px; font-weight: bold;">10% OFF</div>

                <h2 class="text-danger text-center fw-bold mt-2">AristoNut</h2>
                <h3 class="text-center text-danger">makhana</h3>
                <p class="text-center text-muted small">chips ko bolo bye, makhana hai bhai!</p>

                <img src="assets/images/hero.webp" alt="Makhana" width="100%" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="section-title">
            <h2>AristoNut, Taste the Excellence</h2>
            <p>India's finest premium makhana, crafted with tradition and perfection from the heart of Mithila,
                Darbhanga.</p>
        </div>
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <h4>100% Premium</h4>
                    <p>Finest Quality</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-heart-pulse"></i></div>
                    <h4>Health First</h4>
                    <p>Nutritious & Tasty</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-flower2"></i></div>
                    <h4>Fresh Always</h4>
                    <p>Farm to Bowl</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-emoji-smile"></i></div>
                    <h4>Happy Customers</h4>
                    <p>A lot of them!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="blog-section py-5 mb-5" style="background-color: #fdfaf6;">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="badge bg-white text-brown border mb-2 px-3 py-2 rounded-pill shadow-sm"
                style="color: #8B4513;">OUR BLOGS</span>
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
                                <h5 class="card-title fw-bold text-brown mb-3" style="line-height: 1.4; color: #3E2723;">
                                    <?php echo $b_title; ?>
                                </h5>
                                <p class="card-text text-muted small mb-4 flex-grow-1"><?php echo $b_desc; ?></p>
                                <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $b_slug; ?>"
                                    class="btn btn-outline-brown rounded-pill fw-bold w-100 mt-auto"
                                    style="border: 2px solid #8B4513; color: #8B4513; transition: 0.3s;"
                                    onmouseover="this.style.background='#8B4513'; this.style.color='#fff';"
                                    onmouseout="this.style.background='transparent'; this.style.color='#8B4513';">
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
</section> -->

<?php include('inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- NEW: BUY NOW SCRIPT -->
<script>
    // BUY NOW FUNCTION
    function buyNow(productId, variationId = 0, qty = 1) {
        $.ajax({
            url: '<?php echo $site; ?>cart_action.php',
            type: 'POST',
            data: {
                action: 'buy_now',
                product_id: productId,
                variation_id: variationId,
                quantity: qty
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Redirect to checkout specifically for Buy Now
                    window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true';
                } else {
                    showToast("Action Failed", response.message, "error");
                }
            },
            error: function () {
                showToast("System Error", "Could not connect to the server.", "error");
            }
        });
    }

    // ADD TO CART FUNCTION
    function addToCart(productId, variationId = 0, qty = 1) {
        $.ajax({
            url: '<?php echo $site; ?>cart_action.php',
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: productId,
                variation_id: variationId,
                quantity: qty
            },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    $('.cart-count').text(response.cart_count);
                    showToast("Added to Cart!", "Item successfully added to your basket.", "success");
                } else {
                    showToast("Action Failed", response.message, "error");
                }
            },
            error: function() {
                showToast("System Error", "Could not connect to the server.", "error");
            }
        });
    }
</script>

<!-- YEH SAHI KAREGA ISKO -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var heroSwiper = new Swiper(".heroSwiper", {
            spaceBetween: 30,
            effect: "fade",
            fadeEffect: {
                crossFade: true
            },
            loop: true,
            grabCursor: true, /* Shows hand cursor to indicate swipeability */
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    });
</script>

<!-- Vanilla JS for Scroll Reveal Animation -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.15 // Triggers when 15% of the card is visible
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Add slight delay for staggered effect based on column
                    setTimeout(() => {
                        entry.target.classList.add('revealed');
                    }, index * 100); // 100ms delay between each card revealing
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const cards = document.querySelectorAll('.feature-card');
        cards.forEach(card => {
            observer.observe(card);
        });
    });
</script>
<!-- WHY ARISTONUT SECTION END -->


<!-- Vanilla JS for Scroll Reveal Animation -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.2 // Triggers when 20% of the element is visible
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target); // Run animation only once
                }
            });
        }, observerOptions);

        // Observe the elements
        const storyImg = document.getElementById('storyImage');
        const storyText = document.getElementById('storyContent');

        if (storyImg) observer.observe(storyImg);
        if (storyText) observer.observe(storyText);
    });
</script>

<!-- Vanilla JS for Staggered Scroll Reveal -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const observerOpts = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const stmtObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOpts);

        const revealElements = document.querySelectorAll('.stmt-reveal');
        revealElements.forEach(el => stmtObserver.observe(el));
    });
</script>


<!-- Vanilla JS for Scroll Reveal -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const wmOptions = { root: null, rootMargin: '0px', threshold: 0.15 };

        const wmObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, wmOptions);

        const imgBox = document.getElementById('wmImgBox');
        const contentBox = document.getElementById('wmContentBox');

        if (imgBox) wmObserver.observe(imgBox);
        if (contentBox) wmObserver.observe(contentBox);
    });
</script>
</body>

</html>