<?php
require 'config.php';
$pageBodyClass = '';
include 'header.php';
?>

<section class="turf-detail">
    <div class="turf-detail-card">
        <div class="turf-detail-image">
            <img src="images/turf08.jpg" alt="Mohammadpur Badminton Court">
        </div>
        <div class="turf-detail-body">
            <h2>Mohammadpur Badminton Court</h2>
            <p class="turf-detail-meta">
                📍 Mohammadpur, Dhaka &nbsp; • &nbsp;
                <strong>৳800 per hour</strong>
            </p>
            <p class="turf-detail-text">
                Indoor badminton court with wooden flooring, proper lighting and a friendly
                environment for both practice and friendly matches.
            </p>

            <div class="turf-detail-actions">
                <a href="login.php" class="btn btn-small">Login to Book</a>
                <a href="index.php" class="btn btn-small">Back to Home</a>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
