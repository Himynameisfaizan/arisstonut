<?php
$pageTitle = '404 - Page Not Found | AristoNut';
$meta_title = '404 - Page Not Found';
$meta_desc = 'The page you are looking for does not exist.';

include 'config/connect.php';
include 'inc/header.php';
?>

<style>
/* ================= 404 ERROR PAGE STYLES ================= */
.error-section {
    padding: 100px 0;
    background-color: #fffaf5;
    /* AristoNut Light Beige */
    text-align: center;
    min-height: 65vh;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid rgba(139, 69, 19, 0.05);
}

.error-content {
    animation: fadeInUp 0.6s ease-out;
}

.error-content h1 {
    font-size: clamp(6rem, 12vw, 10rem);
    font-weight: 900;
    color: #9c5521;
    /* AristoNut Premium Brown */
    font-family: 'Poppins', sans-serif;
    margin-bottom: 0;
    line-height: 1;
    text-shadow: 4px 4px 0px rgba(156, 85, 33, 0.1);
}

.error-content h2 {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #2c1e16;
    /* Dark Brown */
    margin-top: 10px;
    margin-bottom: 15px;
    font-family: 'Poppins', sans-serif;
}

.error-content p {
    color: #6b5b53;
    /* Muted Text */
    font-size: 1.05rem;
    margin-bottom: 35px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

.btn-back-home {
    background-color: #2c1e16;
    color: #ffffff !important;
    padding: 14px 32px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    border: 1px solid #2c1e16;
}

.btn-back-home:hover {
    background-color: #9c5521;
    border-color: #9c5521;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(156, 85, 33, 0.2);
}

.error-icon {
    font-size: 4rem;
    color: #9c5521;
    margin-bottom: -15px;
    display: block;
    opacity: 0.8;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<section class="error-section">
    <div class="container">
        <div class="error-content">
            <i class="bi bi-emoji-frown error-icon"></i>
            <h1>404</h1>
            <h2>Oops! Page Not Found</h2>
            <p>The page you are looking for might have been removed, had its
                name changed, or is temporarily unavailable.</p>
            <a href="<?php echo $site; ?>index.php" class="btn-back-home">
                <i class="bi bi-house-door-fill"></i> Back to Homepage
            </a>
        </div>
    </div>
</section>

<?php include 'inc/footer.php'; ?>