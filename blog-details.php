<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php');

if (isset($_GET['slug']) && !empty(trim($_GET['slug']))) {
    $blog_slug = $conn->real_escape_string(trim($_GET['slug']));

    $stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 1 LIMIT 1");
    $stmt->bind_param("s", $blog_slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $blog = $result->fetch_assoc();

        $b_id = $blog['blog_id'];
        $b_title = htmlspecialchars($blog['title']);
        $b_author = htmlspecialchars($blog['author'] ?? 'AristoNut Team');
        $b_date = date('F d, Y', strtotime($blog['created_at']));
        $b_content = $blog['description']; 
        $b_img = !empty($blog['image']) ? $site . 'admin/assets/img/uploads/blogs/' . htmlspecialchars($blog['image']) : $site . 'assets/images/hero.webp';

        $meta_title = !empty($blog['meta_title']) ? htmlspecialchars($blog['meta_title']) : $b_title . " - AristoNut Blog";
        $meta_desc = !empty($blog['meta_desc']) ? htmlspecialchars($blog['meta_desc']) : substr(strip_tags($b_content), 0, 160);
        $meta_keyword = !empty($blog['meta_key']) ? htmlspecialchars($blog['meta_key']) : "makhana blogs, makhana benefits, health recipes";
        $schema_markup = !empty($blog['schema_markup']) ? $blog['schema_markup'] : "";

        $og_title = !empty($blog['og_title']) ? htmlspecialchars($blog['og_title']) : $meta_title;
        $og_desc = !empty($blog['og_desc']) ? htmlspecialchars($blog['og_desc']) : $meta_desc;
        $og_image = !empty($blog['og_image']) ? $site . 'admin/uploads/seo/' . htmlspecialchars($blog['og_image']) : $b_img;

    } else {
       http_response_code(404);
    include('404.php');
    exit();
    }
    $stmt->close();
} else {
  http_response_code(404);
    include('404.php');
    exit();
}

$sidebar_query = "SELECT title, slug, image, created_at FROM blogs WHERE status = 1 AND blog_id != '$b_id' ORDER BY blog_id DESC LIMIT 4";
$sidebar_result = $conn->query($sidebar_query);

$pageTitle = $blog['title'];
include('inc/header.php');
include ('inc/breadcrumb.php');
?>

<style>
/* =========================================
   BLOG CONTENT - CKEDITOR FRONTEND FIX
========================================= */

.article-rich-textContent {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    color: #343434;
    font-size: 17px;
    line-height: 1.85;
    text-align: left;
    overflow-wrap: anywhere;
    word-break: normal;
    word-spacing: normal;
    letter-spacing: normal;
}

/* Paragraph spacing and alignment */
.article-rich-textContent p {
    margin: 0 0 22px;
    padding: 0;
    line-height: 1.85;
    color: #343434;
    overflow-wrap: anywhere;
}

/* Fix excessive spacing caused by justified paragraphs */
.article-rich-textContent p[style*="text-align: justify"],
.article-rich-textContent div[style*="text-align: justify"] {
    text-align: left !important;
    word-spacing: normal !important;
}

/* CKEditor headings */
.article-rich-textContent h1,
.article-rich-textContent h2,
.article-rich-textContent h3,
.article-rich-textContent h4,
.article-rich-textContent h5,
.article-rich-textContent h6 {
    color: #3E2723;
    font-weight: 700;
    line-height: 1.4;
    margin: 30px 0 15px;
    overflow-wrap: anywhere;
}

.article-rich-textContent h1 {
    font-size: clamp(27px, 4vw, 36px);
}

.article-rich-textContent h2 {
    font-size: clamp(24px, 3.5vw, 30px);
}

.article-rich-textContent h3 {
    font-size: clamp(21px, 3vw, 25px);
}

.article-rich-textContent h4 {
    font-size: 20px;
}

/* Bold and italic formatting */
.article-rich-textContent strong,
.article-rich-textContent b {
    font-weight: 700;
    color: #292929;
}

.article-rich-textContent em,
.article-rich-textContent i {
    font-style: italic;
}

/* Links */
.article-rich-textContent a {
    color: #8B4513;
    text-decoration: underline;
    text-underline-offset: 3px;
    overflow-wrap: anywhere;
}

.article-rich-textContent a:hover {
    color: #3E2723;
}

/* Lists */
.article-rich-textContent ul,
.article-rich-textContent ol {
    padding-left: 28px;
    margin: 0 0 24px;
}

.article-rich-textContent li {
    padding-left: 4px;
    margin-bottom: 10px;
    line-height: 1.8;
}

.article-rich-textContent li>p {
    margin-bottom: 8px;
}

/* Tables created in CKEditor */
.article-rich-textContent table {
    width: 100%;
    max-width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin: 25px 0;
    color: #343434;
    font-size: 15px;
}

.article-rich-textContent th,
.article-rich-textContent td {
    border: 1px solid #e0d6ce;
    padding: 12px 15px;
    vertical-align: top;
    text-align: left;
}

.article-rich-textContent th {
    background-color: #3E2723;
    color: #ffffff;
    font-weight: 700;
}

.article-rich-textContent tr:nth-child(even) td {
    background-color: #FFF8F0;
}

/* Images inserted through CKEditor */
.article-rich-textContent img {
    display: block;
    max-width: 100% !important;
    height: auto !important;
    margin: 22px auto;
    border-radius: 8px;
}

.article-rich-textContent figure {
    max-width: 100%;
    margin: 25px 0;
}

.article-rich-textContent figure img {
    margin: 0 auto;
}

.article-rich-textContent figcaption {
    margin-top: 10px;
    color: #777;
    font-size: 14px;
    text-align: center;
}

