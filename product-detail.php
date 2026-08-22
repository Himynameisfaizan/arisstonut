<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php'); // Database & Global $site Config Layer

// URL Parameter Validation
if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $conn->real_escape_string($_GET['slug']);

    $query = $conn->query("SELECT * FROM products WHERE slug_url = '$slug' AND status = 1 LIMIT 1");

    if ($query && $query->num_rows > 0) {
        $product = $query->fetch_assoc();

        $p_id = $product['id'];
        $p_name = htmlspecialchars($product['pro_name']);
        $p_mrp = $product['mrp'];
        $p_price = $product['selling_price'];
        $p_cate = $product['pro_cate']; // Category ID for Related Products

        $p_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($product['pro_img']);
        $p_short_desc = $product['short_desc'];
        $p_long_desc = $product['description'];

        $seo_title = htmlspecialchars($product['meta_title']);
        $seo_desc = htmlspecialchars($product['meta_desc']);
        $seo_keywords = htmlspecialchars($product['meta_key']);

        // --- Fetch Variations ---
        $variations = [];
        $var_query = $conn->query("SELECT * FROM product_variations WHERE product_id = '$p_id' ORDER BY id ASC");
        if ($var_query && $var_query->num_rows > 0) {
            while ($row = $var_query->fetch_assoc()) {
                $variations[] = $row;
            }
        }
        $variations_json = json_encode($variations);

        // --- Fetch Related Products (Same Category, Excluding Current Product) ---
        $related_query = $conn->query("SELECT id, pro_name, selling_price, pro_img, slug_url FROM products WHERE pro_cate = '$p_cate' AND id != '$p_id' AND status = 1 ORDER BY id DESC LIMIT 4");

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

    <style>
        .variation-radio {
            display: none !important;
        }

        .variation-label {
            display: inline-block;
            cursor: pointer;
            border: 2px solid #E0E0E0;
            border-radius: 8px;
            color: #5D4037;
            background: #ffffff;
            font-weight: 600;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
            margin-bottom: 5px;
        }

        .variation-label:hover {
            border-color: #8B4513;
            background: #FFF8F0;
        }

        .variation-radio:checked+.variation-label {
            border-color: #8B4513;
            background-color: #8B4513;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(139, 69, 19, 0.3);
        }

        .qty-btn {
            border: 2px solid #F5E6D3;
            background: #FFF8F0;
            color: #8B4513;
            font-weight: bold;
            font-size: 1.2rem;
            width: 45px;
        }

        .qty-btn:hover {
            background: #8B4513;
            color: white;
            border-color: #8B4513;
        }

        .qty-input {
            border-top: 2px solid #F5E6D3 !important;
            border-bottom: 2px solid #F5E6D3 !important;
            border-left: none !important;
            border-right: none !important;
            background: #ffffff !important;
            font-size: 1.2rem;
        }

        .btn-add-detail {
            background-color: #8B4513;
            color: white;
            padding: 14px 24px;
            font-size: 1.1rem;
            border-radius: 8px;
            width: 100%;
            border: none;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-add-detail:hover {
            background-color: #6D3410;
            color: white;
            transform: translateY(-2px);
        }

        /* Thumbnail Selection CSS */
        .var-thumb {
            width: 70px;
            height: 70px;
            object-fit: contain;
            cursor: pointer;
            border: 2px solid #eee;
            border-radius: 8px;
            transition: 0.3s;
            background: #fff;
        }

        .var-thumb.active-thumb {
            border-color: #8B4513;
            box-shadow: 0 4px 8px rgba(139, 69, 19, 0.2);
        }

        /* Related Products CSS */
        .related-card {
            border: 1px solid #F5E6D3;
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            background: #fff;
        }

        .related-card:hover {
            box-shadow: 0 10px 20px rgba(139, 69, 19, 0.1);
            transform: translateY(-5px);
            border-color: #8B4513;
        }

        .related-img-box {
            height: 220px;
            background: #FFF8F0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .related-img-box img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.4s;
        }

        .related-card:hover .related-img-box img {
            transform: scale(1.08);
        }
    </style>
</head>

<body>
    <?php include('inc/header.php'); ?>

    <!-- Added pb-5 to ensure padding before footer -->
    <main class="container py-5 mb-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $site; ?>index.php"
                        class="text-brown text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $site; ?>product.php"
                        class="text-brown text-decoration-none">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $p_name; ?></li>
            </ol>
        </nav>

        <!-- Main Product Card -->
        <div class="card detail-wrapper-card p-4 shadow-sm mb-5 border-0" style="border-radius: 16px;">
            <div class="row g-5">

                <!-- Image Section with Thumbnails -->
                <div class="col-md-6">
                    <div class="detail-image-box rounded p-3 text-center" id="magnify-container-node"
                        style="background: #fbfbfb; border: 1px solid #eee;">
                        <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>" id="magnify-target-img"
                            style="max-height: 400px; width: 100%; object-fit: contain;">
                    </div>

                    <!-- DYNAMIC THUMBNAILS -->
                    <?php if (!empty($variations)): ?>
                        <div class="d-flex gap-2 mt-3 overflow-auto pb-2" id="variation-thumbnails">
                            <?php foreach ($variations as $index => $var): ?>
                                <?php $thumb_img = !empty($var['image_path']) ? $site . 'admin/assets/img/uploads/' . $var['image_path'] : $p_img; ?>
                                <img src="<?php echo $thumb_img; ?>"
                                    class="var-thumb <?php echo $index === 0 ? 'active-thumb' : ''; ?>"
                                    data-index="<?php echo $index; ?>" alt="<?php echo $var['weight_size']; ?>"
                                    title="<?php echo $var['weight_size']; ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Details Section -->
                <div class="col-md-6 d-flex flex-column">
                    <span class="badge align-self-start bg-success mb-2 px-3 py-2 rounded-pill" id="stock-badge"><i
                            class="bi bi-shield-check me-1"></i> In Stock</span>
                    <h1 class="fw-bold text-brown mb-2"><?php echo $p_name; ?></h1>

                    <!-- Price Display -->
                    <div class="mb-3 mt-2 border-bottom pb-3">
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="price-badge-strip text-dark fs-2 fw-bold"
                                id="display-price">₹<?php echo $p_price; ?></span>
                            <span class="text-muted fw-bold fs-5" id="display-total-price"></span>
                        </div>
                        <small class="text-success fw-bold"><i class="bi bi-tags-fill me-1"></i> Inclusive of all
                            regional taxes</small>
                    </div>

                    <!-- Variations Selector -->
                    <?php if (!empty($variations)): ?>
                        <div class="variation-selector mb-3">
                            <h6 class="text-muted fw-bold mb-2">Select Pack Size:</h6>
                            <div class="d-flex flex-wrap gap-2" id="weight-options">
                                <?php foreach ($variations as $index => $var): ?>
                                    <input type="radio" class="variation-radio" name="pack_size"
                                        id="var_<?php echo $var['id']; ?>" value="<?php echo $var['id']; ?>" <?php echo $index === 0 ? 'checked' : ''; ?> data-index="<?php echo $index; ?>">
                                    <label class="variation-label" for="var_<?php echo $var['id']; ?>">
                                        <?php echo htmlspecialchars($var['weight_size']); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Quantity & Add to Cart -->
                    <div class="bg-white p-3 rounded-3 mb-3 border shadow-sm">
                        <h6 class="text-muted fw-bold mb-3">Quantity:</h6>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="input-group" style="width: 140px;">
                                <button class="btn qty-btn" type="button" id="btn-qty-minus"><i
                                        class="bi bi-dash"></i></button>
                                <input type="text" class="form-control text-center text-dark fw-bold qty-input"
                                    id="product-qty" value="1" readonly>
                                <button class="btn qty-btn" type="button" id="btn-qty-plus"><i
                                        class="bi bi-plus"></i></button>
                            </div>
                            <div class="flex-grow-1 d-flex gap-2">
                                <!-- Basket Button (Outline) -->
                                <button type="button" class="btn flex-fill fw-bold" id="custom-add-to-cart-btn"
                                    style="background-color: #FFF8F0; color: #8B4513; border: 2px solid #8B4513; border-radius: 8px;">
                                    <i class="bi bi-cart-plus me-1"></i> CART
                                </button>

                                <!-- Buy Now Button (Solid) -->
                                <button type="button" class="btn flex-fill fw-bold" id="custom-buy-now-btn"
                                    style="background-color: #8B4513; color: white; border: 2px solid #8B4513; border-radius: 8px; transition: 0.3s;">
                                    <i class="bi bi-lightning-charge-fill me-1"></i> BUY NOW
                                </button>
                            </div>
                        </div>

                        <!-- BULK DISCOUNT TABLE (Dynamic) -->
                        <div id="bulk-pricing-table-container" class="mt-4 pt-3 border-top" style="display: none;">
                            <p class="fw-bold text-success mb-2 small"><i class="bi bi-percent"></i> Bulk Discount
                                Applied on High Quantities!</p>
                            <table class="table table-sm table-bordered text-center align-middle mb-0 bg-light"
                                style="font-size: 0.85rem;">
                                <thead class="table-secondary text-muted">
                                    <tr>
                                        <th>Quantity</th>
                                        <th>4+ Packs</th>
                                        <th>5+ Packs</th>
                                        <th>6+ Packs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-muted">Price/Pack</td>
                                        <td id="bp-4" class="fw-bold text-brown">-</td>
                                        <td id="bp-5" class="fw-bold text-brown">-</td>
                                        <td id="bp-6" class="fw-bold text-brown">-</td>
                                    </tr>
                                </tbody>
                            </table>
                            <small class="text-danger fw-bold mt-2 d-block" id="bulk-discount-msg"
                                style="display:none;"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Long Description Tabs -->
        <div class="card detail-wrapper-card p-4 shadow-sm border-0 mb-5" style="border-radius: 16px;">
            <ul class="nav nav-tabs mb-4" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-brown" id="desc-tab" data-bs-toggle="tab"
                        data-bs-target="#desc-pane" type="button" role="tab"
                        style="border-bottom: 3px solid #8B4513;">Detailed Overview</button>
                </li>
            </ul>
            <div class="tab-content text-muted p-2" id="productTabContent">
                <div class="tab-pane fade show active" id="desc-pane" role="tabpanel" aria-labelledby="desc-tab">
                    <div class="lh-lg fs-6 text-dark"><?php echo $p_long_desc; ?></div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <?php if ($related_query && $related_query->num_rows > 0): ?>
            <section class="related-products mt-5 pt-4 border-top">
                <h3 class="fw-bold text-brown mb-4"><i class="bi bi-stars me-2"></i>You Might Also Like</h3>
                <div class="row g-4">
                    <?php while ($r_prod = $related_query->fetch_assoc()): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="related-card">
                                <a href="<?php echo $site; ?>product/<?php echo $r_prod['slug_url']; ?>"
                                    class="text-decoration-none">
                                    <div class="related-img-box">
                                        <img src="<?php echo $site; ?>admin/assets/img/uploads/<?php echo $r_prod['pro_img']; ?>"
                                            alt="<?php echo htmlspecialchars($r_prod['pro_name']); ?>">
                                    </div>
                                    <div class="p-3 text-center border-top">
                                        <h6 class="fw-bold text-dark text-truncate mb-2">
                                            <?php echo htmlspecialchars($r_prod['pro_name']); ?>
                                        </h6>
                                        <div class="text-danger fw-bold fs-5">₹<?php echo $r_prod['selling_price']; ?></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>

    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const targetImg = document.getElementById("magnify-target-img");
            const variations = <?php echo !empty($variations_json) ? $variations_json : '[]'; ?>;
            const baseImgUrl = '<?php echo $site . "admin/assets/img/uploads/"; ?>';
            const defaultImg = '<?php echo $p_img; ?>';

            let currentVariation = variations.length > 0 ? variations[0] : null;
            let qty = 1;

            // Thumbnail Click Event
            document.querySelectorAll('.var-thumb').forEach(thumb => {
                thumb.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    const radio = document.querySelectorAll('.variation-radio')[index];
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                });
            });

            function updateUI() {
                if (!currentVariation) return;

                let unitPrice = parseFloat(currentVariation.single_price);
                let discountMsg = "";

                // Bulk Price Logic
                if (qty >= 6 && currentVariation.price_6_plus !== null && parseFloat(currentVariation.price_6_plus) > 0) {
                    unitPrice = parseFloat(currentVariation.price_6_plus);
                    discountMsg = "Super Saver: 6+ Bulk Price Applied! 💥";
                } else if (qty >= 5 && currentVariation.price_5_plus !== null && parseFloat(currentVariation.price_5_plus) > 0) {
                    unitPrice = parseFloat(currentVariation.price_5_plus);
                    discountMsg = "Mega Saver: 5+ Bulk Price Applied! 🔥";
                } else if (qty >= 4 && currentVariation.price_4_plus !== null && parseFloat(currentVariation.price_4_plus) > 0) {
                    unitPrice = parseFloat(currentVariation.price_4_plus);
                    discountMsg = "Smart Saver: 4+ Bulk Price Applied! 🎉";
                }

                let totalPrice = unitPrice * qty;

                // Update Price
                document.getElementById('display-price').innerHTML = '₹' + unitPrice.toFixed(2) + ' <span class="fs-6 text-muted fw-normal">(' + currentVariation.weight_size + ')</span>';
                if (qty > 1) document.getElementById('display-total-price').innerText = '(Total: ₹' + totalPrice.toFixed(2) + ')';
                else document.getElementById('display-total-price').innerText = '';

                // Update Discount Message
                const msgEl = document.getElementById('bulk-discount-msg');
                if (discountMsg) { msgEl.innerText = discountMsg; msgEl.style.display = 'block'; }
                else { msgEl.style.display = 'none'; }

                // Show Bulk Pricing Table dynamically
                const bpContainer = document.getElementById('bulk-pricing-table-container');
                let hasBulk = false;
                if (currentVariation.price_4_plus > 0) { document.getElementById('bp-4').innerText = '₹' + currentVariation.price_4_plus; hasBulk = true; } else { document.getElementById('bp-4').innerText = '-'; }
                if (currentVariation.price_5_plus > 0) { document.getElementById('bp-5').innerText = '₹' + currentVariation.price_5_plus; hasBulk = true; } else { document.getElementById('bp-5').innerText = '-'; }
                if (currentVariation.price_6_plus > 0) { document.getElementById('bp-6').innerText = '₹' + currentVariation.price_6_plus; hasBulk = true; } else { document.getElementById('bp-6').innerText = '-'; }

                bpContainer.style.display = hasBulk ? 'block' : 'none';

                // Update Image
                if (currentVariation.image_path && currentVariation.image_path.trim() !== '') {
                    targetImg.src = baseImgUrl + currentVariation.image_path;
                } else {
                    targetImg.src = defaultImg;
                }

                // Highlight Active Thumbnail
                document.querySelectorAll('.var-thumb').forEach(thumb => {
                    if (thumb.getAttribute('data-index') == variations.indexOf(currentVariation)) {
                        thumb.classList.add('active-thumb');
                    } else {
                        thumb.classList.remove('active-thumb');
                    }
                });

                // Update Stock Status
                const stockBadge = document.getElementById('stock-badge');
                if (parseInt(currentVariation.stock) > 0) {
                    stockBadge.className = "badge align-self-start bg-success mb-2 px-3 py-2 rounded-pill";
                    stockBadge.innerHTML = '<i class="bi bi-shield-check me-1"></i> In Stock';
                } else {
                    stockBadge.className = "badge align-self-start bg-danger mb-2 px-3 py-2 rounded-pill";
                    stockBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i> Out of Stock';
                }
            }

            // Radio Button Events
            document.querySelectorAll('.variation-radio').forEach(radio => {
                radio.addEventListener('change', function () {
                    const index = this.getAttribute('data-index');
                    currentVariation = variations[index];
                    qty = 1;
                    document.getElementById('product-qty').value = qty;
                    updateUI();
                });
            });

            // QTY Events
            document.getElementById('btn-qty-plus').addEventListener('click', () => {
                if (currentVariation && qty < parseInt(currentVariation.stock)) {
                    qty++; document.getElementById('product-qty').value = qty; updateUI();
                }
            });

            document.getElementById('btn-qty-minus').addEventListener('click', () => {
                if (qty > 1) { qty--; document.getElementById('product-qty').value = qty; updateUI(); }
            });

            // ADD TO CART AJAX
            document.getElementById('custom-add-to-cart-btn').addEventListener('click', () => {
                if (currentVariation && parseInt(currentVariation.stock) > 0) {
                    let productId = <?php echo $p_id; ?>;
                    let variationId = currentVariation.id;
                    let finalQty = qty;

                    $.ajax({
                        url: '<?php echo $site; ?>cart_action.php',
                        type: 'POST',
                        data: {
                            action: 'add_to_cart',
                            product_id: productId,
                            variation_id: variationId,
                            quantity: finalQty
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                $('.cart-count').text(response.cart_count);
                                alert("Success: " + finalQty + " Pack of " + currentVariation.weight_size + " added to your basket!");
                            } else {
                                alert("Error: " + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            alert("System Error: Could not connect to the cart server. Check console for details.");
                        }
                    });
                } else {
                    alert('Cannot add out of stock item to basket.');
                }
            });

            // =====================================
            // REAL BUY NOW AJAX EXECUTION
            // =====================================
            document.getElementById('custom-buy-now-btn').addEventListener('click', () => {
                if (currentVariation && parseInt(currentVariation.stock) > 0) {
                    let productId = <?php echo $p_id; ?>;
                    let variationId = currentVariation.id;
                    let finalQty = qty;

                    document.getElementById('custom-buy-now-btn').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                    $.ajax({
                        url: '<?php echo $site; ?>cart_action.php',
                        type: 'POST',
                        data: {
                            action: 'buy_now', // FIX: Yahan bhi add_to_cart ko buy_now karna zaroori tha
                            product_id: productId,
                            variation_id: variationId,
                            quantity: finalQty
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                // FIX: Redirect with the flag
                                window.location.href = '<?php echo $site; ?>checkout.php?buy_now=true';
                            } else {
                                alert("Error: " + response.message);
                                document.getElementById('custom-buy-now-btn').innerHTML = '<i class="bi bi-lightning-charge-fill me-1"></i> BUY NOW';
                            }
                        },
                        error: function () {
                            alert("System Error: Could not connect to the cart server.");
                            document.getElementById('custom-buy-now-btn').innerHTML = '<i class="bi bi-lightning-charge-fill me-1"></i> BUY NOW';
                        }
                    });
                } else {
                    alert('Cannot buy out of stock item.');
                }
            });
            updateUI();
        });
    </script>
</body>

</html>