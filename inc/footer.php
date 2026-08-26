<?php
// Database se live metrics data fetch karna wildcard variables mapping parameters se
$footer_contact_query = "SELECT phone, email, address, wp_number FROM contacts LIMIT 1";
$footer_contact_res = $conn->query($footer_contact_query);

// Static Fallbacks agar database profile empty ho
$db_phone = "+91 99997 28084";
$db_email = "aristonut@gmail.com";
$db_address = "Subhankarpur, Darbhanga, Bihar-846004";
$db_wp = "919999728084";

if ($footer_contact_res && $footer_contact_res->num_rows > 0) {
    $f_info = $footer_contact_res->fetch_assoc();
    if (!empty($f_info['phone']))
        $db_phone = htmlspecialchars($f_info['phone']);
    if (!empty($f_info['email']))
        $db_email = htmlspecialchars($f_info['email']);
    if (!empty($f_info['address']))
        $db_address = htmlspecialchars($f_info['address']);
    if (!empty($f_info['wp_number']))
        $db_wp = preg_replace('/[^0-9]/', '', $f_info['wp_number']);
}

// WhatsApp call optimization dynamic configuration logic
$wp_clean_link = preg_replace('/[^0-9]/', '', $db_wp);
?>

<style>
    :root {
        --footer-bg: #1F130E;
        /* Rich Dark Warm Brown */
        --footer-text: #D7CCC8;
        --footer-heading: #FFFFFF;
        --footer-accent: #C67D44;
        /* Warm Gold/Copper Accent */
    }

    .footer {
        background-color: var(--footer-bg) !important;
        color: var(--footer-text);
        padding: 80px 0 30px;
        font-family: 'Poppins', sans-serif;
        position: relative;
        border-top: 4px solid var(--footer-accent);
    }

    .footer h5 {
        color: var(--footer-heading);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 25px;
        position: relative;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .footer h5::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 35px;
        height: 2px;
        background: var(--footer-accent);
    }

    /* Links Styling */
    .footer-links-col a {
        color: var(--footer-text) !important;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        margin-bottom: 10px;
        font-size: 0.9rem;
        width: 100%;
        opacity: 0.85;
    }

    .footer-links-col a:hover {
        color: var(--footer-accent) !important;
        transform: translateX(5px);
        opacity: 1;
    }

    .footer .brand {
        font-size: 2rem;
        font-weight: 800;
        color: #FFFFFF;
        margin-bottom: 2px;
        letter-spacing: 0.5px;
    }

    .footer .subtitle {
        color: var(--footer-accent);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 15px;
    }

    .footer p {
        line-height: 1.7;
        font-size: 0.9rem;
        color: var(--footer-text);
        opacity: 0.8;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
        color: var(--footer-text);
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .contact-item i {
        color: var(--footer-accent);
        font-size: 1.1rem;
        margin-top: 2px;
    }

    .contact-item a {
        color: var(--footer-text) !important;
        text-decoration: none;
        transition: color 0.3s;
    }

    .contact-item a:hover {
        color: var(--footer-accent) !important;
    }

    /* Social Icons */
    .footer-social-icons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .footer-social-icons a {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #FFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all 0.3s ease;
        margin-bottom: 0;
    }

    .footer-social-icons a:hover {
        background: var(--footer-accent);
        border-color: var(--footer-accent);
        color: #FFF !important;
        transform: translateY(-3px);
    }

    .footer hr {
        border-color: rgba(255, 255, 255, 0.08) !important;
        margin: 45px 0 25px;
    }

    /* Floating Buttons Styling */
    .floating-btn {
        position: fixed;
        right: 30px;
        color: #fff;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 9999;
        text-decoration: none;
    }

    .whatsapp-btn {
        bottom: 30px;
        background: #25D366;
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.35);
    }

    .whatsapp-btn:hover {
        transform: scale(1.1) rotate(10deg);
        color: #fff;
        box-shadow: 0 12px 30px rgba(37, 211, 102, 0.5);
    }

    .phone-btn {
        bottom: 95px;
        background: var(--footer-accent);
        box-shadow: 0 8px 25px rgba(198, 125, 68, 0.35);
    }

    .phone-btn:hover {
        transform: scale(1.1) rotate(-10deg);
        color: #fff;
        box-shadow: 0 12px 30px rgba(198, 125, 68, 0.5);
    }
