<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Configuration matrix aur routing layer load line 1
include('config/connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - AristoNut | Premium Quality Makhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

   
</head>

<body>

    <?php include('inc/header.php'); ?>

    <main class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $site; ?>index.php" class="text-brown text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Terms & Conditions</li>
            </ol>
        </nav>

        <div class="card legal-wrapper-card shadow-sm mb-5">

            <div class="legal-header">
                <h1 class="fw-bold text-brown mb-2"><i class="bi bi-file-earmark-gantt-fill me-2"></i> Terms & Conditions</h1>
                <p class="text-muted mb-0 small">Last Updated: <?php echo date('F d, Y'); ?> • AristoNut Snacks Enterprise</p>
            </div>

            <div class="legal-content px-lg-3">
                <p>Welcome to <strong>AristoNut</strong>. By accessing, browsing, or executing commercial transactions on this webportal platform, you express complete binding alignment with the structural utilization rules, policies, and legally governed constraints detailed below. Please read them thoroughly before initializing orders.</p>

                <h4>1. Account Utilization & Platform Integrity</h4>
                <p>Users initializing purchase intents or utilizing interactive segments like the shopping cart or wishlist agree to the following protocols:</p>
                <ul>
                    <li>You are solely responsible for ensuring the accuracy of your input parameters (such as email IDs, mobile contact details, and precise postal shipping coordinates).</li>
                    <li>Any malicious injection attempts, script breaks, or unauthorized automated access targeting the backend architecture database schemas will lead to immediate service termination and administrative counter-actions.</li>
                </ul>

                <h4>2. Pricing, Products Specification & Database Records</h4>
                <p>All items displayed in our premium product registry are subject to availability variables:</p>
                <ul>
                    <li>Product descriptions, net weights (grams), packaging aesthetics, and pricing matrix metrics are dynamically mapped directly from live table arrays entries.</li>
                    <li>We put maximum efforts to eliminate discrepancies, but if a technical lag errors row pricing logic inside the system matrix, AristoNut preserves absolute structural rights to reject or re-align the checkout processing traces.</li>
                </ul>

                <h4>3. Ordering Framework & Contract Placement Execution</h4>
                <p>When you click on the <strong>"Place Order"</strong> action sequence triggers, your request is piped down via structured validation statements:</p>
                <ul>
                    <li>Each submission initializes a distinct, immutable <strong>Tracking Order Number</strong> within the `orders` registry.</li>
                    <li>The automated invoice generated at <code>generate_bill.php</code> tracks binding financial records, including subtotal values, regional tax indicators, and payment modes.</li>
                    <li>Admin handlers preserve independent operational privileges to update the fulfillment status to <strong>Pending</strong>, <strong>Processing</strong>, or <strong>Completed</strong> directly via administrative master dashboards.</li>
                </ul>

                <h4>4. Shipping, Delivery & Free Logistics Scope</h4>
                <p>Fulfillment timelines depend strictly on physical geographic distribution parameters:</p>
                <ul>
                    <li><strong>Free Logistics Threshold:</strong> All checkout procedures crossing total bill parameters over ₹500 automatically bypass standard carriage costs.</li>
                    <li>Consolidated shipping metrics are extracted from user inputs fields during checkout streams. AristoNut is not responsible for drops or shipment loss occurring due to corrupted string parameters supplied in the customer address inputs.</li>
                </ul>

                <h4>5. Intellectual Property Rights</h4>
                <p>The entire asset structure hosted inside this platform—including brand text identifiers ("AristoNut"), premium digital layout styling blocks, vector iconography sets, and product image nodes generated from the uploads directory—is protected under intellectual property legislations and must not be repurposed without explicit authorization codes.</p>

                <h4>6. Amendments to Legal Policies</h4>
                <p>We preserve full operational rights to re-align or append terms configurations blocks at any given moment. Continued interaction with the interface system after updates are rolled out constitutes an automatic acceptance of the revised conditions matrix.</p>

                <h4>7. Jurisdictional Governance Support</h4>
                <p>Any disputes or processing compliance failures tracking out from these parameters shall be handled under the judicial sovereignty courts centered around Darbhanga, Bihar. For instant clarifications, connect with our management pipeline directly via email at <strong>aristonut@gmail.com</strong>.</p>
            </div>

        </div>
    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>