<?php
// শুধু About পেজের জন্য আলাদা body class (background image change)
$pageBodyClass = 'about-bg';

include 'header.php';
?>

<div class="about-page">

    <!-- HERO SECTION -->
    <section class="about-hero about-reveal">
        <p class="about-kicker">About Turf Booking System</p>
        <h1>Play, Connect &amp; Manage Turfs in One Place</h1>
        <p class="about-lead">
            Turf Booking System makes it easy for players to discover football &amp; badminton turfs,
            book slots online and avoid phone calls &amp; confusion. Managers and owners get a clean
            dashboard to handle bookings in real time.
        </p>

        <div class="about-hero-tags">
            <span>⚽ Football Turf</span>
            <span>🏸 Badminton Court</span>
            <span>📅 Smart Time Slots</span>
            <span>💳 Easy Payment Status</span>
        </div>
    </section>

    <!-- STORY + OFFER SECTION -->
    <section class="about-panels">
        <!-- Our Story -->
        <div class="about-panel about-panel-story about-reveal">
            <h2>Our Story</h2>
            <p>
                We started this project to solve a simple problem: finding an available turf at
                the right time without twenty phone calls. From student tournaments to weekend
                friendlies, we wanted one place where everyone can see free slots, book online
                and get instant confirmation.
            </p>
            <p>
                Today the system brings players, turf managers and ground owners on the same
                platform so that every slot is used better and every game starts on time.
            </p>
        </div>

        <!-- Two column: Players + Owners -->
        <div class="about-two-column">
            <!-- For Players -->
            <div class="about-panel about-panel-players about-reveal">
                <h2>What We Offer for Players?</h2>
                <ul>
                    <li>Real-time view of available slots for football &amp; badminton.</li>
                    <li>Clear price per hour – no hidden charges or surprises.</li>
                    <li>Booking history &amp; status (pending, approved, cancelled) in one place.</li>
                    <li>Easy cancellation so empty slots can be re-used by other teams.</li>
                </ul>
            </div>

            <!-- For Turf Owners -->
            <div class="about-panel about-panel-owners about-reveal">
                <h2>What We Offer for Turf Owners?</h2>
                <ul>
                    <li>Central dashboard to see all bookings for every day.</li>
                    <li>Approve / reject requests in one click – no messy notebooks.</li>
                    <li>Better slot utilisation with clear time windows and status.</li>
                    <li>Detailed booking record to understand peak hours &amp; revenue.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- VALUES / HIGHLIGHTS -->
    <section class="about-highlight about-reveal">
        <h3>Why Teams Love Our Platform!</h3>
        <ul>
            <li>
                <span class="about-icon">✅</span>
                <span>Fast, mobile-friendly UI – works great on laptop and phone.</span>
            </li>
            <li>
                <span class="about-icon">✅</span>
                <span>Clean role system for Admin, Manager &amp; Customer.</span>
            </li>
            <li>
                <span class="about-icon">✅</span>
                <span>Transparent booking status so no double bookings or confusion.</span>
            </li>
            <li>
                <span class="about-icon">✅</span>
                <span>Customisable for new sports, new turfs and future features.</span>
            </li>
        </ul>
    </section>

</div>

<?php include 'footer.php'; ?>
