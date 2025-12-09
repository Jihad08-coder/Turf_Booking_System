<?php
require 'config.php';
header('Content-Type: application/json');

// JSON body pore
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['email']) || empty($input['password'])) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Missing email or password'
    ]);
    exit;
}

$email       = trim($input['email']);
$newPassword = $input['password'];

// password hash
$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

// DB update
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ? LIMIT 1");
$stmt->execute([$hashed, $email]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        'status'  => 'ok',
        'message' => 'Password updated in database'
    ]);
} else {
    echo json_encode([
        'status'  => 'warn',
        'message' => 'No user updated (maybe email not found)'
    ]);
}
