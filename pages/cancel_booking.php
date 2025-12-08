<?php
// customer nijer booking cancel korar script

require 'config.php';

// sudhu customer access
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$customerId = $_SESSION['user_id'] ?? null;
if (!$customerId) {
    header("Location: login.php");
    exit;
}

// booking_id GET theke ashbe
$bookingId = isset($_GET['booking_id']) ? (int) $_GET['booking_id'] : 0;

if ($bookingId <= 0) {
    // invalid id
    header("Location: my_bookings.php?cancel=invalid");
    exit;
}

// ei booking ki ei customer er? + status ki pending?
$stmt = $pdo->prepare("
    SELECT id, customer_id, status
    FROM bookings
    WHERE id = ?
      AND customer_id = ?
    LIMIT 1
");
$stmt->execute([$bookingId, $customerId]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    // booking nai / onno lok er
    header("Location: my_bookings.php?cancel=notfound");
    exit;
}

// sudhu pending booking cancel korte dibo
if ($booking['status'] !== 'pending') {
    header("Location: my_bookings.php?cancel=notallowed");
    exit;
}

// cancel korchi
$update = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
$update->execute([$bookingId]);

header("Location: my_bookings.php?cancel=success");
exit;
