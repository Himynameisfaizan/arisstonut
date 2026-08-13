<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php');
?>
<?php include('inc/header.php'); ?>

<main class="container py-5" style="min-height: 70vh;">
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold text-brown"><i class="bi bi-heart-fill me-2 text-danger"></i> My Wishlist</h2>
            <p class="text-muted small">Your favorite premium snacks collection saved for later.</p>
        </div>
    </div>

    <div class="row g-4" id="wishlist-main-grid">
        <?php
        $has_items = false;
        if (isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist'])) {
            $has_items = true;
            foreach ($_SESSION['wishlist'] as $pid) {
                $query = $conn->query("SELECT id, pro_name, selling_price, qty, pro_img, slug_url FROM products WHERE id = '$pid' AND status = 1");
                if ($query && $query->num_rows > 0) {
                    $row = $query->fetch_assoc();
                    $p_img = $site . 'admin/assets/img/uploads/' . htmlspecialchars($row['pro_img']);
        ?>
                    <div class="col-md-6 col-lg-4 col-xl-3" id="wish-card-<?php echo $pid; ?>">
                        <div class="card wishlist-wrapper-card h-100 position-relative">
                            <span class="remove-wish-icon" onclick="toggleWishlist(<?php echo $pid; ?>, 'listing')">
                                <i class="bi bi-trash3-fill"></i>
                            </span>

                            <a href="<?php echo $site . 'product/' . htmlspecialchars($row['slug_url']); ?>">
                                <img src="<?php echo $p_img; ?>" class="wishlist-prod-img" alt="">
                            </a>

                            <div class="card-body d-flex flex-column text-center p-3">
                                <h6 class="fw-bold text-brown text-truncate mb-2"><?php echo htmlspecialchars($row['pro_name']); ?></h6>
                                <div class="text-muted small mb-3">₹<?php echo htmlspecialchars($row['selling_price']); ?> <span class="small">/ <?php echo htmlspecialchars($row['qty']); ?>g</span></div>

                                <div class="mt-auto">
                                    <button class="btn btn-add-cart-wish" onclick="addToCart(<?php echo $pid; ?>)">
                                        Add to Cart <i class="bi bi-cart-plus ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
        <?php
                }
            }
        }
        ?>
    </div>

    <div id="empty-wish-msg" class="text-center py-5" <?php echo $has_items ? 'style="display:none;"' : ''; ?>>
        <i class="bi bi-heart text-muted fs-1 d-block mb-3"></i>
        <h5 class="fw-bold text-muted">Your wishlist is empty!</h5>
        <p class="text-muted small">Explore our collections to add your top items here.</p>
        <a href="<?php echo $site; ?>product.php" class="btn btn-primary-custom btn-sm d-inline-block mt-2" style="background:#8B4513; border-radius:20px; color:#fff; text-decoration:none; padding:8px 20px;">Browse Products</a>
    </div>
</main>

<?php include('inc/footer.php'); ?>

<script>
    function toggleWishlist(productId, context) {
        $.ajax({
            url: '<?php echo $site; ?>wishlist_action.php',
            type: 'POST',
            data: {
                action: 'toggle_wishlist',
                product_id: productId
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    if (context === 'listing') {
                        $('#wish-card-' + productId).fadeOut(300, function() {
                            $(this).remove();
                            // Check if grid matrix structural cards are zero empty
                            if ($('#wishlist-main-grid').children().length === 0) {
                                $('#empty-wish-msg').fadeIn();
                            }
                        });
                    }
                }
            }
        });
    }
</script>
</body>

</html>