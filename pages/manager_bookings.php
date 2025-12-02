<?php
require 'config.php';

// only manager or admin
if (empty($_SESSION['role']) || !in_array($_SESSION['role'], ['manager', 'admin'], true)) {
    header("Location: login.php?type=manager");
    exit;
}

$flashMessage = '';

// ----- handle status change (Approve / Reject / Completed) ----- //
if (isset($_GET['booking_id'], $_GET['action'])) {
    $bookingId = (int) $_GET['booking_id'];
    $action    = $_GET['action'];

    if (in_array($action, ['approved', 'rejected', 'completed', 'cancelled'], true)) {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$action, $bookingId]);
        $flashMessage = "Booking status updated to " . htmlspecialchars($action) . ".";
    }

    // reload page after update
    header("Location: manager_bookings.php");
    exit;
}

// ----- load all bookings ----- //
$stmt = $pdo->query("
    SELECT b.*,
           t.name  AS turf_name,
           u.name  AS customer_name,
           u.phone AS customer_phone
    FROM bookings b
    JOIN turfs  t ON b.turf_id = t.id
    JOIN users  u ON b.customer_id = u.id
    ORDER BY b.booking_date DESC, b.start_time DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// header
include 'header.php';
?>

<link rel="stylesheet" href="css/manager_dashboard_style.css">

<div>
<h2>Manage Bookings</h2>
<p>View all turf bookings and update their status.</p>

<p class="booking-toolbar">
    <!-- ei button diye date window update page e jabe -->
    <a href="update_booking_status.php" class="btn">Booking Date Update</a>
</p>

</div>

<?php if ($flashMessage): ?>
    <div class="message success"><?php echo htmlspecialchars($flashMessage); ?></div>
<?php endif; ?>

<div class="booking-table-wrapper">
    <table class="booking-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Turf</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Time</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($bookings)): ?>
            <tr>
                <td colspan="10" style="text-align:center;">No bookings yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($bookings as $index => $b): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($b['turf_name']); ?></td>
                    <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($b['customer_phone']); ?></td>
                    <td><?php echo htmlspecialchars($b['booking_date']); ?></td>
                    <td>
                        <?php
                            $timeRange = substr($b['start_time'], 0, 5) . ' - ' . substr($b['end_time'], 0, 5);
                            echo htmlspecialchars($timeRange);
                        ?>
                    </td>
                    <td>৳<?php echo htmlspecialchars(number_format($b['total_price'], 2)); ?></td>

                    <!-- colourful status -->
                    <td>
                        <span class="status-pill status-<?php echo htmlspecialchars($b['status']); ?>">
                            <?php echo ucfirst(htmlspecialchars($b['status'])); ?>
                        </span>
                    </td>

                    <!-- colourful payment -->
                    <td>
                        <span class="payment-pill payment-<?php echo htmlspecialchars($b['payment_status']); ?>">
                            <?php echo ucfirst(htmlspecialchars($b['payment_status'])); ?>
                        </span>
                    </td>

                    <!-- action buttons -->
                    <td>
                        <?php if ($b['status'] === 'pending'): ?>
                            <a href="manager_bookings.php?booking_id=<?php echo $b['id']; ?>&action=approved"
                               class="btn-status btn-approve">Approve</a>
                            <a href="manager_bookings.php?booking_id=<?php echo $b['id']; ?>&action=rejected"
                               class="btn-status btn-reject">Reject</a>
                        <?php elseif ($b['status'] === 'approved'): ?>
                            <a href="manager_bookings.php?booking_id=<?php echo $b['id']; ?>&action=completed"
                               class="btn-status btn-complete">Mark Completed</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
