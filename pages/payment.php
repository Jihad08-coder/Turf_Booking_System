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


