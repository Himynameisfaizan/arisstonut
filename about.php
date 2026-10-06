<?php 
include ('config/connect.php');
$pageTitle = "About AristoNut";
include('inc/header.php');
include ('inc/breadcrumb.php');

// Database se About Us data fetch karne ki query
$about_query = "SELECT * FROM about_us LIMIT 1";
$about_result = $conn->query($about_query);
$about = ($about_result && $about_result->num_rows > 0) ?$about_result->fetch_assoc() : [];

// Fallback values agar database mein data na ho
$tagline = htmlspecialchars($about['tagline'] ?? 'About AristoNut');
$heading_1 = htmlspecialchars($about['heading_primary'] ?? 'From the Heart of Mithila');
$heading_2 = htmlspecialchars($about['heading_secondary'] ?? 'to the World');
// Description mein HTML tags allow karne ke liye htmlspecialchars nahi lagaya hai
$desc_1 =$about['description_1'] ?? '<strong>AristoNut</strong> is a premium makhana brand owned and operated by <strong>NK Enterprises</strong>, an India-based business rooted in the primary makhana-producing region of Bihar.';
$desc_2 =$about['description_2'] ?? 'Makhana (fox nuts / gorgon nuts) has been a traditional part of Bihar\'s agricultural and food heritage for generations. At AristoNut, we are bringing this traditional Indian superfood to modern global markets through thoughtful sourcing, quality-focused processing, and a professional approach to both B2B and consumer business.';

$legal_name = htmlspecialchars($about['legal_name'] ?? 'NK Enterprises');
$origin = htmlspecialchars($about['origin'] ?? 'Bihar, India • Fox Nuts');

// Image path dynamic handling
$about_img = !empty($about['image']) ?$site . 'admin/assets/img/uploads/' . htmlspecialchars($about['image']) :$site . 'assets/images/hero.webp';
?>

<!-- Custom CSS to Prevent Design Breaking on Long Content -->
<style>
    .about-img-box img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        border-radius: 12px;
    }
    .leading-relaxed {
        line-height: 1.8;
    }
    .stat-card {
        background: #F9F6F0;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid rgba(156, 85, 33, 0.1);
        height: 100%;
    }
</style>

<!-- About Brand Story Section -->
<section class="container py-5">
  <div class="row align-items-center g-5">
    <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
      <span class="section-tag"><?php echo $tagline; ?></span>
      <h2 class="fw-bold mb-3 display-6">
        <?php echo $heading_1; ?> <br><span class="text-danger"><?php echo $heading_2; ?></span>
      </h2>
      <div class="text-muted leading-relaxed mb-3">
        <?php echo $desc_1; ?>
      </div>
      <div class="text-muted leading-relaxed">
        <?php echo $desc_2; ?>
      </div>

      <div class="row g-3 mt-3">
        <div class="col-sm-6">
          <div class="stat-card">
            <h6 class="fw-bold mb-1" style="color: #2C1E16;">Legal Business Name</h6>
            <p class="text-muted mb-0 small"><?php echo $legal_name; ?></p>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="stat-card">
            <h6 class="fw-bold mb-1" style="color: #2C1E16;">Origin & Category</h6>
            <p class="text-muted mb-0 small"><?php echo $origin; ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5 offset-lg-1" data-aos="fade-left" data-aos-duration="1000">
      <div class="card p-4 shadow-lg border-0 rounded-4 position-relative overflow-hidden">
        <div class="position-absolute top-0 end-0 bg-danger text-white px-3 py-1 rounded-bottom-start fw-bold small">
          100% Authentic
        </div>
        <div class="text-center my-3">
          <h3 class="text-danger fw-bold mb-0">AristoNut</h3>
          <p class="text-muted small">A Brand by NK Enterprises</p>
        </div>
        
        <!-- Dynamic Image with fixed styling to protect layout -->
        <div class="about-img-box mb-3 shadow-sm">
            <img src="<?php echo $about_img; ?>" alt="AristoNut Makhana Packaging" onerror="this.src='<?php echo $site; ?>assets/images/hero.webp';">
        </div>

        <div class="text-center">
          <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">🌱 Farm to Bowl Quality Control</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Vision & Mission -->
