<?php
// always first: database + session
require 'config.php';

// jodi login na thake tahole just home page dekhabo
include 'header.php';
?>


<main class="home-wrapper">

    <!-- HERO / WELCOME SECTION -->
    <section class="hero">
        <div class="hero-content">
            <p class="hero-badge">Easy &amp; Fast Online Turf Booking</p>

            <h1 class="hero-title">
              <span> Welcome to JAJ Sports Arena</span>
            </h1>

            <h3 class="hero-subtitle">
                Your choice is our first priority!
            </h3>

            <p class="hero-text">
                Book football turfs and badminton courts in Mohammadpur within seconds.
                Choose your slot, confirm online &amp; just come and play.
            </p>

            <div class="hero-buttons">
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="register.php" class="btn btn-secondary">Create Account</a>
            </div>

            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">10+</span>
                    <span class="stat-label">Daily Bookings</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">2</span>
                    <span class="stat-label">Active Turfs</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Secure Booking</span>
                </div>
            </div>
        </div>

        <div class="hero-image">
            <div class="hero-image-card">
                <img src="images/turf07.jpg" alt="Football Turf">
                <span class="hero-image-tag">Mohammadpur Football Turf</span>
            </div>
        </div>
    </section>

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
                    <a href="football_turf.php" class="btn btn-small">View Details</a>
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
                    <a href="badminton_court.php" class="btn btn-small">View Details</a>
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
</main>

<?php include 'footer.php'; ?>
