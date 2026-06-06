<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    if ($action === 'sync') {
        $_SESSION['cart'] = $data['cart'] ?? [];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['cart' => $_SESSION['cart'] ?? [], 'count' => getCartCount()]);
} else {
    echo json_encode(['error' => 'Method not allowed']);
}
