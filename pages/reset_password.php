<?php
require 'config.php';
include 'header.php';
?>

<link rel="stylesheet" href="css/login_style.css">

<div class="login-wrapper">
    <div class="login-card">
        <h2 class="login-title">Reset Password</h2>

        <p id="rp-message" class="auth-message" style="margin-bottom:15px;"></p>

        <form id="resetForm" class="login-form" style="display:none;">
            <label>New Password</label>
            <input type="password" id="newPassword" required>

            <label>Confirm New Password</label>
            <input type="password" id="confirmPassword" required>

            <button type="submit" class="btn btn-primary login-btn">
                Update Password
            </button>
        </form>

        <p class="auth-switch-text" style="margin-top:10px;">
            <a href="login.php">Back to login</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- 🔥 Firebase + DB sync -->
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-app.js";
    import {
        getAuth,
        verifyPasswordResetCode,
        confirmPasswordReset
    } from "https://www.gstatic.com/firebasejs/11.0.0/firebase-auth.js";

    // SAME config jeita forgot_password.php te use korso
    const firebaseConfig = {
      apiKey: "AIzaSyAKkG2G19rp6dbmfLO8onc2Hi_lSGDDpwo",
      authDomain: "turf-booking-814c7.firebaseapp.com",
      projectId: "turf-booking-814c7",
      storageBucket: "turf-booking-814c7.appspot.com",
      messagingSenderId: "81682398292",
      appId: "1:81682398292:web:c1213c074dfca971fd41c1",
      measurementId: "G-D2YJLQ77W"
    };

    const app  = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    const msgEl  = document.getElementById('rp-message');
    const form   = document.getElementById('resetForm');
    const newPw  = document.getElementById('newPassword');
    const confPw = document.getElementById('confirmPassword');

    // Firebase link theke token (oobCode) neowa
    const params  = new URLSearchParams(window.location.search);
    const oobCode = params.get('oobCode');

    let resetEmail = null; // ei var e email rakhbo

    if (!oobCode) {
        msgEl.textContent = "Invalid reset link.";
        msgEl.style.color = "red";
    } else {
        (async () => {
            try {
                // oobCode valid kina & kon email er jonno, seta ber kora
                resetEmail = await verifyPasswordResetCode(auth, oobCode);
                msgEl.textContent = "Set a new password for: " + resetEmail;
                msgEl.style.color = "#333";
                form.style.display = 'block';
            } catch (error) {
                console.error(error);
                msgEl.textContent = "Invalid or expired reset link.";
                msgEl.style.color = "red";
            }
        })();
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const pass    = newPw.value;
        const confirm = confPw.value;

        if (pass.length < 6) {
            msgEl.textContent = "Password must be at least 6 characters.";
            msgEl.style.color = "red";
            return;
        }
        if (pass !== confirm) {
            msgEl.textContent = "Passwords do not match.";
            msgEl.style.color = "red";
            return;
        }

        try {
            // 1) Firebase e password change
            await confirmPasswordReset(auth, oobCode, pass);

            // 2) DB te update (email + new password pathabo)
            await fetch('update_password_from_firebase.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: resetEmail,
                    password: pass
                })
            });

            msgEl.textContent = "Password reset successful! You can now log in with your new password.";
            msgEl.style.color = "green";
            form.style.display = 'none';
        } catch (error) {
    console.error(error);
    // debug er jonno real error code/message show korbo
    msgEl.textContent = error.code + " : " + error.message;
    msgEl.style.color = "red";
        }
    });
</script>
