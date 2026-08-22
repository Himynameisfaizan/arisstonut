<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database & Global Configuration
include('config/connect.php');

// ==========================================
// PAGINATION & FETCH LOGIC
// ==========================================
$limit = 9; // Ek page par 9 blogs dikhayenge
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total active blogs count for pagination
$count_query = "SELECT COUNT(*) as total FROM blogs WHERE status = 1";
$count_result = $conn->query($count_query);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch blogs for current page
$blog_query = "SELECT blog_id, title, slug, author, image, description, created_at 
               FROM blogs 
               WHERE status = 1 
               ORDER BY blog_id DESC 
               LIMIT $limit OFFSET $offset";
$blog_result = $conn->query($blog_query);
?>
    <?php include('inc/header.php'); ?>

    <!-- Blog Header Banner -->
    <section class="blog-banner text-center">
        <div class="container">
            <span class="badge bg-white text-brown border mb-3 px-4 py-2 rounded-pill shadow-sm" style="color: #8B4513; font-weight: bold; letter-spacing: 1px;">JOURNAL & RECIPES</span>
            <h1 class="fw-bold display-4 text-brown mb-3">AristoNut Blog</h1>
            <p class="text-muted mx-auto fs-5" style="max-width: 650px;">Discover the health benefits of Makhana, exciting recipes, and the latest news from India's finest premium makhana brand.</p>
        </div>
    </section>

    <!-- Main Blog Grid -->
    <main class="container mb-5 pb-5">
        <div class="row g-4 align-items-stretch">
            <?php if ($blog_result && $blog_result->num_rows > 0): ?>
                <?php while ($blog = $blog_result->fetch_assoc()): 
                    // Sanitize dynamic database fields
                    $b_title = htmlspecialchars($blog['title']);
                    $b_slug = htmlspecialchars($blog['slug']);
                    $b_author = htmlspecialchars($blog['author'] ?? 'AristoNut Team');
                    $b_date = date('d M, Y', strtotime($blog['created_at']));
                    
                    // Format Description safely
                    $b_desc = strip_tags($blog['description']);
                    $b_desc = strlen($b_desc) > 120 ? substr($b_desc, 0, 120) . '...' : $b_desc;

                    // Fallback Image
                    $b_img = !empty($blog['image']) ? $site . 'admin/assets/img/uploads/blogs/' . htmlspecialchars($blog['image']) : $site . 'assets/images/hero.webp';
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card blog-card h-100 shadow-sm border-0">
                            <!-- Image Wrap with Link -->
                            <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $b_slug; ?>" class="text-decoration-none">
                                <div class="blog-img-wrapper">
                                    <img src="<?php echo $b_img; ?>" alt="<?php echo $b_title; ?>">
                                    <span class="blog-date-badge"><i class="bi bi-calendar3 me-1"></i> <?php echo $b_date; ?></span>
                                </div>
                            </a>
                            
                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column">
                                <p class="blog-author mb-2"><i class="bi bi-pen-fill me-1"></i> By <?php echo $b_author; ?></p>
                                
                                <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $b_slug; ?>" class="text-decoration-none">
                                    <h4 class="card-title fw-bold mb-3" style="line-height: 1.3; color: #3E2723;"><?php echo $b_title; ?></h4>
                                </a>
                                
                                <p class="card-text text-muted small mb-4 flex-grow-1" style="line-height: 1.6;"><?php echo $b_desc; ?></p>
                                
                                <!-- CTA Button -->
                                <a href="<?php echo $site; ?>blog-details.php?slug=<?php echo $b_slug; ?>" class="btn btn-outline-brown rounded-pill fw-bold w-100 mt-auto" style="border: 2px solid #8B4513; color: #8B4513; transition: 0.3s;" onmouseover="this.style.background='#8B4513'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#8B4513';">
                                    Read Full Article <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="col-12 text-center py-5 my-5 bg-light rounded-4 border" style="border-style: dashed !important;">
                    <i class="bi bi-journal-x text-muted display-1 d-block mb-3"></i>
                    <h3 class="fw-bold text-brown">No Blogs Published Yet</h3>
                    <p class="text-muted fs-5">We are currently cooking up some exciting content. Check back soon!</p>
                    <a href="<?php echo $site; ?>index.php" class="btn text-white px-4 py-2 mt-3 rounded-pill" style="background:#8B4513;">Return to Homepage</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Dynamic Pagination UI -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Blog Page Navigation">
                <ul class="pagination justify-content-center custom-pagination">
                    
                    <!-- Previous Button -->
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                        </a>
                    </li>
                    
                    <!-- Page Numbers -->
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <!-- Next Button -->
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                        </a>
                    </li>
                    
                </ul>
            </nav>
        <?php endif; ?>
    </main>

    <?php include('inc/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>