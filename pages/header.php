<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// jodi kono page $pageBodyClass set kore, seta use korbo
// na korle empty class thakbe
$pageBodyClass = $pageBodyClass ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Turf Booking System</title>

    <!-- CSS link -->
    <link rel="stylesheet" href="css/style.css">

    <!-- optional JS -->
    <script defer src="js/main.js"></script>
</head>
<body class="<?php echo htmlspecialchars($pageBodyClass); ?>">
<div class="nav">
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>  <!-- About page link -->
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>


    <?php if (!empty($_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
        <?php elseif ($_SESSION['role'] === 'manager'): ?>
            <a href="manager_dashboard.php">Manager Dashboard</a>
        <?php elseif ($_SESSION['role'] === 'customer'): ?>
            <a href="customer_dashboard.php">Customer Dashboard</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    <?php endif; ?>
</div>

<div class="container">