</style>

<footer class="footer">
    <div class="container">
        <div class="row g-4">

            <!-- Col 1: Brand Info & Socials -->
            <div class="col-lg-4 col-md-6 mb-3">
                <?php
                $footer_logo_img = "";
                $footer_logo_query = "SELECT `logo_path` FROM `logos` WHERE `location` = 'header' AND `is_active` = 1 LIMIT 1";
                $footer_logo_res = $conn->query($footer_logo_query);

                if ($footer_logo_res && $footer_logo_res->num_rows > 0) {
                    $footer_logo_row = $footer_logo_res->fetch_assoc();
                    if (!empty($footer_logo_row['logo_path'])) {
                        $footer_logo_img = $site . "admin/uploads/" . htmlspecialchars($footer_logo_row['logo_path']);
                    }
                }
                ?>

                <!-- Footer Logo Box Code Replace -->
                <?php if (!empty($footer_logo_img)): ?>
                    <div class="footer-logo-box mb-3">
                        <a href="<?php echo $site; ?>index.php">
                            <img src="<?php echo $footer_logo_img; ?>" alt="AristoNut Logo" class="brand-logo-img"
                                style="height: 48px; width: auto; object-fit: contain;">
                        </a>
                    </div>
                <?php else: ?>
                    <div class="brand">AristoNut</div>
                <?php endif; ?>

                <div class="subtitle">Premium Quality</div>
                <p class="pe-lg-4 mb-3">
                    India's finest premium makhana, crafted with tradition and quality. Experience the perfect blend of
                    health, crispness, and delicious taste.
                </p>

                <!-- Parent Company Tag -->
                <div class="mb-4">
                    <span class="d-inline-block px-3 py-1 rounded-pill"
                        style="font-size: 0.75rem; font-weight: 600; letter-spacing: 1px; background: rgba(198, 125, 68, 0.15); color: #E6B180; border: 1px solid rgba(198, 125, 68, 0.3);">
                        A BRAND BY NK ENTERPRISES
                    </span>
                </div>

                <?php
                $social_query = "SELECT `facebook`, `instagram`, `twitter`, `linkdin` FROM `contacts` LIMIT 1";
                $social_res = $conn->query($social_query);

                $fb_link = $insta_link = $twitter_link = $linkedin_link = "";

                if ($social_res && $social_res->num_rows > 0) {
                    $social_row = $social_res->fetch_assoc();
                    $fb_link = trim($social_row['facebook']);
                    $insta_link = trim($social_row['instagram']);
                    $twitter_link = trim($social_row['twitter']);
                    $linkedin_link = trim($social_row['linkdin']);
                }
                ?>

                <div class="footer-social-icons">
                    <?php if (!empty($fb_link)): ?>
                        <a href="<?php echo htmlspecialchars($fb_link); ?>" target="_blank" title="Facebook"><i
                                class="bi bi-facebook"></i></a>
                    <?php endif; ?>

                    <?php if (!empty($insta_link)): ?>
                        <a href="<?php echo htmlspecialchars($insta_link); ?>" target="_blank" title="Instagram"><i
                                class="bi bi-instagram"></i></a>
                    <?php endif; ?>

                    <?php if (!empty($twitter_link)): ?>
                        <a href="<?php echo htmlspecialchars($twitter_link); ?>" target="_blank" title="Twitter/X"><i
                                class="bi bi-twitter"></i></a>
                    <?php endif; ?>

                    <?php if (!empty($linkedin_link)): ?>
                        <a href="<?php echo htmlspecialchars($linkedin_link); ?>" target="_blank" title="LinkedIn"><i
                                class="bi bi-linkedin"></i></a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-2 col-md-6 col-6 mb-3 footer-links-col">
                <h5>Quick Links</h5>
                <a href="<?php echo $site; ?>index.php">Home</a>
                <a href="<?php echo $site; ?>about.php">About Us</a>
                <a href="<?php echo $site; ?>product.php">Products</a>
                <a href="<?php echo $site; ?>contact.php">Contact</a>
                <a href="<?php echo $site; ?>privacy-policy.php">Privacy Policy</a>
                <a href="<?php echo $site; ?>terms-conditions.php">Terms & Conditions</a>
            </div>

            <!-- Col 3: Our Products -->
            <div class="col-lg-3 col-md-6 col-6 mb-3 footer-links-col">
                <h5>Our Products</h5>
                <?php
                $footer_prod_query = "SELECT categories, slug_url FROM categories WHERE status = 1 ORDER BY id DESC LIMIT 6";
                $footer_prod_res = $conn->query($footer_prod_query);

                if ($footer_prod_res && $footer_prod_res->num_rows > 0) {
                    while ($f_prod = $footer_prod_res->fetch_assoc()) {
                        $f_prod_name = htmlspecialchars($f_prod['categories']);
                        $f_prod_slug = htmlspecialchars($f_prod['slug_url']);

                        echo '<a href="' . $site . 'category/' . $f_prod_slug . '">' . $f_prod_name . '</a>';
                    }
                } else {
                    echo '<a href="' . $site . 'product.php">Premium Makhana</a>';
                    echo '<a href="' . $site . 'product.php">Flavored Makhana</a>';
                    echo '<a href="' . $site . 'product.php">Organic Makhana</a>';
                }
                ?>
            </div>

            <!-- Col 4: Get In Touch -->
            <div class="col-lg-3 col-md-6 mb-3">
                <h5>Get In Touch</h5>
                <div class="contact-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <p class="mb-0"><?php echo $db_address; ?></p>
                </div>
                <div class="contact-item">
                    <i class="bi bi-telephone-fill"></i>
                    <p class="mb-0">
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $db_phone); ?>">
                            <?php echo $db_phone; ?>
                        </a>
                    </p>
                </div>
                <div class="contact-item">
                    <i class="bi bi-envelope-fill"></i>
                    <p class="mb-0">
                        <a href="mailto:<?php echo $db_email; ?>">
                            <?php echo $db_email; ?>
                        </a>
                    </p>
                </div>
            </div>

        </div>

        <hr>

        <!-- Bottom Copyright Row -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <p class="mb-0 small text-muted" style="color: #A1887F !important;">
                    © <?php echo date('Y'); ?> <span class="text-white fw-bold">AristoNut</span> (NK Enterprises). All
                    rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small" style="color: #A1887F !important;">
                    Powered by <strong class="text-white">NK Enterprises</strong>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Floating Call & WhatsApp Buttons -->
