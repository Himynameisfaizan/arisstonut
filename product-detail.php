<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php'); // Database & Global $site Config Layer

// 1. URL Parameter Validation & Sanitization (Slug Tracking)
if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $conn->real_escape_string($_GET['slug']);
    
    // Database se product details fetch karna matching slug query standard se
    $query = $conn->query("SELECT * FROM products WHERE slug_url = '$slug' AND status = 1 LIMIT 1");
    
    if ($query && $query->num_rows > 0) {
        $product = $query->fetch_assoc();
        
        // Data nodes dynamic separation mapping matrix variables
        $p_id = $product['id'];
        $p_name = htmlspecialchars($product['pro_name']);
        $p_mrp = $product['mrp'];
        $p_price = $product['selling_price'];
        $p_weight = htmlspecialchars($product['qty']); // Qty field used for weight context
        
        // Global $site base routing matrix parameters for absolute image assets path mapping
        $p_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($product['pro_img']);
        
        $p_short_desc = $product['short_desc']; // Contains paragraph HTML text block
        $p_long_desc = $product['description']; // Contains long descriptive data node block
        
        // SEO Meta Optimization dynamically map indicators
        $seo_title = htmlspecialchars($product['meta_title']);
        $seo_desc = htmlspecialchars($product['meta_desc']);
        $seo_keywords = htmlspecialchars($product['meta_key']);
    } else {
        header("Location: " . $site . "index.php");
        exit();
    }
} else {
    header("Location: " . $site . "index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $seo_title; ?> - AristoNut</title>
    <meta name="description" content="<?php echo $seo_desc; ?>">
    <meta name="keywords" content="<?php echo $seo_keywords; ?>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #fffaf5; color: #3E2723; overflow-x: hidden; }
        .text-brown { color: #8B4513; }
        
        /* Product Container Visual Canvas Structure Styles */
        .detail-wrapper-card { background: #ffffff; border-radius: 24px; border: 2px solid #F5E6D3; overflow: hidden; }
        
        /* 🔍 DYNAMIC ZOOM CANVAS CORE ARCHITECTURE PRESETS */
        .detail-image-box { 
            background: #FFF8F0; 
            border-radius: 16px; 
            border: 1px solid #F5E6D3; 
            padding: 20px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 400px; 
            position: relative;
            overflow: hidden; /* Restricts the magnified profile container scale boundaries */
            cursor: zoom-in;
        }
        
        /* Core image target configurations */
        .detail-image-box img { 
            max-width: 100%; 
            max-height: 380px; 
            object-fit: contain; 
            transform-origin: center center;
            transition: transform 0.1s ease-out; /* Keeps mouse panning smooth */
            pointer-events: none; /* Bypasses context menu extraction blocks during execution */
        }
        
        /* Action buttons components logic architecture presets */
        .btn-add-detail { background: #8B4513; color: #fff; border: 2px solid #8B4513; border-radius: 30px; padding: 14px 30px; font-weight: 600; font-size: 1.1rem; width: 100%; transition: all 0.3s ease; cursor: pointer; }
        .btn-add-detail:hover { background: #6D3410; border-color: #6D3410; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(139, 69, 19, 0.25); color: #fff; }
        
        .price-badge-strip { font-size: 2.2rem; font-weight: 800; color: #8B4513; }
        .mrp-strike { font-size: 1.2rem; color: #8D6E63; text-decoration: line-through; }
        .weight-badge { background: #FFF3E0; color: #8B4513; font-weight: 600; padding: 6px 16px; border-radius: 20px; font-size: 0.9rem; border: 1px solid #FFE4C4; }
        
        /* Tabs control structure navigation configuration arrays */
        .nav-tabs { border-bottom: 2px solid #F5E6D3; }
        .nav-tabs .nav-link { color: #8D6E63; font-weight: 500; border: none; padding: 12px 24px; background: transparent; }
        .nav-tabs .nav-link.active { color: #8B4513; border-bottom: 3px solid #8B4513; font-weight: 700; background: transparent; }
        
        @media (max-width: 768px) {
            .price-badge-strip { font-size: 1.8rem; }
            .detail-image-box { min-height: 300px; cursor: default; }
        }
    </style>
</head>
<body>

    <?php include('inc/header.php'); ?>

    <main class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $site; ?>index.php" class="text-brown text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $site; ?>product.php" class="text-brown text-decoration-none">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $p_name; ?></li>
            </ol>
        </nav>

        <div class="card detail-wrapper-card p-4 shadow-sm mb-5">
            <div class="row g-5">
                
                <div class="col-md-6">
                    <div class="detail-image-box" id="magnify-container-node">
                        <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>" id="magnify-target-img">
                    </div>
                </div>

                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <span class="badge align-self-start bg-success mb-2 px-3 py-2 rounded-pill"><i class="bi bi-shield-check me-1"></i> In Stock</span>
                    <h1 class="fw-bold text-brown mb-2"><?php echo $p_name; ?></h1>
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="weight-badge"><i class="bi bi-box-seam me-2"></i>Net Weight: <?php echo $p_weight; ?>g</span>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="price-badge-strip">₹<?php echo $p_price; ?></span>
                            <?php if(!empty($p_mrp) && $p_mrp > $p_price): ?>
                                <span class="mrp-strike">MRP ₹<?php echo $p_mrp; ?></span>
                            <?php endif; ?>
                        </div>
                        <small class="text-success fw-bold"><i class="bi bi-tags-fill me-1"></i> Inclusive of all regional taxes</small>
                    </div>

                    <div class="product-short-summary text-muted mb-4 fs-6">
                        <?php echo $p_short_desc; ?>
                    </div>

                    <div class="row mt-auto">
                        <div class="col-xl-8">
                            <button type="button" class="btn btn-add-detail" onclick="addToCart(<?php echo $p_id; ?>)">
                                <i class="bi bi-bag-plus-fill me-2"></i> Add To Premium Basket
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card detail-wrapper-card p-4 shadow-sm">
            <ul class="nav nav-tabs mb-4" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">Detailed Overview</button>
                </li>
            </ul>
            <div class="tab-content text-muted p-2" id="productTabContent">
                <div class="tab-pane fade show active" id="desc-pane" role="tabpanel" aria-labelledby="desc-tab">
                    <div class="lh-lg fs-6"><?php echo $p_long_desc; ?></div>
                </div>
            </div>
        </div>
    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const containerNode = document.getElementById("magnify-container-node");
        const targetImg = document.getElementById("magnify-target-img");

        // Execute hover panning only on screens greater than mobile thresholds
        if (window.innerWidth > 768) {
            containerNode.addEventListener("mousemove", function(e) {
                const rect = containerNode.getBoundingClientRect();
                
                // Get mouse coordinates relative to the image container box bounds
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                // Convert coordinates to exact target matrix percentages
                const xPercent = (x / rect.width) * 100;
                const yPercent = (y / rect.height) * 100;
                
                // Lock the zoom-origin tracking node dynamically matching the cursor context
                targetImg.style.transformOrigin = `${xPercent}% ${yPercent}%`;
                targetImg.style.transform = "scale(2.2)"; // Adjust magnification power profile multiplier here
            });
            
            // Revert image back to default scaling layout bounds on mouse leave parameters
            containerNode.addEventListener("mouseleave", function() {
                targetImg.style.transform = "scale(1)";
                targetImg.style.transformOrigin = "center center";
            });
        }
    });
    </script>
</body>
</html>