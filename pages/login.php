<?php
require 'config.php';

// valid roles
$validRoles = ['customer', 'manager', 'admin'];

// get selected login type from URL
$currentRole = 'customer';
if (!empty($_GET['type']) && in_array($_GET['type'], $validRoles, true)) {
    $currentRole = $_GET['type'];
}

// if already logged in → redirect
if (!empty($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_dashboard.php');
        exit;
    } elseif ($_SESSION['role'] === 'manager') {
        header('Location: manager_dashboard.php');
        exit;
    } elseif ($_SESSION['role'] === 'customer') {
        header('Location: customer_dashboard.php');
        exit;
    }
}

$message = '';

// form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $formRole = $_POST['login_role'] ?? $currentRole;

    if (!in_array($formRole, $validRoles, true)) {
        $formRole = 'customer';
    }
    $currentRole = $formRole;

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = 'Please enter both email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            // role check
            if ($user['role'] !== $formRole) {
                $message = "You are trying to login as " . ucfirst($formRole) .
                           " but this account is a " . ucfirst($user['role']) . " account.";
            } else {
                // login success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['role']    = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                } elseif ($user['role'] === 'manager') {
                    header('Location: manager_dashboard.php');
                } else {
                    header('Location: customer_dashboard.php');
                }
                exit;
            }
        } else {
            $message = 'Invalid email or password.';
        }
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="css/login_style.css">

<div class="login-wrapper">
    <div class="login-card">

        <h2 class="login-title"><?php echo ucfirst($currentRole); ?> Login</h2>

        <!-- Tabs -->
        <div class="login-tabs">
            <a href="login.php?type=customer"
               class="login-tab <?php echo $currentRole === 'customer' ? 'active' : ''; ?>">
                Customer
            </a>
            <a href="login.php?type=manager"
               class="login-tab <?php echo $currentRole === 'manager' ? 'active' : ''; ?>">
                Manager
            </a>
            <a href="login.php?type=admin"
               class="login-tab <?php echo $currentRole === 'admin' ? 'active' : ''; ?>">
                Admin
            </a>
        </div>

        <!-- Error Message -->
        <?php if (!empty($message)): ?>
            <div class="message error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

                <!-- Login Form -->
        <form method="post" novalidate class="login-form">
            <input type="hidden" name="login_role"
                   value="<?php echo htmlspecialchars($currentRole); ?>">

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button class="btn btn-primary login-btn" type="submit">
                Login as <?php echo ucfirst($currentRole); ?>
            </button>
        </form>
        

        <p class="auth-switch-text">
    <a href="forgot_password.php">Forgot your password?</a>
</p>


        <!-- Social login buttons (below form) -->
        <p class="social-or">
            <span></span>
            <span>Or continue with</span>
            <span></span>
        </p>

        <div class="social-buttons vertical">
            <a href="oauth_start.php?provider=google" class="social-btn social-google">
                <span class="social-icon-circle">G</span>
                <span>Google</span>
            </a>
            <a href="oauth_start.php?provider=facebook" class="social-btn social-fb">
                <span class="social-icon-circle">f</span>
                <span>Facebook</span>
            </a>
            <a href="oauth_start.php?provider=github" class="social-btn social-gh">
                <span class="social-icon-circle">GH</span>
                <span>GitHub</span>
            </a>
        </div>

        <p class="auth-switch-text">
            Can't log in? 
            <a href="register.php">Create an account</a>
        </p>

    </div>
</div>

<?php include 'footer.php'; ?>
