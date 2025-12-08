<?php
require 'config.php';
include 'header.php';
?>

<link rel="stylesheet" href="css/login_style.css">

<div class="login-wrapper">
    <div class="login-card">

        <h2 class="login-title">Forgot Password</h2>
        <p class="auth-subtitle">
            Enter the email address you used to register. We’ll send you a password reset link.
        </p>

        <form id="forgotForm" class="login-form" novalidate>
            <label>Email</label>
            <input type="email" id="resetEmail" name="email" required>

            <button type="submit" class="btn btn-primary login-btn">
                Send Reset Link
            </button>
        </form>

        <p id="fp-message" class="auth-message" style="margin-top:10px;"></p>

        <p class="auth-switch-text" style="margin-top:10px;">
            Remembered your password? <a href="login.php">Back to login</a>
        </p>

    </div>
</div>

<?php include 'footer.php'; ?>

<!-- 🔥 Firebase reset link sender -->
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
    import { getAuth, sendPasswordResetEmail } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";

    // ✅ EXACT config from Project settings (console theke copy kora)
    const firebaseConfig = {
      apiKey: "AIzaSyAKkG2G19rp6dbmfLO8onc2Hi_lSGDDpwo",
      authDomain: "turf-booking-814c7.firebaseapp.com",
      projectId: "turf-booking-814c7",
      storageBucket: "turf-booking-814c7.firebasestorage.app",
      messagingSenderId: "81682398292",
      appId: "1:81682398292:web:c1213c074dfca971fd41c1"
    };

    const app  = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    const form    = document.getElementById('forgotForm');
    const emailEl = document.getElementById('resetEmail');
    const msgEl   = document.getElementById('fp-message');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = emailEl.value.trim();
        msgEl.textContent = "";

        if (!email) {
            msgEl.textContent = "Please enter your email address.";
            msgEl.style.color = "red";
            return;
        }

        try {
            await sendPasswordResetEmail(auth, email);
            msgEl.textContent = "Password reset link has been sent! Check your email inbox.";
            msgEl.style.color = "green";
            emailEl.value = "";
        } catch (error) {
            console.error(error);
            msgEl.textContent = "Something went wrong: " + error.message + " (" + error.code + ")";
            msgEl.style.color = "red";
        }
    });
</script>
