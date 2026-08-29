<?php

if (!isset($pageTitle)) {
    $pageTitle = "Page";
}

if (!isset($bgImage)) {
    $bgImage = isset($site) ? $site . "assets/images/flavoure/pack1/cream-onion/5.jpg" : "https://images.unsplash.com/photo-1596422846543-73c1d9b3e107?q=80&w=1920&auto=format&fit=crop";
}
?>

<style>
    .premium-breadcrumb-section {
  position: relative;
  padding: 120px 0 90px 0;
  background-image: url("<?php echo $bgImage; ?>");
  background-size: contain;
  background-position: top;
  background-attachment: fixed;
  text-align: center;
  overflow: hidden;
}

</style>

<section class="premium-breadcrumb-section">
    <div class="container">
        <div class="bc-content">
            <!-- Dynamic Page Title -->
            <h1 class="bc-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
            
            <!-- Glassmorphism Trail Box -->
            <div class="bc-trail-wrapper">
                <div class="bc-trail">
                    <!-- Home Link -->
                    <a href="<?php echo isset($site) ? $site : ''; ?>index.php" class="bc-link">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                    
                    <!-- OPTIONAL: Parent Category Link -->
                    <?php if (isset($parentName) && isset($parentUrl)): ?>
                        <span class="bc-separator"><i class="bi bi-chevron-right"></i></span>
                        <a href="<?php echo htmlspecialchars($parentUrl); ?>" class="bc-link">
                            <?php echo htmlspecialchars($parentName); ?>
                        </a>
                    <?php endif; ?>

                    <!-- Current Active Page -->
                    <span class="bc-separator"><i class="bi bi-chevron-right"></i></span>
                    <span class="bc-active"><?php echo htmlspecialchars($pageTitle); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>