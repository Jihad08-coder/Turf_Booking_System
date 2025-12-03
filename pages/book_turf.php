<?php
require 'config.php';

// only customer access
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$customerId = $_SESSION['user_id'] ?? null;
if (!$customerId) {
    header("Location: login.php");
    exit;
}

// turf id from query
$turf_id = $_GET['turf_id'] ?? null;
if (!$turf_id) {
    // jodi turf_id na thake tahole customer dashboard e pathai
    header("Location: customer_dashboard.php");
    exit;
}

// turf info
$stmt = $pdo->prepare("SELECT * FROM turfs WHERE id = ?");
$stmt->execute([$turf_id]);
$turf = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$turf) {
    die("Turf not found!");
}

// --- Booking window (10 days or DB-driven) --- //
$bwStmt = $pdo->query("SELECT start_date, days_to_show FROM booking_window ORDER BY id DESC LIMIT 1");
$window = $bwStmt->fetch(PDO::FETCH_ASSOC);

if ($window) {
    $startDate  = $window['start_date'];
    $daysToShow = (int)$window['days_to_show'];
    if ($daysToShow <= 0) {
        $daysToShow = 10;
    }
} else {
    $startDate  = date('Y-m-d');
    $daysToShow = 10;
}

// build dates array
$dates = [];
$startTs = strtotime($startDate);
for ($i = 0; $i < $daysToShow; $i++) {
    $dates[] = date('Y-m-d', strtotime("+$i day", $startTs));
}
$endDate = end($dates);

// define time slots (2-hour blocks)
$slots = [
    ['label' => '9 am to 11 am',  'start' => '09:00:00', 'end' => '11:00:00'],
    ['label' => '11 am to 1 pm', 'start' => '11:00:00', 'end' => '13:00:00'],
    ['label' => '1 pm to 3 pm',  'start' => '13:00:00', 'end' => '15:00:00'],
    ['label' => '3 pm to 5 pm',  'start' => '15:00:00', 'end' => '17:00:00'],
    ['label' => '5 pm to 7 pm',  'start' => '17:00:00', 'end' => '19:00:00'],
    ['label' => '7 pm to 9 pm',  'start' => '19:00:00', 'end' => '21:00:00'],
];

// message variables
$message = '';
$success = false;

