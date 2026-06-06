<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Change to your MySQL username
define('DB_PASS', '');            // Change to your MySQL password
define('DB_NAME', 'blossommart');

// Site Configuration
define('SITE_NAME', 'BlossomMart');
define('SITE_URL', 'http://localhost/shop');
define('CURRENCY', 'Rp');
define('SHIPPING_FEE', 15000);

// Create PDO connection
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper: format currency
function formatCurrency($amount) {
    return CURRENCY . ' ' . number_format($amount, 0, ',', '.');
}

// Helper: get cart count
function getCartCount() {
    $cart = $_SESSION['cart'] ?? [];
    return array_sum(array_column($cart, 'qty'));
}

// Helper: sanitize input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Helper: generate order number
function generateOrderNumber() {
    return 'BM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}
