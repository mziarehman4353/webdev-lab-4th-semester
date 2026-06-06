<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

$pdo = getDB();

// Filter by status
$filterStatus = sanitize($_GET['status'] ?? '');
$where  = $filterStatus ? "WHERE o.status = ?" : "";
$params = $filterStatus ? [$filterStatus] : [];

$orders = $pdo->prepare("
    SELECT o.*, COUNT(oi.id) as item_count
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    $where
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$orders->execute($params);
$orders = $orders->fetchAll();

// Counts per status
$statusCounts = [];
$rows = $pdo->query("SELECT status, COUNT(*) as cnt FROM orders GROUP BY status")->fetchAll();
foreach ($rows as $r) $statusCounts[$r['status']] = $r['cnt'];
$statusCounts['all'] = array_sum($statusCounts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders — BlossomMart Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --pink-50:#fff0f5; --pink-100:#ffd6e7; --pink-200:#ffadd4; --pink-600:#c2185b; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #fdf5f8; color: #4a3040; display: flex; min-height: 100vh; }
        h1,h2,h3 { font-family: 'Playfair Display', serif; }
        .sidebar { width: 240px; background: white; border-right: 1px solid var(--pink-100); flex-shrink: 0; display: flex; flex-direction: column; padding: 24px 0; }
        .sidebar-logo { padding: 0 20px 24px; border-bottom: 1px solid var(--pink-100); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 20px; font-size: 14px; font-weight: 500; color: #9e7a90; text-decoration: none; transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: var(--pink-50); color: var(--pink-600); }
        .sidebar-link i { font-size: 18px; }
        main { flex: 1; padding: 32px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 12px; font-weight: 600; color: #9e7a90; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 16px; text-align: left; border-bottom: 1px solid var(--pink-100); }
        td { font-size: 14px; padding: 12px 16px; border-bottom: 1px solid var(--pink-50); color: #4a3040; }
        tr:hover td { background: var(--pink-50); }
        .tab-link { display: inline-block; padding: 7px 18px; border-radius: 20px; font-size: 13px; font-weight: 500; text-decoration: none; color: #9e7a90; transition: all 0.2s; }
        .tab-link:hover, .tab-link.active { background: var(--pink-50); color: var(--pink-600); }
        select.status-select { border: 1px solid var(--pink-200); border-radius: 8px; padding: 4px 8px; font-size: 13px; background: white; color: #4a3040; cursor: pointer; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-logo">
        <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #f48fb1, #e91e8c); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <i class="ti ti-shopping-bag" style="color: white; font-size: 16px;"></i>
        </div>
        <span style="font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 700; color: #c2185b;">Blossom<span style="color: #4a3040;">Mart</span></span>
    </div>
    <a href="index.php" class="sidebar-link"><i class="ti ti-dashboard"></i> Dashboard</a>
    <a href="orders.php" class="sidebar-link active"><i class="ti ti-receipt"></i> Orders</a>
    <a href="products.php" class="sidebar-link"><i class="ti ti-package"></i> Products</a>
    <a href="add_product.php" class="sidebar-link"><i class="ti ti-circle-plus"></i> Add Product</a>
    <a href="customers.php" class="sidebar-link"><i class="ti ti-users"></i> Customers</a>
    <div style="margin-top: auto; padding: 16px 20px; border-top: 1px solid var(--pink-100);">
        <a href="<?= SITE_URL ?>/index.php" class="sidebar-link" style="padding: 8px 0;"><i class="ti ti-arrow-left"></i> Back to Store</a>
        <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link" style="padding: 8px 0; color: #d48ba0;"><i class="ti ti-logout"></i> Logout</a>
    </div>
</div>

<main>
    <div style="margin-bottom: 28px;">
        <h1 style="font-size: 28px;">Orders</h1>
        <p style="color: #9e7a90; font-size: 14px; margin-top: 4px;">Manage and track all customer orders</p>
    </div>

    <!-- Status Tabs -->
    <div style="display: flex; gap: 4px; margin-bottom: 24px; background: white; border-radius: 14px; border: 1px solid var(--pink-100); padding: 8px; width: fit-content;">
        <a href="orders.php" class="tab-link <?= !$filterStatus ? 'active' : '' ?>">All <span style="font-size: 11px; background: #f3e5f5; color: #7b1fa2; border-radius: 10px; padding: 1px 7px; margin-left: 4px;"><?= $statusCounts['all'] ?? 0 ?></span></a>
        <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
        <a href="orders.php?status=<?= $s ?>" class="tab-link <?= $filterStatus === $s ? 'active' : '' ?>">
            <?= ucfirst($s) ?>
            <span style="font-size: 11px; background: var(--pink-50); color: var(--pink-600); border-radius: 10px; padding: 1px 7px; margin-left: 4px;"><?= $statusCounts[$s] ?? 0 ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Orders Table -->
    <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); overflow: hidden;">
        <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 60px; color: #9e7a90;">
            <i class="ti ti-receipt-off" style="font-size: 48px; display: block; margin-bottom: 12px;"></i>
            No orders found.
        </div>
        <?php else: ?>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong style="color: #c2185b;"><?= htmlspecialchars($order['order_number']) ?></strong></td>
                        <td>
                            <div style="font-weight: 500;"><?= htmlspecialchars($order['customer_name']) ?></div>
                            <div style="font-size: 12px; color: #9e7a90;"><?= htmlspecialchars($order['customer_email']) ?></div>
                            <div style="font-size: 12px; color: #9e7a90;"><?= htmlspecialchars($order['customer_phone']) ?></div>
                        </td>
                        <td style="font-size: 12px; max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($order['shipping_address']) ?>">
                            <?= htmlspecialchars($order['shipping_address']) ?>
                        </td>
                        <td><?= $order['item_count'] ?></td>
                        <td><strong><?= formatCurrency($order['total']) ?></strong></td>
                        <td style="font-size: 12px; text-transform: capitalize;"><?= htmlspecialchars($order['notes'] ?? 'cod') ?></td>
                        <td>
                            <select class="status-select" onchange="updateStatus(<?= $order['id'] ?>, this.value)">
                                <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                                <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td style="font-size: 12px; color: #9e7a90; white-space: nowrap;"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <a href="order_detail.php?id=<?= $order['id'] ?>" style="color: #c2185b; font-size: 13px; text-decoration: none; font-weight: 500;">
                                <i class="ti ti-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
function updateStatus(orderId, status) {
    fetch('update_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({order_id: orderId, status})
    }).then(r => r.json()).then(d => {
        if (!d.success) alert('Error updating status');
    });
}
</script>
</body>
</html>
