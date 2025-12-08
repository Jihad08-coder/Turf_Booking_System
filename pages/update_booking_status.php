// ----- update booking ----- //

<?php
require 'config.php';

// only manager/admin can update booking window
if (empty($_SESSION['role']) || !in_array($_SESSION['role'], ['manager', 'admin'], true)) {
    header("Location: login.php?type=manager");
    exit;
}

$message = '';

// ----- handle form submit ----- //
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date   = $_POST['start_date']   ?? '';
    $days_to_show = (int)($_POST['days_to_show'] ?? 10);

    $d = DateTime::createFromFormat('Y-m-d', $start_date);

    if (!($d && $d->format('Y-m-d') === $start_date)) {
        $message = 'Please choose a valid start date.';
    } elseif ($days_to_show < 1 || $days_to_show > 60) {
        $message = 'Days to show must be between 1 and 60.';
    } else {
        // insert or update single row (id = 1)
        $stmt = $pdo->prepare("
            INSERT INTO booking_window (id, start_date, days_to_show)
            VALUES (1, ?, ?)
            ON DUPLICATE KEY UPDATE
                start_date   = VALUES(start_date),
                days_to_show = VALUES(days_to_show)
        ");
        $stmt->execute([$start_date, $days_to_show]);
        $message = 'Booking date window updated successfully.';
    }
}

// ----- read current window ----- //
$stmt = $pdo->query("SELECT start_date, days_to_show FROM booking_window WHERE id = 1");
$current = $stmt->fetch(PDO::FETCH_ASSOC);

$current_start = $current['start_date']  ?? date('Y-m-d');
$current_days  = $current['days_to_show'] ?? 10;

// header
include 'header.php';
?>

<link rel="stylesheet" href="css/manager_dashboard_style.css">


<div class="window-update-wrapper">

    <!-- HEADER / TITLE -->
    <div class="window-update-header">
        <h2 class="window-title">Booking Date Window</h2>
        <p class="window-subtitle">
            Set from which date & how many days customers can view available booking slots.
        </p>
    </div>

    <!-- SUCCESS / ERROR MESSAGE -->
    <?php if ($message): ?>
        <div class="message success window-msg">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- FORM CARD -->
    <div class="window-update-card">
        <form method="post" class="window-form">

            <div class="form-group">
                <label>Start Date for Booking Slots</label>
                <input type="date" name="start_date"
                       value="<?php echo htmlspecialchars($current_start); ?>" required>
            </div>

            <div class="form-group">
                <label>Number of Days to Show (1–60)</label>
                <input type="number" name="days_to_show" min="1" max="60"
                       value="<?php echo htmlspecialchars($current_days); ?>" required>
            </div>

            <button type="submit" class="btn window-save-btn">
                Save Booking Window
            </button>

        </form>
    </div>

</div>

<?php include 'footer.php'; ?>

