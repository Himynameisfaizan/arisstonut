<?php
session_start();
include('config/connect.php'); // Database config

$pageTitle = "Order Status";
include('inc/header.php');

// URL se status check karna (Success ya Failed)
// Example: order-status.php?status=success&order_id=AR-98765
$status = isset($_GET['status']) ? $_GET['status'] : 'success'; 
$order_id = isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : 'ORD-' . rand(100000, 999999);
?>

<style>
    :root {
        --os-bg: #FCFAF8;
        --os-card-bg: #FFFFFF;
        --os-dark: #2C1E16;
        --os-muted: #6B5B53;
        --os-accent: #9C5521;
        --os-success: #27AE60;
        --os-danger: #E02020;
    }

    .order-status-wrapper {
        background-color: var(--os-bg);
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 80px 20px;
    }

    .status-card {
        background: var(--os-card-bg);
        border-radius: 24px;
        padding: 50px 40px;
        max-width: 550px;
        width: 100%;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.02);
        position: relative;
        overflow: hidden;
    }

    /* Icon Animation Setup */
    .icon-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px auto;
        font-size: 3.5rem;
    }

    .icon-success {
        background: rgba(39, 174, 96, 0.1);
        color: var(--os-success);
        animation: popIn 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .icon-fail {
        background: rgba(224, 32, 32, 0.1);
        color: var(--os-danger);
        animation: shakeError 0.5s ease-in-out;
    }

    @keyframes popIn {
        0% { transform: scale(0); opacity: 0; }
        80% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); }
    }

    @keyframes shakeError {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-10px); }
        40%, 80% { transform: translateX(10px); }
    }

    /* Typography */
    .status-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: var(--os-dark);
        margin-bottom: 10px;
    }

    .status-desc {
        font-family: 'Inter', sans-serif;
        font-size: 1.05rem;
        color: var(--os-muted);
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .order-badge {
        display: inline-block;
        background: #F9F6F0;
        color: var(--os-accent);
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 50px;
        border: 1px dashed rgba(156, 85, 33, 0.3);
        margin-bottom: 30px;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    /* Buttons */
    .btn-group-custom {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary-action, .btn-secondary-action {
        padding: 14px 28px;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary-action {
        background: var(--os-accent);
        color: #FFFFFF;
        border: 2px solid var(--os-accent);
        box-shadow: 0 8px 20px rgba(156, 85, 33, 0.2);
    }
    .btn-primary-action:hover {
        background: #7A4219;
        border-color: #7A4219;
        color: #FFF;
        transform: translateY(-2px);
    }

    .btn-secondary-action {
        background: #FFFFFF;
        color: var(--os-dark);
        border: 2px solid rgba(0,0,0,0.1);
    }
    .btn-secondary-action:hover {
        border-color: var(--os-dark);
        background: var(--os-dark);
        color: #FFF;
    }

    @media (max-width: 576px) {
        .status-card { padding: 40px 25px; }
        .status-title { font-size: 1.6rem; }
        .btn-group-custom { flex-direction: column; }
        .btn-primary-action, .btn-secondary-action { width: 100%; }
    }
</style>

<main class="order-status-wrapper">
    <div class="container d-flex justify-content-center">
        
        <?php if ($status === 'success'): ?>
            <!-- ================= SUCCESS CARD ================= -->
            <div class="status-card">
                <div class="icon-wrapper icon-success">
                    <i class="bi bi-check2-circle"></i>
                </div>
                
                <h1 class="status-title">Order Confirmed!</h1>
                <p class="status-desc">Thank you for choosing AristoNut. Your premium makhana is being prepared for dispatch. We've sent the confirmation details to your email.</p>
                
                <div class="order-badge">
                    Order ID: <?php echo $order_id; ?>
                </div>

                <div class="btn-group-custom">
                    <a href="<?php echo $site; ?>product.php" class="btn-primary-action">
                        Continue Shopping <i class="bi bi-arrow-right"></i>
                    </a>
                    <!-- Optional: Agar user account system hai toh yaha link de sakte ho -->
                    <a href="<?php echo $site; ?>index.php" class="btn-secondary-action">
                        <i class="bi bi-house-door"></i> Back to Home
                    </a>
                </div>
            </div>

        <?php else: ?>
            <!-- ================= FAILED CARD ================= -->
            <div class="status-card">
                <div class="icon-wrapper icon-fail">
                    <i class="bi bi-x-circle"></i>
                </div>
                
                <h1 class="status-title">Payment Failed</h1>
                <p class="status-desc">Oops! We couldn't process your payment. Don't worry, no money was deducted. Please check your connection or try a different payment method.</p>
                
                <div class="btn-group-custom mt-4">
                    <a href="<?php echo $site; ?>checkout.php" class="btn-primary-action" style="background: var(--os-danger); border-color: var(--os-danger); box-shadow: 0 8px 20px rgba(224, 32, 32, 0.2);">
                        <i class="bi bi-arrow-clockwise"></i> Try Again
                    </a>
                    <a href="<?php echo $site; ?>cart.php" class="btn-secondary-action">
                        <i class="bi bi-cart"></i> Return to Cart
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php include('inc/footer.php'); ?>