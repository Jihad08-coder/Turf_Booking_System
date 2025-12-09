<?php
// admin & manager user create korar small script
// 1 bar run korbe, pore file delete kore diba

require 'config.php';

$usersToCreate = [
    [
        'name'     => 'jihad',
        'email'    => 'jahid@admin.com',
        'password' => '123456',   // login er password
        'role'     => 'admin',
    ],
    [
        'name'     => 'sanzid',
        'email'    => 'sanzid@gmail.com',
        'password' => '123456',
        'role'     => 'manager',
    ],
];

foreach ($usersToCreate as $u) {
    // check: ei email er user age theke ache naki
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$u['email']]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        echo ucfirst($u['role']) . " already exists with email " . htmlspecialchars($u['email']) . "<br>";
        continue;
    }

    // password hash
    $hash = password_hash($u['password'], PASSWORD_DEFAULT);

    // notun user insert
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$u['name'], $u['email'], $hash, $u['role']]);

    echo ucfirst($u['role']) . " created. Email: " . htmlspecialchars($u['email']) .
         " | Password: " . htmlspecialchars($u['password']) . "<br>";
}

echo "<br>Done. Now delete this file (create_admin_manager.php) for security.";
