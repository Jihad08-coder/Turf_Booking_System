<?php
// always first: database + session
require 'config.php';

// jodi age thekei login kora thake tahole direct tar dashboard e pathai
if (!empty($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'manager') {
        header("Location: manager_dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'customer') {
        header("Location: customer_dashboard.php");
        exit;
    }
}

// jodi login na thake tahole just home page dekhabo
include 'header.php';
?>

<!-- HERO / WELCOME SECTION -->
<div class="hero">
    <h2>Welcome to JAJ Sports Arena</h2>
    <p>
       <b><h3>Your choice is our first priority!</h3></b>
    </p>
    <p>
        Start by logging in or creating a new account:
    </p>

    <div class="hero-buttons">
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="register.php" class="btn btn-secondary">Register</a>
    </div>
</div>

<!-- FEATURED TURFS PREVIEW (2 ta ground) -->
<section class="home-preview">
    <div class="home-preview-header">
        <h3>Popular Turfs in Mohammadpur</h3>
        <p>Preview our football &amp; badminton turf before you book.</p>
    </div>

    <div class="preview-grid">
        <!-- FOOTBALL CARD -->
        <article class="preview-card">
            <div class="preview-image">
                <span class="preview-badge">Football</span>
                <span class="preview-price">৳1500/hr</span>
                <img src="images/turf07.jpg" alt="Football Turf">
            </div>
            <div class="preview-body">
                <h4>Mohammadpur Football Turf</h4>
                <p class="preview-text">
                    5-a-side synthetic football turf with night lights and clean changing rooms.
                </p>
                <p class="preview-meta">
                    📍 Mohammadpur, Dhaka &nbsp; • &nbsp;
                    Price: <strong>৳1500 per hour</strong>
                </p>
                <a href="football_turf.php" class="btn btn-outline btn-small">View Details</a>
            </div>
        </article>

        <!-- BADMINTON CARD -->
        <article class="preview-card">
            <div class="preview-image">
                <span class="preview-badge">Badminton</span>
                <span class="preview-price">৳800/hr</span>
                <img src="images/turf08.jpg" alt="Badminton Court">
            </div>
            <div class="preview-body">
                <h4>Mohammadpur Badminton Court</h4>
                <p class="preview-text">
                    Indoor badminton court with proper lighting and wooden flooring.
                </p>
                <p class="preview-meta">
                    📍 Mohammadpur, Dhaka &nbsp; • &nbsp;
                    Price: <strong>৳800 per hour</strong>
                </p>
                <a href="badminton_court.php" class="btn btn-outline btn-small">View Details</a>
            </div>
        </article>
    </div>
</section>

<!-- SMALL ABOUT TEASER -->
<section class="home-about-teaser">
    <p class="home-about-title">Want to know more about us?</p>
    <p class="home-about-text">
        Learn how Turf Booking System helps players and turf owners with easy, transparent bookings.
    </p>
    <a href="about.php" class="btn btn-outline">About Us</a>
</section>

<?php include 'footer.php'; ?>