<?php if (!empty($db_phone)): ?>
    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $db_phone); ?>" class="floating-btn phone-btn" title="Call Us">
        <i class="bi bi-telephone-fill"></i>
    </a>
<?php endif; ?>

<?php if (!empty($wp_clean_link)): ?>
    <a href="https://wa.me/<?php echo $wp_clean_link; ?>?text=Hello%20AristoNut,%20I%20want%20to%20know%20more%20about%20your%20products."
        target="_blank" class="floating-btn whatsapp-btn" title="Chat on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
<?php endif; ?>
<a href="https://wa.me/<?php echo $db_phone; ?>" class="floating-btn whatsapp-btn" target="_blank"
    title="Chat on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<a href="tel:<?php echo $wp_clean_link; ?>" class="floating-btn phone-btn" title="Call Us">
    <i class="bi bi-telephone-fill"></i>
</a>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Header Scripts -->
<script>
    $(document).ready(function () {
        // 1. 📱 MOBILE HAMBURGER TOGGLER BUTTON ACTION
        $('#custom-mobile-toggler-btn').on('click', function (e) {
            e.stopPropagation();
            $('#customNavbarCollapseMenu').toggleClass('show-mobile-menu');
        });

        // 2. Search Overlay Input Controller Node
        $('#search-trigger-btn').on('click', function (e) {
            e.stopPropagation();
            $('#search-dropdown-box').fadeToggle(200);
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#search-dropdown-box, #search-trigger-btn').length) {
                $('#search-dropdown-box').fadeOut(150);
            }
        });

        // 3. BULLETPROOF DROPDOWN CLICK HANDLER (Mobile + Desktop Manual Override)
        $('.dropdown-toggle').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var targetDropdown = $(this).next('.dropdown-menu');

            // Kisi dusre open dropdown state ko close karna
            $('.dropdown-menu').not(targetDropdown).removeClass('show');

            // Toggle current dropdown
            targetDropdown.toggleClass('show');
        });

        // Outer screen parameters click reset actions
        $(document).on('click', function (e) {
            // Agar mobile menu ke bahar click ho to menu aur dropdown dono close karein
            if (!$(e.target).closest('#customNavbarCollapseMenu, #custom-mobile-toggler-btn').length) {
                $('#customNavbarCollapseMenu').removeClass('show-mobile-menu');
                $('.dropdown-menu').removeClass('show');
            }
        });

        // 4. Navigation Dynamic Page Active Highlighters Setup
        var currentUrl = window.location.pathname.split("/").pop();
        if (currentUrl == "") currentUrl = "index.php";

        $('.navbar-nav .nav-link').each(function () {
            var hrefVal = $(this).attr('href').split("/").pop();
            if (hrefVal == currentUrl && hrefVal !== "#" && hrefVal !== "") {
                $('.navbar-nav .nav-link').removeClass('active');
                $(this).addClass('active');
            }
        });
    });