<section class="py-5" style="background-color: var(--soft-bg);">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="vision-mission-card">
          <div class="icon-box"><i class="bi bi-eye"></i></div>
          <h4 class="fw-bold mb-3">Our Vision</h4>
          <p class="text-muted mb-0">
            To build a globally recognised makhana brand representing the authentic heritage of Mithila and the unmatched quality of Indian food products.
          </p>
        </div>
      </div>
      <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="vision-mission-card">
          <div class="icon-box"><i class="bi bi-bullseye"></i></div>
          <h4 class="fw-bold mb-3">Our Mission</h4>
          <p class="text-muted mb-0">
            To deliver quality-focused makhana products through responsible sourcing, professional operations, attractive packaging, and dependable long-term partnerships with customers and global buyers.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Our Capabilities / Offerings -->
<section class="capabilities-section">
    <div class="container">
        <div class="cap-header cap-reveal">
            <span class="cap-tag">Comprehensive Solutions</span>
            <h2 class="cap-title">Our Focus & Capabilities</h2>
            <p class="cap-desc">Meeting the strict requirements of modern retail consumers and high-volume commercial buyers worldwide.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6 col-12 cap-reveal cap-delay-1">
                <div class="cap-card">
                    <div class="cap-icon-box"><i class="bi bi-bag-check"></i></div>
                    <h5 class="cap-card-title">Retail-Ready Makhana</h5>
                    <p class="cap-card-desc">Consumer-ready retail packs in multiple weights with shelf-appealing, airtight modern packaging.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 cap-reveal cap-delay-2">
                <div class="cap-card">
                    <div class="cap-icon-box"><i class="bi bi-boxes"></i></div>
                    <h5 class="cap-card-title">Bulk & Wholesale</h5>
                    <p class="cap-card-desc">High-capacity bulk shipments with consistent grading, standard sizing, and strict moisture control.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 cap-reveal cap-delay-3">
                <div class="cap-card">
                    <div class="cap-icon-box"><i class="bi bi-stars"></i></div>
                    <h5 class="cap-card-title">Flavoured Innovations</h5>
                    <p class="cap-card-desc">Roasted, non-fried flavored makhana recipes expertly designed to cater to modern snacking trends.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 cap-reveal cap-delay-1">
                <div class="cap-card">
                    <div class="cap-icon-box"><i class="bi bi-tag"></i></div>
                    <h5 class="cap-card-title">Private-Label</h5>
                    <p class="cap-card-desc">Custom white-label and private-label processing and packaging support for domestic and global brands.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 cap-reveal cap-delay-2">
                <div class="cap-card">
                    <div class="cap-icon-box"><i class="bi bi-award"></i></div>
                    <h5 class="cap-card-title">Quality Standardisation</h5>
                    <p class="cap-card-desc">Carefully handpicked grading focusing intensely on appearance, crunch, size uniformity, and freshness.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 cap-reveal cap-delay-3">
                <div class="cap-card">
                    <div class="cap-icon-box"><i class="bi bi-globe2"></i></div>
                    <h5 class="cap-card-title">Export & Logistics</h5>
                    <p class="cap-card-desc">End-to-end support for international documentation, phytosanitary standards, and safe export logistics.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= AVAILABLE ON PLATFORMS SECTION ================= -->
