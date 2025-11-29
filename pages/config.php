<?php
// ---- DATABASE SETTINGS ----
$host = '127.0.0.1';      // or 'localhost'
$db   = 'turf_booking';   // phpMyAdmin e jei DB create korecho, oitar naam
$user = 'root';           // XAMPP default user
$pass = '';               // root er default password faka

try {
    // PDO connection
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    // error mode: exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // session start (jodi age theke na thake)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

} catch (PDOException $e) {
    // connection fail hole friendly error
    die("Database connection failed: " . $e->getMessage());
}