// ---- HANDLE BOOK REQUEST (POST) ---- //
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_date = $_POST['booking_date'] ?? '';
    $start_time   = $_POST['start_time'] ?? '';
    $end_time     = $_POST['end_time'] ?? '';

    // basic validation
    if (!$booking_date || !$start_time || !$end_time) {
        $message = "Please select a valid date and time slot.";
    } else {
        $startTsSlot = strtotime($start_time);
        $endTsSlot   = strtotime($end_time);

        if ($endTsSlot <= $startTsSlot) {
            $message = "End time must be greater than start time!";
        } else {
            // check: ei slot already booked / pending / completed naki?
            $check = $pdo->prepare("
                SELECT COUNT(*) 
                FROM bookings
                WHERE turf_id = ?
                  AND booking_date = ?
                  AND start_time = ?
                  AND end_time = ?
                  AND status IN ('pending','approved','completed')
            ");
            $check->execute([$turf_id, $booking_date, $start_time, $end_time]);
            $count = (int)$check->fetchColumn();

            if ($count > 0) {
                $message = "This slot is already booked or pending.";
            } else {
                // calculate total price (hours * price_per_hour)
                $hours = ($endTsSlot - $startTsSlot) / 3600;
                if ($hours <= 0) {
                    $message = "Invalid slot duration.";
                } else {
                    $total_price = $hours * $turf['price_per_hour'];

                    $ins = $pdo->prepare("
                        INSERT INTO bookings (turf_id, customer_id, booking_date, start_time, end_time, total_price)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $ins->execute([
                        $turf_id,
                        $customerId,
                        $booking_date,
                        $start_time,
                        $end_time,
                        $total_price
                    ]);

                    $success = true;
                    $message = "Booking request submitted! Status: pending.";
                }
            }
        }
    }
}

// ---- FETCH BOOKINGS TO BUILD SLOT MAP (AFTER POSSIBLE INSERT) ---- //
$bookedSlots = [];

$bookStmt = $pdo->prepare("
    SELECT id, customer_id, booking_date, start_time, end_time, status
    FROM bookings
    WHERE turf_id = ?
      AND booking_date BETWEEN ? AND ?
      AND status IN ('pending','approved','completed')
");
$bookStmt->execute([$turf_id, $startDate, $endDate]);
while ($row = $bookStmt->fetch(PDO::FETCH_ASSOC)) {
    $key = $row['booking_date'] . '|' . $row['start_time'] . '|' . $row['end_time'];
    $bookedSlots[$key] = $row;
}



// turf info
$stmt = $pdo->prepare("SELECT * FROM turfs WHERE id = ?");
$stmt->execute([$turf_id]);
$turf = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$turf) {
    die("Turf not found!");
}

// ---- BODY BACKGROUND CLASS (dynamic) ---- //
$bgClass = 'booking-bg-default';

if (!empty($turf['sport_type'])) {
    if ($turf['sport_type'] === 'football') {
        $bgClass = 'booking-bg-football';
    } elseif ($turf['sport_type'] === 'badminton') {
        $bgClass = 'booking-bg-badminton';
    }
}

// ei page er jonno body class set
$pageBodyClass = $bgClass;

// --- Booking window (10 days or DB-driven) --- //
$bwStmt = $pdo->query("SELECT start_date, days_to_show FROM booking_window ORDER BY id DESC LIMIT 1");




// include header
// include header
include 'header.php';
?>



<div class="booking-wrapper">

    <!-- TOP HEADER / INFO -->
    <section class="booking-header">
        <div class="booking-header-left">
            <p class="booking-kicker">Turf Booking</p>
            <h2 class="booking-title">
                <?php echo htmlspecialchars($turf['name']); ?>
            </h2>
            <p class="booking-meta">
                📍 <?php echo htmlspecialchars($turf['location']); ?>
                &nbsp; • &nbsp;
                Price per hour: <strong>৳<?php echo htmlspecialchars($turf['price_per_hour']); ?></strong>
            </p>
            <p class="booking-sub">
                Choose a suitable date and time slot below. Each block represents a 2-hour session.
            </p>
        </div>

        <div class="booking-header-right">
            <div class="booking-window">
                <span class="window-label">Booking Window</span>
                <span class="window-range">
                    <?php echo htmlspecialchars($startDate); ?> &nbsp;–&nbsp;
                    <?php echo htmlspecialchars($endDate); ?>
                </span>
            </div>

            <ul class="booking-points">
                <li>Green slots are free to book.</li>
                <li>Yellow = pending approval from manager.</li>
                <li>Red = already booked / completed.</li>
            </ul>
        </div>
    </section>

    <!-- MESSAGE -->
    <?php if ($message): ?>
        <div class="message <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- LEGEND -->
    <section class="slot-legend">
        <div class="legend-item">
            <span class="legend-color legend-free"></span>
            <span>Available</span>
        </div>
        <div class="legend-item">
            <span class="legend-color legend-pending"></span>
            <span>Pending</span>
        </div>
        <div class="legend-item">
            <span class="legend-color legend-booked"></span>
            <span>Booked</span>
        </div>
        <div class="legend-item">
            <span class="legend-color legend-my-pending"></span>
            <span>My Pending Booking</span>
        </div>
    </section>

    <!-- SLOT TABLE -->
    <section class="slot-section">
        <h3 class="slot-title">Select a Time Slot</h3>

        <div class="slot-table-wrapper">
            <table class="slot-table">
                <tr>
                    <th>Time \\ Date</th>
                    <?php foreach ($dates as $date): ?>
                        <th>
                            <?php $dayName = date('D', strtotime($date)); ?>
                            <?php echo htmlspecialchars($dayName); ?><br>
                            <?php echo htmlspecialchars($date); ?>
                        </th>
                    <?php endforeach; ?>
                </tr>

                <?php foreach ($slots as $slot): ?>
                    <tr>
                        <td class="slot-time-label">
                            <?php echo htmlspecialchars($slot['label']); ?>
                        </td>

                        <?php foreach ($dates as $date): ?>
                            <?php
                                $dbDate   = $date;
                                $key      = $dbDate . '|' . $slot['start'] . '|' . $slot['end'];
                                $slotInfo = $bookedSlots[$key] ?? null;
                            ?>

                            <?php if ($slotInfo): ?>
                                <?php
                                    $status    = $slotInfo['status'];
                                    $isOwn     = ((int)$slotInfo['customer_id'] === (int)$customerId);
                                    $bookingId = (int)$slotInfo['id'];
                                ?>

                                <?php if ($status === 'pending'): ?>

                                    <?php if ($isOwn): ?>
                                        <!-- amar nijer pending booking -->
                                        <td class="slot-cell slot-pending slot-own">
                                            <span class="slot-label">Pending</span>
                                            <?php 
                                                $cancelUrl = 'cancel_booking.php?booking_id=' . urlencode($bookingId);
                                            ?>
                                            <a href="<?php echo htmlspecialchars($cancelUrl); ?>"
                                               class="slot-cancel-link"
                                               data-confirm="Are you sure you want to cancel this booking?">
                                                Cancel
                                            </a>
                                        </td>
                                    <?php else: ?>
                                        <!-- onnoder pending booking -->
                                        <td class="slot-cell slot-pending">
                                            <span class="slot-label">Pending</span>
                                        </td>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <!-- approved / completed -->
                                    <td class="slot-cell slot-booked">
                                        <span class="slot-label">Booked</span>
                                    </td>
                                <?php endif; ?>

                            <?php else: ?>
                                <!-- free slot -->
                                <td class="slot-cell slot-available">
                                    <form method="post" class="slot-form">
                                        <input type="hidden" name="booking_date" value="<?php echo htmlspecialchars($dbDate); ?>">
                                        <input type="hidden" name="start_time"   value="<?php echo htmlspecialchars($slot['start']); ?>">
                                        <input type="hidden" name="end_time"     value="<?php echo htmlspecialchars($slot['end']); ?>">
                                        <button type="submit" class="slot-book-btn">
                                            Book
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>

</div>

<?php include 'footer.php'; ?>

