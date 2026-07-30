<?php
// Database se live metrics data fetch karna wildcard variables mapping parameters se
$footer_contact_query = "SELECT phone, email, address, wp_number FROM contacts LIMIT 1";
$footer_contact_res = $conn->query($footer_contact_query);

// Static Fallbacks agar database profile empty ho
$db_phone   = "+91 99997 28084";
$db_email   = "aristonut@gmail.com";
$db_address = "Subhankarpur, Darbhanga, Bihar-846004";
$db_wp      = "919999728084";

if ($footer_contact_res && $footer_contact_res->num_rows > 0) {
    $f_info = $footer_contact_res->fetch_assoc();
    if (!empty($f_info['phone']))     $db_phone   = htmlspecialchars($f_info['phone']);
    if (!empty($f_info['email']))     $db_email   = htmlspecialchars($f_info['email']);
    if (!empty($f_info['address']))   $db_address = htmlspecialchars($f_info['address']);
    if (!empty($f_info['wp_number'])) $db_wp      = preg_replace('/[^0-9]/', '', $f_info['wp_number']);
}

// WhatsApp call optimization dynamic configuration logic
$wp_clean_link = preg_replace('/[^0-9]/', '', $db_wp);
?>

<style>
    .footer {
        background: #e5afaf52 !important; /* Rich Dark Brown Chocolate Base */
        color: #D7CCC8;
        padding: 70px 0 25px;
        font-family: 'Poppins', sans-serif;
    }
    .footer h5 {
        color: #0a0a0a;
        font-weight: 700;
        font-size: 1.15rem;
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
        width: 40px;
        height: 2px;
        background: #ff0100; /* Gold Accent Underline Line */
    }
    .footer a {
           color: #000000 !important;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        margin-bottom: 12px;
        font-size: 0.92rem;
        width: 100%;
    }
    .footer a:hover {
        color: #000000 !important;
        transform: translateX(5px);
    }
    .footer .brand {
        font-size: 2rem;
        font-weight: 800;
        color: #ff0100;
        margin-bottom: 5px;
        letter-spacing: 0.5px;
    }
    .footer .subtitle {
        color: #040404;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 18px;
    }
    .footer p {
        line-height: 1.7;
        font-size: 0.92rem;
        color: #080808;
    }
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
        color: #BCAAA4;
    }
    .contact-item i {
        color: #ff0100;
        font-size: 1.1rem;
        margin-top: 2px;
    }
    .footer-social-icons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }
    .footer-social-icons a {
        width: 38px;
        height: 38px;
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
        background: #FFD700;
        color: #2D1B18 !important;
        transform: translateY(-3px);
    }
    .footer hr {
        border-color: rgba(255, 255, 255, 0.08) !important;
        margin: 40px 0 25px;
    }
    /* Base Style dono buttons ke liye */
.floating-btn {
    position: fixed;
    right: 30px;
    color: #fff;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.9rem;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: 9999;
    text-decoration: none;
}

/* 1. WhatsApp Button ki alag position aur color */
.whatsapp-btn {
    bottom: 30px; /* Ye sabse neeche rahega */
    background: #25D366;
    box-shadow: 0 8px 25px rgba(37, 211, 102, 0.35);
}
.whatsapp-btn:hover {
    transform: scale(1.1) rotate(10deg);
    color: #fff;
    box-shadow: 0 12px 30px rgba(37, 211, 102, 0.5);
}

/* 2. Call Button ki alag position (WhatsApp ke upar) aur color */
.phone-btn {
    bottom: 100px; /* Isko humne upar kar diya (30px + 70px spacing) */
    background: #007bff; /* Blue color call ke liye (Aap chahein to change kar sakte hain) */
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.35);
}
.phone-btn:hover {
    transform: scale(1.1) rotate(-10deg); /* Isko opposite rotate diya hai cool look ke liye */
    color: #fff;
    box-shadow: 0 12px 30px rgba(0, 123, 255, 0.5);
}
</style>

<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 mb-2">
    <?php
    // Default Fallback Text/Image Block setup agar query empty ho
    $footer_logo_img = ""; 

    // Logos table se footer location ka active logo path fetch karna
    $footer_logo_query = "SELECT `logo_path` FROM `logos` WHERE `location` = 'footer' AND `is_active` = 1 LIMIT 1";
    $footer_logo_res = $conn->query($footer_logo_query);

    if ($footer_logo_res && $footer_logo_res->num_rows > 0) {
        $footer_logo_row = $footer_logo_res->fetch_assoc();
        if (!empty($footer_logo_row['logo_path'])) {
            $footer_logo_img = $site . "admin/uploads/" . htmlspecialchars($footer_logo_row['logo_path']);
        }
    }
    ?>

    <?php if (!empty($footer_logo_img)): ?>
        <div class="footer-logo-box mb-3">
            <a href="<?php echo $site; ?>index.php">
                <img src="<?php echo $footer_logo_img; ?>" alt="AristoNut Logo" class="brand-logo-img" style="height: 52px; width: auto; object-fit: contain;">
            </a>
        </div>
    <?php else: ?>
        <div class="brand">AristoNut</div>
    <?php endif; ?>
    
    <div class="subtitle">Premium Quality</div>
    <p class="pe-lg-4">
        India's finest premium makhana, crafted with tradition and quality. 
        Experience the perfect blend of health, crispness, and delicious taste.
    </p>
    <?php
