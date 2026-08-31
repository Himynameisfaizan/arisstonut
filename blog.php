<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('config/connect.php');

$limit = 9; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$count_query = "SELECT COUNT(*) as total FROM blogs WHERE status = 1";
$count_result = $conn->query($count_query);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$blog_query = "SELECT blog_id, title, slug, author, image, description, created_at 
               FROM blogs 
               WHERE status = 1 
               ORDER BY blog_id DESC 
               LIMIT $limit OFFSET $offset";
$blog_result = $conn->query($blog_query);
?>
<?php 
$pageTitle = "Blog & Recipes";
include('inc/header.php');
include('inc/breadcrumb.php');
?>


<main class="blog-listing-section">
    <div class="container">
        
        <div class="row g-4 align-items-stretch">
            <?php if ($blog_result && $blog_result->num_rows > 0): ?>
                <?php while ($blog = $blog_result->fetch_assoc()):
                    $b_title = htmlspecialchars($blog['title']);
                    $b_slug = htmlspecialchars($blog['slug']);
                    $b_author = htmlspecialchars($blog['author'] ?? 'AristoNut');
                    $b_date = date('d M, Y', strtotime($blog['created_at']));

                    $b_desc = strip_tags($blog['description']);
                    $b_desc = strlen($b_desc) > 130 ? substr($b_desc, 0, 130) . '...' : $b_desc;

                    // Image matching index page logic with uploads/blog fallback
                    $b_img = !empty($blog['image']) ? $site . 'admin/assets/img/uploads/blogs/' . htmlspecialchars($blog['image']) : $site . 'assets/images/hero.webp';
                    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . parse_url($b_img, PHP_URL_PATH))) {
                        $b_img = $site . 'admin/uploads/' . htmlspecialchars($blog['image']);
                    }
                ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $b_slug; ?>" class="modern-blog-card">
                            
                            <!-- Image Frame with Smooth Hover Zoom -->
                            <div class="blog-img-box">
                                <img src="<?php echo $b_img; ?>" alt="<?php echo $b_title; ?>" onerror="this.src='https://thumbs.dreamstime.com/b/roasted-lotus-seed-makhana-22764990.jpg?w=768';">
                                <span class="blog-author-badge"><i class="bi bi-person-fill me-1"></i> <?php echo $b_author; ?></span>
                            </div>

                            <!-- Content -->
                            <div class="blog-content">
                                <div class="blog-date">
                                    <i class="bi bi-calendar3"></i> <?php echo $b_date; ?>
                                </div>

                                <h3 class="blog-title" title="<?php echo $b_title; ?>"><?php echo $b_title; ?></h3>
                                <p class="blog-snippet"><?php echo $b_desc; ?></p>
                                
                                <div class="blog-read-more">
                                    Read Full Article <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="col-12 text-center py-5 my-5">
                    <div class="p-5 rounded-4 border" style="background: #FFFFFF; border-style: dashed !important; border-color: rgba(156,85,33,0.3) !important;">
                        <i class="bi bi-journal-x display-2 d-block mb-3" style="color: var(--brand-accent);"></i>
                        <h3 class="fw-bold" style="color: var(--text-dark); font-family: 'Poppins', sans-serif;">No Articles Published Yet</h3>
                        <p class="text-muted" style="font-family: 'Inter', sans-serif;">We are working on fresh recipes and nutritional guides. Check back soon!</p>
                        <a href="<?php echo $site; ?>index.php" class="btn text-white px-4 py-2 mt-2 rounded-pill" style="background: var(--text-dark); font-weight: 500;">Return to Homepage</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Dynamic Circular Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Blog Page Navigation">
                <ul class="pagination justify-content-center custom-pagination">

                    <!-- Previous Button -->
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>

                </ul>
            </nav>
        <?php endif; ?>
        
    </div>
</main>

<?php include('inc/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>