<section class="available-platforms-sec">
    <div class="container">
        <div class="platform-header">
            <span class="platform-subtitle">Nationwide Availability</span>
            <h2 class="platform-title">Shop Your Way</h2>
            <p class="platform-desc">We are committed to delivering health everywhere. Find AristoNut's premium makhana range on India's most trusted marketplaces.</p>
        </div>
    </div>

    <div class="marquee-wrapper">
        <div class="marquee-track">
            <?php
            $brands_query = "SELECT * FROM `brands` ORDER BY `id` DESC";
            $brands_result =$conn->query($brands_query);$brands_html = ""; 

            if ($brands_result &&$brands_result->num_rows > 0) {
                while ($brand = $brands_result->fetch_assoc()) {$brand_img = !empty($brand['logo_path']) ?$site . 'admin/' . htmlspecialchars($brand['logo_path']) :$site . 'assets/images/default-brand.png';
                    $brand_name = htmlspecialchars($brand['title'] ?? 'Partner Brand');

                    $brands_html .= '
                    <div class="brand-logo-box">
                        <img src="' . $brand_img . '" alt="' . $brand_name . '" title="' . $brand_name . '">
                    </div>';
                }
            } else {
                $brands_html .= '
                    <div class="brand-logo-box"><h4 class="text-muted fw-bold">Amazon</h4></div>
                    <div class="brand-logo-box"><h4 class="text-muted fw-bold">Flipkart</h4></div>
                    <div class="brand-logo-box"><h4 class="text-muted fw-bold">JioMart</h4></div>
                    <div class="brand-logo-box"><h4 class="text-muted fw-bold">Blinkit</h4></div>
                    <div class="brand-logo-box"><h4 class="text-muted fw-bold">Zepto</h4></div>';
            }

            echo $brands_html;
            echo $brands_html;
            ?>
        </div>
    </div>
</section>

<!-- International B2B Partners Section -->
<section class="py-5" style="background-color: #fafafa;">
  <div class="container text-center">
    <span class="section-tag" data-aos="fade-up">Global Trade</span>
    <h2 class="fw-bold mb-3" data-aos="fade-up" data-aos-delay="100">Serving International Buyers & Distributors</h2>
    <p class="text-muted mx-auto mb-4" style="max-width: 650px;" data-aos="fade-up" data-aos-delay="200">
      We build long-term relationships through dependable supply, clear communication, consistent product parameters, and seamless fulfillment.
    </p>

    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4" data-aos="fade-up" data-aos-delay="300">
      <span class="badge-tag"><i class="bi bi-check-circle-fill text-danger"></i> Importers</span>
      <span class="badge-tag"><i class="bi bi-check-circle-fill text-danger"></i> Distributors</span>
      <span class="badge-tag"><i class="bi bi-check-circle-fill text-danger"></i> Wholesalers</span>
      <span class="badge-tag"><i class="bi bi-check-circle-fill text-danger"></i> Supermarkets</span>
      <span class="badge-tag"><i class="bi bi-check-circle-fill text-danger"></i> Food Brands</span>
      <span class="badge-tag"><i class="bi bi-check-circle-fill text-danger"></i> Private Labels</span>
    </div>
  </div>
</section>

<!-- CTA & Inquiry Section -->
<section class="container py-5">
  <div class="cta-section" data-aos="zoom-in" data-aos-duration="800">
    <div class="row align-items-center">
      <div class="col-lg-8 mb-4 mb-lg-0">
        <h2 class="fw-bold text-white mb-2">Let's Build a Partnership</h2>
        <p class="text-light opacity-75 mb-0">
          Whether you need wholesale supply, retail distribution, private labeling, or international export quotes — our team is here to assist.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="contact.php" class="btn btn-danger btn-lg px-4 py-2 rounded-pill shadow">
          Contact NK Enterprises <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    once: true,
    easing: 'ease-in-out'
  });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const capOptions = { root: null, rootMargin: '0px', threshold: 0.15 };

        const capObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target); 
                }
            });
        }, capOptions);

        const capElements = document.querySelectorAll('.cap-reveal');
        capElements.forEach(el => capObserver.observe(el));
    });
</script>

<?php include('inc/footer.php'); ?>
</body>
</html>