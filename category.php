<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Configuration data connection pipeline
include('config/connect.php'); 

// 1. .htaccess se redirected dynamic URL parameter catch karna
if (!isset($_GET['url']) || empty(trim($_GET['url']))) {
    header("Location: " . $site . "index.php");
    exit();
}

$category_slug = htmlspecialchars(trim($_GET['url']));

// 2. RELATIONAL FIX: categories table se 'cate_id' fetch karna slug ke zariye
$cat_stmt = $conn->prepare("SELECT `cate_id`, `categories`, `meta_title`, `meta_desc` FROM `categories` WHERE `slug_url` = ? AND `status` = 1 LIMIT 1");
$cat_stmt->bind_param("s", $category_slug);
$cat_stmt->execute();
$cat_res = $cat_stmt->get_result();

if (!$cat_res || $cat_res->num_rows == 0) {
    echo "<h2 style='text-align:center; margin-top:100px; color:#8B4513; font-family:sans-serif;'>Category Route Matrix Not Found.</h2>";
    exit();
}

$category_data = $cat_res->fetch_assoc();
// Yahan humne aapke database ke mutabik 'cate_id' ko primary relation target banaya hai
$current_cate_id = intval($category_data['cate_id']);
$current_category_name = htmlspecialchars($category_data['categories']);
$cat_stmt->close();

// 3. RELATIONAL FIX: products table ke 'pro_cate' column ko 'cate_id' se match karna
$prod_query = "SELECT * FROM `products` WHERE `pro_cate` = '$current_cate_id' AND `status` = 1 AND `is_disabled` = 0 ORDER BY `id` DESC";
$products_result = $conn->query($prod_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category_data['meta_title']); ?> - AristoNut</title>
    <meta name="description" content="<?php echo htmlspecialchars($category_data['meta_desc']); ?>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { background: #fffaf5; font-family: 'Poppins', sans-serif; color: #3E2723; }
        .category-banner { background: linear-gradient(135deg, #FFF8F0 0%, #FFE4C4 100%); border-bottom: 2px solid #F5E6D3; padding: 50px 0; border-radius: 0 0 30px 30px; }
        .product-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.4s; border: 2px solid #F5E6D3; margin-bottom: 30px; }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(139, 69, 19, 0.12); border-color: #8B4513; }
        .product-image-box { height: 250px; background: #FFF8F0; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 20px; }
        .product-image-box img { max-width: 90%; max-height: 90%; object-fit: contain; transition: transform 0.4s; }
        .product-card:hover .product-image-box img { transform: scale(1.08); }
        .product-link { text-decoration: none; color: inherit; display: block; }
        .btn-add-cart-custom { background: #ff0100; color: #fff; border: none; padding: 10px 25px; border-radius: 25px; font-weight: 500; width: 100%; transition: 0.3s; }
        .btn-add-cart-custom:hover { background: #6D3410; }
    </style>
</head>
<body>

    <?php include('inc/header.php'); ?>

    <section class="category-banner text-center mb-5">
        <div class="container">
            <span class="badge mb-2 px-3 py-2 rounded-pill text-uppercase" style="letter-spacing:1px; background:#ff0100 !important;">Collection</span>
            <h1 class="fw-bold display-5" style="color: #8B4513;"><?php echo $current_category_name; ?></h1>
            <p class="text-muted mx-auto" style="max-width: 600px;">Freshly picked, high-nutrition crisp premium flavored makhana directly from Mithila.</p>
        </div>
    </section>

    <main class="container mb-5">
        <div class="row">
            <?php
            if ($products_result && $products_result->num_rows > 0) {
                while ($product = $products_result->fetch_assoc()) {
                    $p_id = intval($product['id']);
                    $p_name = htmlspecialchars($product['pro_name']);
                    $p_price = htmlspecialchars($product['selling_price']);
                    $p_mrp = htmlspecialchars($product['mrp']);
                    $p_img = htmlspecialchars($product['pro_img']);
                    $p_qty = htmlspecialchars($product['qty']);
                    $p_slug = htmlspecialchars($product['slug_url']);
                    
                    // 🎯 SEO Friendly Link Structure Generation
                    $seo_detail_url = $site . "product/" . $p_slug;
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <a href="<?php echo $seo_detail_url; ?>" class="product-link">
                        <div class="product-image-box position-relative">
                            <img src="<?php echo $site; ?>admin/assets/img/uploads/<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>">
                        </div>
                    </a>
                    <div class="product-info p-3 text-center">
                        <a href="<?php echo $seo_detail_url; ?>" class="product-link">
                            <h5 class="product-name fw-bold mb-1 text-truncate" style="font-size:1.05rem; color:#3E2723;"><?php echo $p_name; ?></h5>
                        </a>
                        <p class="text-muted small mb-2">Net Vol: <?php echo !empty($p_qty) ? $p_qty : '100g'; ?></p>
                        <div class="product-price mb-3">
                            <span class="fw-bold fs-5" style="color: #ff0100;">₹<?php echo $p_price; ?></span>
                            <?php if(!empty($p_mrp) && $p_mrp > $p_price): ?>
                                <span class="text-decoration-line-through text-muted small ms-2">₹<?php echo $p_mrp; ?></span>
                            <?php endif; ?>
                        </div>
                        <button onclick="addToCart(<?php echo $p_id; ?>)" class="btn btn-add-cart-custom">
                            Add to Basket <i class="bi bi-cart-plus ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<div class='col-12 text-center py-5'><i class='bi bi-box-seizer text-muted display-1'></i><h4 class='text-muted mt-3'>Is category ke andar abhi koi product listed nahi hai.</h4></div>";
            }
            ?>
        </div>
    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>