<?php
// database & session jodi use koro
require 'config.php';

// common header (navbar, etc.)
include 'header.php';
?>

<!-- PROFILE PAGE START -->
<link rel="stylesheet" href="css/profile_style.css">

<div class="profile-wrapper">
    <div class="profile-top">
        <div class="profile-avatar">
            <!-- ekhane tomar photo / logo rakhba -->
            <img src="images/jihad.jpg" alt="Profile Photo">
        </div>

        <div class="profile-info">
            <h2>Md. Jahid Ahamed Jihad</h2>
            <p>CSE Student & Web Developer</p>
            <p>Student of University Of liberal Arts Bangladesh</p>
            <p>Location: Dhaka, Bangladesh</p>
            <p>Email: jaj016017@gmail.com</p>
        </div>
    </div>

    <div class="profile-bio">
        <h3 class="profile-section-title">About Me</h3>
        <p>
           I am a Computer Science student passionate about full-stack web development. 
           I enjoy creating practical solutions such as turf booking systems and lightweight management tools. 
           Alongside development, I work on UI/UX design to ensure smooth user experiences.
            My technical expertise spans PHP, MySQL, HTML, CSS, Bootstrap, and version control using Git/GitHub.
        </p>
    </div>

    <div class="profile-bio">
        <h3 class="profile-section-title">Skills</h3>
        <p>
            • PHP, MySQL, HTML, CSS, Bootstrap<br>
            • Basic JavaScript, Git & GitHub<br>
            • Figma, academic reports, project documentation
        </p>
    </div>


   <div class="profile-socials">
    <!-- Facebook -->
    <a href="https://www.facebook.com/jihad.ahamed08"
       target="_blank"
       rel="noopener noreferrer"
       class="btn-fb">
        <i class="fa-brands fa-facebook-f"></i>
        Facebook
    </a>

    <!-- GitHub -->
    <a href="https://github.com/Jihad08-coder"
       target="_blank"
       rel="noopener noreferrer"
       class="btn-git">
        <i class="fa-brands fa-github"></i>
        GitHub
    </a>

    <!-- LinkedIn -->
    <a href="https://www.linkedin.com/in/jihad-ahmed-48048634a/"
       target="_blank"
       rel="noopener noreferrer"
       class="btn-li">
        <i class="fa-brands fa-linkedin-in"></i>
        LinkedIn
    </a>
</div>


    <!-- 👇 NEW: Complain / Contact box -->
    <div class="complain-box">
    <div class="complain-text">
        <h3>If any problem, please contact us</h3>
        <p>
            If you face any issue with booking, payment or your account,
            you can send us a complaint by email. We will check it as soon as possible.
        </p>
    </div>

    <a href="mailto:jaj016017@gmail.com
        ?subject=Complaint%20about%20Turf%20Booking%20System
        &body=Write%20your%20problem%20here..."
       class="complain-btn">
        Complain Box
    </a>
</div>

</div>
<!-- PROFILE PAGE END -->


<?php
// common footer
include 'footer.php';
?>
   