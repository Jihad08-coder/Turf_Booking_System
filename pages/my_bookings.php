<?php
require 'config.php';

// sudhu customer access
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php?type=customer");
    exit;
}

$customerId = $_SESSION['user_id'] ?? null;
if (!$customerId) {
    header("Location: login.php?type=customer");
    exit;
}

// background change jodi kore thako
$pageBodyClass = 'my-bookings-bg';

// sob booking (latest first)
$stmt = $pdo->prepare("
    SELECT b.*,
           t.name     AS turf_name,
           t.location AS turf_location,
           t.sport_type
    FROM bookings b
    JOIN turfs t ON b.turf_id = t.id
    WHERE b.customer_id = ?
    ORDER BY b.booking_date DESC, b.start_time DESC
");
$stmt->execute([$customerId]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// cancel er message
$cancelMessage = '';
$cancelType    = '';

if (!empty($_GET['cancel'])) {
    switch ($_GET['cancel']) {
        case 'success':
            $cancelMessage = 'Your booking has been cancelled.';
            $cancelType    = 'success';
            break;
        case 'notfound':
            $cancelMessage = 'Booking not found or not yours.';
            $cancelType    = 'error';
            break;
        case 'notallowed':
            $cancelMessage = 'You can only cancel pending bookings.';
            $cancelType    = 'error';
            break;
        case 'invalid':
        default:
            $cancelMessage = 'Invalid booking request.';
            $cancelType    = 'error';
            break;
    }
}



include 'header.php';
?>

<link rel="stylesheet" href="css/home_style.css">


<div class="my-bookings-wrapper">

    <!-- PAGE HEADER -->
    <div class="my-bookings-header">
        <div>
            <h2 class="page-title">My Bookings</h2>
            <p class="page-subtitle">
                Here you can see all your turf bookings and cancel pending ones.
            </p>
        </div>
    </div>

    <!-- CANCEL MESSAGE -->
    <?php if ($cancelMessage): ?>
        <div class="message <?php echo $cancelType === 'success' ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($cancelMessage); ?>
        </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="booking-table-wrapper">
        <table class="booking-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Turf</th>
                    <th>Sport</th>
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
                    <td colspan="9" class="no-bookings-cell">
                        You have no bookings yet.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($bookings as $index => $b): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>

                        <td>
                            <div class="turf-cell">
                                <span class="turf-name">
                                    <?php echo htmlspecialchars($b['turf_name']); ?>
                                </span>
                                <span class="turf-location">
                                    <?php echo htmlspecialchars($b['turf_location']); ?>
                                </span>
                            </div>
                        </td>

                        <td><?php echo htmlspecialchars(ucfirst($b['sport_type'])); ?></td>

                        <td><?php echo htmlspecialchars($b['booking_date']); ?></td>

                        <td>
                            <?php
                                $timeRange = substr($b['start_time'], 0, 5) . ' - ' . substr($b['end_time'], 0, 5);
                                echo htmlspecialchars($timeRange);
                            ?>
                        </td>

                        <td>
                            ৳<?php echo htmlspecialchars(number_format($b['total_price'], 2)); ?>
                        </td>

                        <!-- status pill -->
                        <td>
                            <span class="status-pill status-<?php echo htmlspecialchars($b['status']); ?>">
                                <?php echo ucfirst(htmlspecialchars($b['status'])); ?>
                            </span>
                        </td>

                         <!-- payment pill + pay button -->
<td>
    <span class="payment-pill payment-<?php echo htmlspecialchars($b['payment_status']); ?>">
        <?php echo ucfirst(htmlspecialchars($b['payment_status'])); ?>
    </span>

    <?php if ($b['payment_status'] === 'unpaid' && $b['status'] === 'pending'): ?>
        <br>
        <a href="payment.php?booking_id=<?php echo $b['id']; ?>"
           class="btn-status btn-approve"
           style="margin-top:6px; display:inline-block; font-size:12px;">
            Pay Now
        </a>
    <?php endif; ?>
</td>

                        <!-- cancel action -->
                        <td>
                            <?php if ($b['status'] === 'pending'): ?>
                                <a href="cancel_booking.php?booking_id=<?php echo $b['id']; ?>"
                                   class="btn-status btn-reject"
                                   data-confirm="Are you sure you want to cancel this booking?">
                                    Cancel
                                </a>
                            <?php else: ?>
                                <span class="no-action">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'footer.php'; ?>
