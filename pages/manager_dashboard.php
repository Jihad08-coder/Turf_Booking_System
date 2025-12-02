<?php
require 'config.php';

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php?type=manager");
    exit;
}
include 'header.php';
?>

<link rel="stylesheet" href="css/manager_dashboard_style.css">

<div class="manager-wrapper">

    <!-- HERO / HEADER -->
    <section class="manager-hero-card">
        <div class="manager-hero-left">
            <p class="hero-kicker">Dashboard</p>
            <h2 class="hero-title">Manager Panel</h2>
            <p class="hero-welcome">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> 👋
            </p>
            <p class="hero-subtitle">
                Review turf booking requests, approve or reject them and keep your schedule organised.
            </p>
        </div>

        <div class="manager-hero-right">
            <div class="hero-stat">
                <span class="hero-stat-label">Role</span>
                <span class="hero-stat-value">Manager</span>
            </div>
            <div class="hero-tip">
                Tip: Check booking requests regularly so players get quick confirmation.
            </div>
        </div>
    </section>

    <!-- MAIN ACTION CARDS -->
    <section class="manager-actions">
        <a href="manager_bookings.php"
           class="manager-card"
           id="viewRequestsCard">
            <div class="manager-card-badge">New</div>
            <h3>View Booking Requests</h3>
            <p>
                See all pending, approved and completed bookings
                for your football &amp; badminton turfs in one place.
            </p>
            <span class="manager-card-cta">Open requests →</span>
        </a>

        <!-- Future feature placeholder (no link) -->
        <div class="manager-card manager-card-disabled">
            <div class="manager-card-badge muted">Coming soon</div>
            <h3>Analytics &amp; Reports</h3>
            <p>
                View peak hours, most used turfs and monthly booking summary.
            </p>
            <span class="manager-card-cta">Available in next version</span>
        </div>
    </section>

</div>

<?php include 'footer.php'; ?>
