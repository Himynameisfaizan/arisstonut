<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database & Global Configuration Layer Integration
include('config/connect.php');

// ==========================================
// DYNAMIC BLOG SLUG VERIFICATION
// ==========================================
if (isset($_GET['slug']) && !empty(trim($_GET['slug']))) {
    $blog_slug = $conn->real_escape_string(trim($_GET['slug']));

    // Fetch the specific blog matching the unique slug parameter
    $stmt = $conn->prepare("SELECT blog_id, title, author, image, description, meta_title, meta_desc, meta_keywords, created_at FROM blogs WHERE slug = ? AND status = 1 LIMIT 1");
    $stmt->bind_param("s", $blog_slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $blog = $result->fetch_assoc();

        $b_id = $blog['blog_id'];
        $b_title = htmlspecialchars($blog['title']);
        $b_author = htmlspecialchars($blog['author'] ?? 'AristoNut Team');
        $b_date = date('F d, Y', strtotime($blog['created_at']));
        $b_content = $blog['description']; // Contains rich text or standard paragraphs

        // SEO Configurations Meta Properties
        $seo_title = !empty($blog['meta_title']) ? htmlspecialchars($blog['meta_title']) : $b_title . " - AristoNut Blog";
        $seo_desc = !empty($blog['meta_desc']) ? htmlspecialchars($blog['meta_desc']) : substr(strip_tags($b_content), 0, 160);
        $seo_key = !empty($blog['meta_keywords']) ? htmlspecialchars($blog['meta_keywords']) : "makhana blogs, makhana benefits, health recipes";

        // Image asset path validation logic
        $b_img = !empty($blog['image']) ? $site . 'admin/assets/img/uploads/blogs/' . htmlspecialchars($blog['image']) : $site . 'assets/images/hero.webp';
    } else {
        // Redirect to main blog directory if slug mismatch logs are found
        header("Location: " . $site . "blog.php");
        exit();
    }
    $stmt->close();
} else {
    header("Location: " . $site . "blog.php");
    exit();
}

// Fetch Sidebar Recent Posts (Latest 4 blogs excluding current one)
$sidebar_query = "SELECT title, slug, image, created_at FROM blogs WHERE status = 1 AND blog_id != '$b_id' ORDER BY blog_id DESC LIMIT 4";
$sidebar_result = $conn->query($sidebar_query);
?>

<?php include('inc/header.php'); ?>

<main class="container blog-detail-container mb-5 pb-5">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $site; ?>index.php" class="text-brown text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo $site; ?>blog.php" class="text-brown text-decoration-none">Blogs</a></li>
            <li class="breadcrumb-item active text-truncate" style="max-width: 300px;" aria-current="page"><?php echo $b_title; ?></li>
        </ol>
    </nav>

    <div class="row g-5">

        <div class="col-lg-8">
            <article>
                <h1 class="article-title mb-3"><?php echo $b_title; ?></h1>

                <div class="meta-strip mb-4 pb-3 border-bottom">
                    <span><i class="bi bi-person-fill text-brown me-1"></i> By <strong><?php echo $b_author; ?></strong></span>
                    <span><i class="bi bi-calendar3 text-brown me-1"></i> <?php echo $b_date; ?></span>
                    <span><i class="bi bi-folder-fill text-brown me-1"></i> Healthy Superfoods</span>
                </div>

                <div class="featured-img-box mb-4">
                    <img src="<?php echo $b_img; ?>" alt="<?php echo $b_title; ?>">
                </div>

                <div class="article-rich-textContent mt-4">
                    <?php echo $b_content; ?>
                </div>
            </article>

            <div class="mt-5 p-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background-color: #FFF8F0; border: 1px dashed #F5E6D3;">
                <span class="fw-bold text-brown fs-6"><i class="bi bi-share-fill me-2"></i>Share this healthy insight:</span>
                <div class="d-flex gap-2">
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($b_title . ' - ' . $site . 'blog-details.php?slug=' . $blog_slug); ?>" target="_blank" class="btn btn-sm btn-success rounded-circle px-2 py-1"><i class="bi bi-whatsapp fs-5"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($site . 'blog-details.php?slug=' . $blog_slug); ?>" target="_blank" class="btn btn-sm btn-primary rounded-circle px-2 py-1"><i class="bi bi-facebook fs-5"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-card p-4 shadow-sm mb-4">
                <h5 class="fw-bold mb-3 pb-2 text-brown border-bottom" style="border-bottom: 2px dashed #F5E6D3 !important;">
                    <i class="bi bi-journal-text me-2"></i>Recent Articles
                </h5>

                <div class="d-flex flex-column gap-4 mt-3">
                    <?php if ($sidebar_result && $sidebar_result->num_rows > 0): ?>
                        <?php while ($s_row = $sidebar_result->fetch_assoc()):
                            $s_title = htmlspecialchars($s_row['title']);
                            $s_slug = htmlspecialchars($s_row['slug']);
                            $s_date = date('M d, Y', strtotime($s_row['created_at']));
                            $s_img = !empty($s_row['image']) ? $site . 'admin/uploads/blog/' . htmlspecialchars($s_row['image']) : $site . 'assets/images/hero.webp';
                        ?>
                            <div class="d-flex align-items-center gap-3">
                                <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $s_slug; ?>">
                                    <img src="<?php echo $s_img; ?>" class="recent-blog-thumb" alt="<?php echo $s_title; ?>">
                                </a>
                                <div class="flex-grow-1">
                                    <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $s_slug; ?>" class="recent-post-link">
                                        <h6 class="mb-1 text-truncate-2"><?php echo $s_title; ?></h6>
                                    </a>
                                    <small class="text-muted" style="font-size: 0.8rem;"><i class="bi bi-calendar3 me-1"></i><?php echo $s_date; ?></small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted small mb-0">No other articles available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card p-4 text-center border-0" style="background: linear-gradient(45deg, #3E2723, #8B4513); border-radius: 16px; color:#fff;">
                <i class="bi bi-bag-heart-fill display-4 text-white mb-2"></i>
                <h5 class="fw-bold">Hungry for Health?</h5>
                <p class="small text-white-50">Ditch the processed junk chips. Switch to AristoNut premium roasted flavors!</p>
                <a href="<?php echo $site; ?>product.php" class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold text-brown mt-2">Shop Premium Makhana</a>
            </div>
        </div>

    </div>
</main>

<?php include('inc/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>