<?php
/**
 * BitBuddy Admin Setup Script
 * Run this ONCE after importing database.sql to create the admin user.
 * DELETE this file after running!
 */
require_once 'db_connect.php';

$admin_username = 'admin';
$admin_email = 'admin@bitbuddy.ru';
$admin_password = 'Admin123!';
$admin_role = 'admin';

try {
    // Check if admin already exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$admin_username, $admin_email]);
    if ($stmt->fetch()) {
        echo "Admin user already exists. Skipping creation.<br>";
    } else {
        $hashed = password_hash($admin_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$admin_username, $admin_email, $hashed, $admin_role]);
        echo "Admin user created successfully!<br>";
    }

    echo "<br><strong>Login credentials:</strong><br>";
    echo "Email: <code>{$admin_email}</code><br>";
    echo "Password: <code>{$admin_password}</code><br>";
    echo "<br><span style='color:red;font-weight:bold;'>⚠ DELETE this file (setup_admin.php) after use!</span>";
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
