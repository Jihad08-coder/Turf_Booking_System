<?php
require 'config.php';
$pageBodyClass = '';
include 'header.php';
?>

<section class="turf-detail">
    <div class="turf-detail-card">
        <div class="turf-detail-image">
            <img src="images/turf07.jpg" alt="Mohammadpur Football Turf">
        </div>
        <div class="turf-detail-body">
            <h2>Mohammadpur Football Turf</h2>
            <p class="turf-detail-meta">
                📍 Mohammadpur, Dhaka &nbsp; • &nbsp;
                <strong>৳1500 per hour</strong>
            </p>
            <p class="turf-detail-text">
                Full-size synthetic football turf suitable for 5-a-side and 7-a-side matches.
                Night lighting, boundary net and clean changing rooms are available.
            </p>

            <div class="turf-detail-actions">
                <a href="login.php" class="btn btn-small">Login to Book</a>
                <a href="index.php" class="btn btn-small">Back to Home</a>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
