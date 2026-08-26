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

    <?php include('inc/header.php'); ?>

    <style>
        /* Modern Dual Button Styles */
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
</head>

<body>

    <section class="category-banner text-center mb-5">
        <div class="container">
            <span class="badge mb-2 px-3 py-2 rounded-pill text-uppercase"
                style="letter-spacing:1px; background:#ff0100 !important;">Collection</span>
            <h1 class="fw-bold display-5" style="color: #8B4513;"><?php echo $current_category_name; ?></h1>
            <p class="text-muted mx-auto" style="max-width: 600px;">Freshly picked, high-nutrition crisp premium
                flavored makhana directly from Mithila.</p>
        </div>
    </section>

    <main class="container mb-5">
        <div class="row g-4">
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
                        <div class="product-card h-100 d-flex flex-column"
                            style="background: #fff; border-radius: 20px; border: 2px solid #f5e6d3; overflow: hidden; padding: 15px;">
                            <a href="<?php echo $seo_detail_url; ?>" class="product-link text-center mb-3">
                                <div class="product-image-box position-relative" style="height: 180px;">
                                    <img src="<?php echo $site; ?>admin/assets/img/uploads/<?php echo $p_img; ?>"
                                        alt="<?php echo $p_name; ?>" style="height:100%; object-fit:contain;">
                                </div>
                            </a>
                            <div class="product-info text-center d-flex flex-column flex-grow-1">
                                <a href="<?php echo $seo_detail_url; ?>" class="product-link text-decoration-none">
                                    <h5 class="product-name fw-bold mb-1 text-truncate"
                                        style="font-size:1.05rem; color:#3E2723;"><?php echo $p_name; ?></h5>
                                </a>
                                <p class="text-muted small mb-2">Net Wt: <?php echo !empty($p_qty) ? $p_qty : '100'; ?>g</p>
                                <div class="product-price mb-3">
                                    <span class="fw-bold fs-5" style="color: #ff0100;">₹<?php echo $p_price; ?></span>
                                    <?php if (!empty($p_mrp) && $p_mrp > $p_price): ?>
                                        <span
                                            class="text-decoration-line-through text-muted small ms-2">₹<?php echo $p_mrp; ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- NAYA CODE: TWO BUTTONS (CART & BUY NOW) -->
                                <div class="d-flex gap-2 w-100 mt-auto">
                                    <button class="modern-btn-add" onclick="addToCart(<?php echo $p_id; ?>)">
                                        Cart
                                    </button>
                                    <button class="modern-btn-buy" onclick="buyNow(<?php echo $p_id; ?>)">
                                        Buy Now
                                    </button>
                                </div>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- NEW: BUY NOW SCRIPT -->
    <script>
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
                        // Successfully added to direct buy session -> Redirect to checkout instantly
                        window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true';
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("System Error: Could not connect to the server.");
                }
            });
        }
    </script>
</body>

</html>