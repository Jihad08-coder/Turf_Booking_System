<?php
require 'config.php';

// only customer access
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$customerId = $_SESSION['user_id'];

// ----- Summary stats ----- //

// total bookings
$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE customer_id = ?");
$stmtTotal->execute([$customerId]);
$totalBookings = (int)$stmtTotal->fetchColumn();

// upcoming (today theke future, status pending/approved)
$stmtUpcomingCount = $pdo->prepare("
    SELECT COUNT(*) FROM bookings
    WHERE customer_id = ?
      AND booking_date >= CURDATE()
      AND status IN ('pending','approved')
");
$stmtUpcomingCount->execute([$customerId]);
$upcomingCount = (int)$stmtUpcomingCount->fetchColumn();

// completed
$stmtCompleted = $pdo->prepare("
    SELECT COUNT(*) FROM bookings
    WHERE customer_id = ? AND status = 'completed'
");
$stmtCompleted->execute([$customerId]);
$completedCount = (int)$stmtCompleted->fetchColumn();

// shortcut turfs (Football + Badminton) – 2 ta active turf niye nibo
$shortcutTurfs = $pdo->query("
    SELECT id, name
    FROM turfs
    WHERE status = 'active'
    ORDER BY name
    LIMIT 2
")->fetchAll(PDO::FETCH_ASSOC);

// ----- Upcoming bookings list (latest 5) ----- //
$stmtUpcoming = $pdo->prepare("
    SELECT b.*, t.name AS turf_name
    FROM bookings b
    JOIN turfs t ON b.turf_id = t.id
    WHERE b.customer_id = ?
      AND b.booking_date >= CURDATE()
    ORDER BY b.booking_date, b.start_time
    LIMIT 5
");
$stmtUpcoming->execute([$customerId]);
$upcomingBookings = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

// include header (nav)
include 'header.php';
?>

<link rel="stylesheet" href="css/customer_dashboard_style.css">

<div class="dashboard-wrapper">

    <!-- TOP HERO BANNER -->
    <div class="dashboard-hero">
        <div class="dashboard-hero-image">
            <img src="images/turf01.jpg" alt="Turf banner" class="page-banner">
        </div>
        <div class="dashboard-hero-overlay">
            <h2>Customer Dashboard</h2>
            <p class="dashboard-welcome">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> 👋
            </p>
            <p class="dashboard-sub">
                Manage your bookings, check upcoming matches and book your favourite turf in seconds.
            </p>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <section class="dashboard-section">
        <h3 class="section-title">Overview</h3>
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <span class="card-label">Total Bookings</span>
                <span class="card-value"><?php echo $totalBookings; ?></span>
            </div>
            <div class="dashboard-card">
                <span class="card-label">Upcoming</span>
                <span class="card-value"><?php echo $upcomingCount; ?></span>
            </div>
            <div class="dashboard-card">
                <span class="card-label">Completed</span>
                <span class="card-value"><?php echo $completedCount; ?></span>
            </div>
        </div>
    </section>

    <!-- QUICK ACTIONS -->
    <section class="dashboard-section">
        <h3 class="section-title">Quick Actions</h3>
        <p class="dashboard-actions">
            <?php if (!empty($shortcutTurfs)): ?>
                <?php foreach ($shortcutTurfs as $t): ?>
                    <a href="book_turf.php?turf_id=<?php echo (int)$t['id']; ?>"
                       class="btn btn-primary btn-turf">
                        <?php echo htmlspecialchars($t['name']); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <a href="my_bookings.php" class="btn btn-turf">
                View All My Bookings
            </a>
        </p>
    </section>

    <!-- UPCOMING BOOKINGS TABLE -->
    <section class="dashboard-section">
        <h3 class="section-title">Upcoming Bookings</h3>

        <?php if (empty($upcomingBookings)): ?>
            <p class="no-bookings">
                You have no upcoming bookings.
                <a href="turfs_list.php">Book your first turf now</a> 🎯
            </p>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Turf</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($upcomingBookings as $b): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['turf_name']); ?></td>
                            <td><?php echo htmlspecialchars($b['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars($b['start_time'] . ' - ' . $b['end_time']); ?></td>
                            <td>৳<?php echo htmlspecialchars($b['total_price']); ?></td>
                            <td>
                                <span class="badge badge-status-<?php echo htmlspecialchars($b['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($b['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-payment-<?php echo htmlspecialchars($b['payment_status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($b['payment_status'])); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

</div>

<?php include 'footer.php'; ?>
