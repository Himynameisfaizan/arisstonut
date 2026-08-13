<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php');
?>

    <?php include('inc/header.php'); ?>

    <main class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $site; ?>index.php" class="text-brown text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
            </ol>
        </nav>

        <div class="card legal-wrapper-card shadow-sm mb-5">

            <div class="legal-header">
                <h1 class="fw-bold text-brown mb-2"><i class="bi bi-shield-lock-fill me-2"></i> Privacy Policy</h1>
                <p class="text-muted mb-0 small">Last Updated: <?php echo date('F d, Y'); ?> • AristoNut Snacks Enterprise</p>
            </div>

            <div class="legal-content px-lg-3">
                <p>Welcome to <strong>AristoNut</strong>. Your data security and privacy protection structure are core pillars of our governance. This Privacy Policy describes how your personal metrics and dataset coordinates are safely collected, processed, and maintained when you use or place an order through our webportal platform.</p>

                <h4>1. Information We Collect</h4>
                <p>When you interact with the AristoNut store ecosystem, certain dynamic fields are logged automatically or through manual entry initialization layers:</p>
                <ul>
                    <li><strong>Personal Identity Coordinates:</strong> Name, Email Address, Phone Number, and physical Shipping Address transmitted during the checkout processing sequences.</li>
                    <li><strong>Device Configuration Data Logs:</strong> IP Address, Session Identification matrix identifiers, and network parameter records utilized for security logs validation profiling.</li>
                    <li><strong>Volatile Shopping Metadata:</strong> Selected flavors preferences arrays, cart tracking memory nodes, and wishlist collections arrays.</li>
                </ul>

                <h4>2. How We Use Your Data</h4>
                <p>AristoNut processes input data vectors under absolute operational necessity protocols:</p>
                <ul>
                    <li>To fulfill, log, and trace order tracking parameters onto our structural schema table registries.</li>
                    <li>To safely transmit order confirmation references bills, and invoices generation indicators.</li>
                    <li>To process customer inquiries, emails validations, and background inquiry pipeline tasks safely.</li>
                    <li>To detect potential risk or fraudulent transaction activities against internal platform layers.</li>
                </ul>

                <h4>3. Data Protection and Session Cookies Locking</h4>
                <p>We implement a strict <strong>Global Session Cookies Scope Path Locking Strategy</strong>. Cookies generated during browser reloads are locked safely to the root domain path ("/"), preventing cross-directory volatile leakages or unauthorized memory split faults when browsing nested paths like <code>/product/</code> sub-folders.</p>
                <p>Your passwords or financial authorization logs are never directly parsed or cached within local storage units. Transaction pathways operate strictly via authenticated verification standard channels.</p>

                <h4>4. Third-Party Information Interchanges</h4>
                <p>We safely share minimal data variables only with vital operational partners to execute logistics:
                    <br>• <strong>Logistics Partners:</strong> Delivery channels access names and addresses to transport packages to your specific coordinates.
                    <br>• <strong>Database Hosts:</strong> Safe data archiving within isolated hosting parameters under standard security firewalls encryption controls.
                </p>

                <h4>5. Your Data Ownership Rights</h4>
                <p>As a verified user or customer, you retain absolute privileges over your recorded metadata layers. You can request changes, manual data erasure blocks, or complete tracking restrictions from our background logs registry parameters at any moment by contacting our web administration team.</p>

                <h4>6. Contact Corporate Governance</h4>
                <p>For questions or complaints about our dynamic data processing standard sequences, feel free to connect with our administrative support cell directly:</p>
                <p>
                    <i class="bi bi-envelope-fill text-brown me-2"></i> <strong>Email Correspondence:</strong> aristonut@gmail.com<br>
                    <i class="bi bi-geo-alt-fill text-brown me-2"></i> <strong>Corporate Node:</strong> Subhankarpur, Darbhanga, Bihar - 846004
                </p>
            </div>

        </div>
    </main>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>