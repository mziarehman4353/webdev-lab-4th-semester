<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

// Validate required fields
$required = ['name', 'email', 'phone', 'address', 'cart', 'total'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'error' => "Missing: $field"]);
        exit;
    }
}

$cart = $data['cart'];
if (empty($cart) || !is_array($cart)) {
    echo json_encode(['success' => false, 'error' => 'Cart is empty']);
    exit;
}

$pdo = getDB();

try {
    $pdo->beginTransaction();

    // Verify prices server-side (basic security)
    $productIds = array_column($cart, 'id');
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id IN ($placeholders) AND status = 'active'");
    $stmt->execute($productIds);
    $dbProducts = [];
    while ($row = $stmt->fetch()) {
        $dbProducts[$row['id']] = $row;
    }

    // Recalculate totals server-side
    $subtotal = 0;
    foreach ($cart as $item) {
        $pid = (int)$item['id'];
        if (!isset($dbProducts[$pid])) {
            throw new Exception("Product #$pid not found");
        }
        $subtotal += $dbProducts[$pid]['price'] * (int)$item['qty'];
    }
    $shippingFee = SHIPPING_FEE;
    $total = $subtotal + $shippingFee;
    $orderNumber = generateOrderNumber();

    // Create order
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, order_number, customer_name, customer_email, customer_phone, shipping_address, subtotal, shipping_fee, total, notes, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $userId = $_SESSION['user_id'] ?? null;
    $stmt->execute([
        $userId,
        $orderNumber,
        htmlspecialchars($data['name']),
        filter_var($data['email'], FILTER_SANITIZE_EMAIL),
        htmlspecialchars($data['phone']),
        htmlspecialchars($data['address']),
        $subtotal,
        $shippingFee,
        $total,
        htmlspecialchars($data['notes'] ?? '')
    ]);
    $orderId = $pdo->lastInsertId();

    // Insert order items + decrement stock
    $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

    foreach ($cart as $item) {
        $pid = (int)$item['id'];
        $qty = (int)$item['qty'];
        $dbProd = $dbProducts[$pid];

        // Decrement stock
        $stockStmt->execute([$qty, $pid, $qty]);
        if ($stockStmt->rowCount() === 0) {
            throw new Exception("Insufficient stock for: {$dbProd['name']}");
        }

        // Insert item
        $itemStmt->execute([
            $orderId,
            $pid,
            $dbProd['name'],
            $dbProd['price'],
            $qty,
            $dbProd['price'] * $qty
        ]);
    }

    // Clear session cart
    $_SESSION['cart'] = [];

    $pdo->commit();
    echo json_encode(['success' => true, 'order_number' => $orderNumber, 'order_id' => $orderId]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