// Contacts table se live social links columns fetch karna
$social_query = "SELECT `facebook`, `instagram`, `twitter`, `linkdin` FROM `contacts` LIMIT 1";
$social_res = $conn->query($social_query);

// Default empty strings initialization
$fb_link = $insta_link = $twitter_link = $linkedin_link = "";

if ($social_res && $social_res->num_rows > 0) {
    $social_row = $social_res->fetch_assoc();
    $fb_link       = trim($social_row['facebook']);
    $insta_link    = trim($social_row['instagram']);
    $twitter_link  = trim($social_row['twitter']);
    $linkedin_link = trim($social_row['linkdin']); /* Table schema mein linkdin spelling hai */
}
?>

<div class="footer-social-icons">
    <?php if (!empty($fb_link)): ?>
        <a href="<?php echo htmlspecialchars($fb_link); ?>" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
    <?php endif; ?>
    
    <?php if (!empty($insta_link)): ?>
        <a href="<?php echo htmlspecialchars($insta_link); ?>" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
    <?php endif; ?>

    <?php if (!empty($twitter_link)): ?>
        <a href="<?php echo htmlspecialchars($twitter_link); ?>" target="_blank" title="Twitter/X"><i class="bi bi-twitter"></i></a>
    <?php endif; ?>
    
    <?php if (!empty($linkedin_link)): ?>
        <a href="<?php echo htmlspecialchars($linkedin_link); ?>" target="_blank" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
    <?php endif; ?>
</div>
</div>
            
            <div class="col-lg-2 col-md-6 col-6 mb-2">
                <h5>Quick Links</h5>
                <a href="<?php echo $site; ?>index.php">Home</a>
                <a href="<?php echo $site; ?>about.php">About Us</a>
                <a href="<?php echo $site; ?>product.php">Products</a>
                <a href="<?php echo $site; ?>contact.php">Contact</a>
                <a href="<?php echo $site; ?>privacy-policy.php">Privacy Policy</a>
                <a href="<?php echo $site; ?>terms-conditions.php">Terms & Conditions</a>
            </div>
            
            <div class="col-lg-3 col-md-6 col-6 mb-2">
                <h5>Our Products</h5>
                <?php
                $footer_prod_query = "SELECT pro_name, slug_url FROM products WHERE status = 1 ORDER BY id DESC LIMIT 6";
                $footer_prod_res = $conn->query($footer_prod_query);

                if ($footer_prod_res && $footer_prod_res->num_rows > 0) {
                    while ($f_prod = $footer_prod_res->fetch_assoc()) { 
                        $f_prod_name = htmlspecialchars($f_prod['pro_name']);
                        $f_prod_slug = htmlspecialchars($f_prod['slug_url']);
                        
                        echo '<a href="' . $site . 'product/' . $f_prod_slug . '">' . $f_prod_name . '</a>';
                    }
                } else {
                    echo '<a href="' . $site . 'product.php">Premium Makhana</a>';
                    echo '<a href="' . $site . 'product.php">Flavored Makhana</a>';
                    echo '<a href="' . $site . 'product.php">Organic Makhana</a>';
                }
                ?>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-2">
                <h5>Get In Touch</h5>
                <div class="contact-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <p class="mb-0"><?php echo $db_address; ?></p>
                </div>
                <div class="contact-item">
                    <i class="bi bi-telephone-fill"></i>
                    <p class="mb-0">
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $db_phone); ?>" style="display:inline; color:inherit; padding:0; margin:0;">
                            <?php echo $db_phone; ?>
                        </a>
                    </p>
                </div>
                <div class="contact-item">
                    <i class="bi bi-envelope-fill"></i>
                    <p class="mb-0">
                        <a href="mailto:<?php echo $db_email; ?>" style="display:inline; color:inherit; padding:0; margin:0;">
                            <?php echo $db_email; ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
        <hr>
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 small text-muted">© <?php echo date('Y'); ?> <span class="text-black fw-medium">AristoNut</span>. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="developer-credit mb-0 small">
                    Made with <i class="bi bi-heart-fill text-danger mx-1"></i> 
                </p>
            </div>
        </div>
    </div>
</footer>

<a href="https://wa.me/<?php echo $db_phone; ?>" class="floating-btn whatsapp-btn" target="_blank" title="Chat on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<a href="tel:<?php echo $wp_clean_link; ?>" class="floating-btn phone-btn" title="Call Us">
    <i class="bi bi-telephone-fill"></i>
</a>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Smooth Scrolling Script Module Integration Matrix
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetElement = document.querySelector(this.getAttribute('href'));
            if(targetElement) {
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
        success: function(response) {
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
        error: function(xhr, status, error) {
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
        data: { action: 'toggle_wishlist', product_id: productId },
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                if(response.action_status === 'added') {
                    icon.removeClass('bi-heart').addClass('bi-heart-fill text-danger');
                } else {
                    icon.removeClass('bi-heart-fill text-danger').addClass('bi-heart');
                }
            } else {
                alert("Wishlist Action Failure response validation logs parameter metrics.");
            }
        },
        error: function() {
            alert("Network connection error. Wishlist matrix properties mapping aborted.");
        }
    });
}
</script>