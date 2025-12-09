<?php
require 'config.php';

// only customer access
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php?type=customer");
    exit;
}

$customerId = $_SESSION['user_id'] ?? null;
if (!$customerId) {
    header("Location: login.php?type=customer");
    exit;
}

$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
if ($bookingId <= 0) {
    die('Invalid booking id.');
}

// booking load
$stmt = $pdo->prepare("
    SELECT b.*, t.name AS turf_name, t.location AS turf_location
    FROM bookings b
    JOIN turfs t ON b.turf_id = t.id
    WHERE b.id = ? AND b.customer_id = ?
    LIMIT 1
");
$stmt->execute([$bookingId, $customerId]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die('Booking not found or not yours.');
}

// already paid → redirect
if ($booking['payment_status'] === 'paid') {
    header("Location: my_bookings.php?payment=success");
    exit;
}

$error = '';
$success = false;

// form handle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobileNumber = trim($_POST['mobile_number'] ?? '');
    $amount       = (float)($_POST['amount'] ?? 0);
    $password     = $_POST['password'] ?? '';

    if ($mobileNumber === '' || $password === '') {
        $error = 'Please fill all required fields.';
    } elseif ($amount < (float)$booking['total_price']) {
        $error = 'Amount must be at least the booking total price.';
    } else {

        $stmtUser = $pdo->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        $stmtUser->execute([$customerId]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Password is incorrect.';
        } else {

            $upd = $pdo->prepare("
                UPDATE bookings
                SET payment_status = 'paid'
                WHERE id = ? AND customer_id = ?
            ");
            $upd->execute([$bookingId, $customerId]);

            header("Location: my_bookings.php?payment=success");
            exit;
        }
    }
}

$pageBodyClass = 'page-payment';
include 'header.php';
?>

<!-- ===========================
     PAYMENT PAGE WRAPPER
=========================== -->

<div class="payment-wrapper">

    <?php if ($error): ?>
        <div class="payment-msg error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <h2 class="payment-title">Payment</h2>

    <div class="payment-summary">
        You are paying for:
        <strong><?php echo htmlspecialchars($booking['turf_name']); ?></strong><br>
        Date: <?php echo htmlspecialchars($booking['booking_date']); ?><br>
        Time:
        <?php
            $timeRange = substr($booking['start_time'], 0, 5) . ' - ' . substr($booking['end_time'], 0, 5);
            echo htmlspecialchars($timeRange);
        ?><br>
        Total Amount:
        <strong>৳<?php echo htmlspecialchars(number_format($booking['total_price'], 2)); ?></strong>
    </div>

    <form method="post" class="payment-form">

        <label>Mobile Number (Dummy)</label>
        <input type="text" name="mobile_number" placeholder="01XXXXXXXXX" required>

        <label>Amount (৳)</label>
        <input type="number" name="amount"
               value="<?php echo htmlspecialchars($booking['total_price']); ?>" required>

        <label>Your Account Password</label>
        <input type="password" name="password" placeholder="Enter your login password" required>

        <button type="submit" class="pay-btn">Pay Now</button>
    </form>

</div>

<?php include 'footer.php'; ?>
