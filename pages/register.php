<?php
require 'config.php';

$message = '';
$success = false;



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $message = "Passwords do not match!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");

        try {
            $stmt->execute([$name, $email, $phone, $hashed]);
            $message = "Registration successful! You can login now.";
            $success = true;
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<?php include 'header.php'; ?>

<h2>Customer Registration</h2>

<?php if ($message): ?>
    <div class="message <?php echo $success ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<form method="post" id="registerForm">
    <label>Name</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Phone</label>
    <input type="text" name="phone">

    <label>Password</label>
    <input type="password" name="password" id="password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" id="confirm_password" required>

    <button type="submit">Register</button>
</form>

</div></body></html>