</script>

<!-- Footer Scripts -->
<script>
    $(document).ready(function () {
        // 1. Smooth Scrolling Script Module Integration Matrix
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetElement = document.querySelector(this.getAttribute('href'));
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    });

    // 2. Global Dynamic Add to Cart AJAX Execution Script Action Controller Node
    function addToCart(productId) {
        const currentBtn = event.currentTarget;
        let originalBtnHtml = "";

        if (currentBtn && currentBtn.tagName === 'BUTTON') {
            originalBtnHtml = currentBtn.innerHTML;
            currentBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Processing...';
            currentBtn.disabled = true;
        }

        $.ajax({
            url: '<?php echo $site; ?>cart_action.php',
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: productId
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#header-cart-badge').text(response.cart_count);

                    if (currentBtn && currentBtn.tagName === 'BUTTON') {
                        currentBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i> Added!';
                        currentBtn.style.background = '#4CAF50';
                        currentBtn.style.borderColor = '#4CAF50';

                        setTimeout(() => {
                            currentBtn.innerHTML = originalBtnHtml;
                            currentBtn.style.background = '#8B4513';
                            currentBtn.style.borderColor = '#8B4513';
                            currentBtn.disabled = false;
                        }, 2000);
                    } else {
                        alert("Item successfully added to your premium basket!");
                    }
                } else {
                    alert("Operation Fail: " + response.message);
                    if (currentBtn && currentBtn.tagName === 'BUTTON') {
                        currentBtn.innerHTML = originalBtnHtml;
                        currentBtn.disabled = false;
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("Cart transaction failed validation diagnostics metrics logs: ", error);
                alert("Network connection drop. Item could not be initialized in session mapping layer.");
                if (currentBtn && currentBtn.tagName === 'BUTTON') {
                    currentBtn.innerHTML = originalBtnHtml;
                    currentBtn.disabled = false;
                }
            }
        });
    }

    // 3. Global Wishlist Toggle Controller Handler Trigger Node
    function handleWishlist(productId, element) {
        const icon = $(element).find('i');

        $.ajax({
            url: '<?php echo $site; ?>wishlist_action.php',
            type: 'POST',
            data: {
                action: 'toggle_wishlist',
                product_id: productId
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    if (response.action_status === 'added') {
                        icon.removeClass('bi-heart').addClass('bi-heart-fill text-danger');
                    } else {
                        icon.removeClass('bi-heart-fill text-danger').addClass('bi-heart');
                    }
                } else {
                    alert("Wishlist Action Failure response validation logs parameter metrics.");
                }
            },
            error: function () {
                alert("Network connection error. Wishlist matrix properties mapping aborted.");
            }
        });
    }
</script>

<!-- index page Scripts -->
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
                slidesPerView: 3, // Desktop par strict 2 slides hi aayengi
                spaceBetween: 25 // Do banners ke beech ka safe gap
            }
        }
    });
</script>