/* Quotes */
.article-rich-textContent blockquote {
    margin: 25px 0;
    padding: 18px 22px;
    background: #FFF8F0;
    border-left: 4px solid #8B4513;
    border-radius: 0 8px 8px 0;
    color: #555;
}

/* Horizontal separator */
.article-rich-textContent hr {
    margin: 30px 0;
    border: 0;
    border-top: 1px solid #e5ddd6;
}

/* Code blocks */
.article-rich-textContent pre {
    max-width: 100%;
    padding: 16px;
    background: #f5f5f5;
    border-radius: 8px;
    overflow-x: auto;
    white-space: pre;
}

.article-rich-textContent code {
    overflow-wrap: anywhere;
}

/* Prevent content from breaking the sidebar layout */
.blog-detail-container .row,
.blog-detail-container .col-lg-8 {
    min-width: 0;
}

/* Mobile responsive styling */
@media (max-width: 767px) {
    .article-rich-textContent {
        font-size: 16px;
        line-height: 1.8;
    }

    .article-rich-textContent p {
        margin-bottom: 18px;
    }

    .article-rich-textContent h1 {
        font-size: 27px;
    }

    .article-rich-textContent h2 {
        font-size: 24px;
    }

    .article-rich-textContent h3 {
        font-size: 21px;
    }

    .article-rich-textContent table {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .article-rich-textContent th,
    .article-rich-textContent td {
        padding: 10px;
        min-width: 100px;
    }

    .article-rich-textContent ul,
    .article-rich-textContent ol {
        padding-left: 24px;
    }

    .article-rich-textContent blockquote {
        padding: 15px;
    }
}
</style>

<main class="container blog-detail-container mb-5 pb-5">

    <div class="row g-5">

        <div class="col-lg-8">
            <article>


                <div class="featured-img-box mb-4">
                    <img src="<?php echo $b_img; ?>" alt="<?php echo $b_title; ?>">
                </div>
                <div class="meta-strip mb-4 pb-3 border-bottom">
                    <span><i class="bi bi-person-fill text-brown me-1"></i> By
                        <strong><?php echo $b_author; ?></strong></span>
                    <span><i class="bi bi-calendar3 text-brown me-1"></i> <?php echo $b_date; ?></span>
                    <span><i class="bi bi-folder-fill text-brown me-1"></i> Healthy Superfoods</span>
                </div>
                <h2 class="article-title mb-3"><?php echo $b_title; ?></h2>

                <div class="article-rich-textContent mt-4">
                    <?php echo $b_content; ?>
                </div>
            </article>

            <div class="mt-5 p-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3"
                style="background-color: #FFF8F0; border: 1px dashed #F5E6D3;">
                <span class="fw-bold text-brown fs-6"><i class="bi bi-share-fill me-2"></i>Share this healthy
                    insight:</span>
                <div class="d-flex gap-2">
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($b_title . ' - ' . $site . 'blog-details.php?slug=' . $blog_slug); ?>"
                        target="_blank" class="btn btn-sm btn-success rounded-circle px-2 py-1"><i
                            class="bi bi-whatsapp fs-5"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($site . 'blog-details.php?slug=' . $blog_slug); ?>"
                        target="_blank" class="btn btn-sm btn-primary rounded-circle px-2 py-1"><i
                            class="bi bi-facebook fs-5"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-card p-4 shadow-sm mb-4">
                <h5 class="fw-bold mb-3 pb-2 text-brown border-bottom"
                    style="border-bottom: 2px dashed #F5E6D3 !important;">
                    <i class="bi bi-journal-text me-2"></i>Recent Articles
                </h5>

                <div class="d-flex flex-column gap-4 mt-3">
                    <?php if ($sidebar_result && $sidebar_result->num_rows > 0): ?>
                    <?php while ($s_row = $sidebar_result->fetch_assoc()):
                            $s_title = htmlspecialchars($s_row['title']);
                            $s_slug = htmlspecialchars($s_row['slug']);
                            $s_date = date('M d, Y', strtotime($s_row['created_at']));
                            $s_img = !empty($s_row['image']) ? $site . 'admin/assets/img/uploads/blogs/' . htmlspecialchars($s_row['image']) : $site . 'assets/images/hero.webp';
                        ?>
                    <div class="d-flex align-items-center gap-3">
                        <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $s_slug; ?>">
                            <img src="<?php echo $s_img; ?>" class="recent-blog-thumb" alt="<?php echo $s_title; ?>">
                        </a>
                        <div class="flex-grow-1">
                            <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $s_slug; ?>"
                                class="recent-post-link">
                                <h6 class="mb-1 text-truncate-2"><?php echo $s_title; ?></h6>
                            </a>
                            <small class="text-muted" style="font-size: 0.8rem;"><i
                                    class="bi bi-calendar3 me-1"></i><?php echo $s_date; ?></small>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <p class="text-muted small mb-0">No other articles available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card p-4 text-center border-0"
                style="background: linear-gradient(45deg, #3E2723, #8B4513); border-radius: 16px; color:#fff;">
                <i class="bi bi-bag-heart-fill display-4 text-white mb-2"></i>
                <h5 class="fw-bold">Hungry for Health?</h5>
                <p class="small text-white-50">Ditch the processed junk chips. Switch to AristoNut premium roasted
                    flavors!</p>
                <a href="<?php echo $site; ?>product.php"
                    class="btn btn-light btn-sm rounded-pill px-4 py-2 fw-bold text-brown mt-2">Shop Premium Makhana</a>
            </div>
        </div>

    </div>
</main>

<?php include('inc/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>