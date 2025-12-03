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
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/home_style.css">
    <link rel="stylesheet" href="css/footer_style.css">
    <link rel="stylesheet" href="css/about_style.css">

    <!-- optional JS -->
    <script defer src="js/main.js"></script>
</head>
<body class="<?php echo htmlspecialchars($pageBodyClass); ?>">

<header class="nav">
    <!-- Left side: logo / brand -->
    <div class="nav-logo">
        <span class="logo-badge">JAJ</span>
        <span class="logo-text">Sports</span>
    </div>

    <!-- Right side: menu links -->
    <nav class="nav-links">
        <!-- public links: sobar jonno -->
        <a href="index.php">Home</a>
        <a href="about.php">About</a>

        <?php if (empty($_SESSION['role'])): ?>
            <!-- ONLY when NOT logged in -->
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Registration</a>
        <?php else: ?>
            <!-- ONLY when logged in -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" class="btn">Admin Dashboard</a>
            <?php elseif ($_SESSION['role'] === 'manager'): ?>
                <a href="manager_dashboard.php" class="btn">Manager Dashboard</a>
            <?php elseif ($_SESSION['role'] === 'customer'): ?>
                <a href="customer_dashboard.php" class="btn">Customer Dashboard</a>
            <?php endif; ?>

            <a href="logout.php" class="btn">Logout</a>
        <?php endif; ?>
    </nav>
</header>
