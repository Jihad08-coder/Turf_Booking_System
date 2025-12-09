<?php
// oauth_start.php
require 'config.php';

$provider = $_GET['provider'] ?? '';

if ($provider === 'google') {
    // ekhane Google OAuth redirect URL banabe
} elseif ($provider === 'facebook') {
    // Facebook redirect
} elseif ($provider === 'github') {
    // GitHub redirect
} else {
    header('Location: login.php');
    exit;
}
