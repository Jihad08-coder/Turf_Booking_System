<?php
require 'config.php';

// only admin access
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ----- summary stats ----- //

// total users
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
// total managers
$totalManagers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'manager'")->fetchColumn();
// total customers
$totalCustomers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();

// total bookings
$totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
// pending bookings
$pendingBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();

include 'header.php';
?>

<link rel="stylesheet" href="css/customer_dashboard_style.css">


<div class="admin-wrapper">

    <!-- HERO / HEADER -->
    <section class="admin-hero-card">
        <div class="admin-hero-left">
            <p class="hero-kicker">Dashboard</p>
            <h2 class="hero-title">Admin Control Panel</h2>
            <p class="hero-welcome">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> 👑
            </p>
            <p class="hero-subtitle">
                Monitor all turf bookings, manage users &amp; managers and keep the system running smoothly.
            </p>
        </div>

        <div class="admin-hero-right">
            <div class="hero-stat">
                <span class="hero-stat-label">Total Users</span>
                <span class="hero-stat-value"><?php echo $totalUsers; ?></span>
                <span class="hero-stat-extra">
                    Managers: <?php echo $totalManagers; ?> · Customers: <?php echo $totalCustomers; ?>
                </span>
            </div>
            <div class="hero-stat hero-stat-alt">
                <span class="hero-stat-label">Total Bookings</span>
                <span class="hero-stat-value"><?php echo $totalBookings; ?></span>
                <span class="hero-stat-extra">
                    Pending: <?php echo $pendingBookings; ?>
                </span>
            </div>
        </div>
    </section>

    <!-- SUMMARY CARDS (re-use dashboard styles) -->
    <section class="dashboard-grid admin-summary-grid">
        <div class="dashboard-card">
            <span class="card-label">Total Users</span>
            <span class="card-value"><?php echo $totalUsers; ?></span>
            <span class="card-extra">
                Managers: <?php echo $totalManagers; ?> · Customers: <?php echo $totalCustomers; ?>
            </span>
        </div>
        <div class="dashboard-card">
            <span class="card-label">Total Bookings</span>
            <span class="card-value"><?php echo $totalBookings; ?></span>
            <span class="card-extra">Pending: <?php echo $pendingBookings; ?></span>
        </div>
    </section>

    <!-- MAIN ACTION CARDS -->
    <section class="admin-cards">
        <a href="manager_bookings.php" class="admin-card">
            <div class="admin-card-badge">Bookings</div>
            <h3>View All Booking List</h3>
            <p>
                See every turf booking across football &amp; badminton courts.
                Approve, reject or complete from the manager bookings page.
            </p>
            <span class="admin-card-cta">Open booking list →</span>
        </a>

        <a href="admin_users.php" class="admin-card">
            <div class="admin-card-badge admin-card-badge-green">Users</div>
            <h3>Manage Users &amp; Managers</h3>
            <p>
                Promote customers to manager, add new managers, update roles
                and remove inactive accounts.
            </p>
            <span class="admin-card-cta">Manage users →</span>
        </a>

        <!-- optional future feature card -->
        <div class="admin-card admin-card-disabled">
            <div class="admin-card-badge admin-card-badge-muted">Coming soon</div>
            <h3>Reports &amp; Analytics</h3>
            <p>
                View monthly booking trends, top used turfs and revenue overview.
            </p>
            <span class="admin-card-cta">Available in next version</span>
        </div>
    </section>

    <!-- SMALL INFO BLOCK -->
    <section class="admin-lists">
        <div class="admin-list-block">
            <div class="section-header">
                <p class="section-subtitle">
                    Tip: Regularly review pending bookings and user roles to keep the platform smooth
                    for both players and managers.
                </p>
            </div>
        </div>
    </section>

</div>

<?php include 'footer.php'; ?>

