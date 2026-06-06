<?php
require_once '../includes/config.php';

// Guard: admin only
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

$pdo = getDB();

// Stats
$totalOrders  = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status NOT IN ('cancelled')")->fetchColumn();
$totalProducts= $pdo->query("SELECT COUNT(*) FROM products WHERE status='active'")->fetchColumn();
$totalUsers   = $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();

// Recent orders
$recentOrders = $pdo->query("SELECT o.*, COUNT(oi.id) as item_count FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id GROUP BY o.id ORDER BY o.created_at DESC LIMIT 10")->fetchAll();

// Low stock products
$lowStock = $pdo->query("SELECT * FROM products WHERE stock < 10 AND status='active' ORDER BY stock ASC LIMIT 6")->fetchAll();

$pageTitle = 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — BlossomMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --pink-50:#fff0f5; --pink-100:#ffd6e7; --pink-200:#ffadd4; --pink-300:#ff85bf; --pink-400:#f06292; --pink-500:#e91e8c; --pink-600:#c2185b; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #fdf5f8; color: #4a3040; display: flex; min-height: 100vh; }
        h1,h2,h3,.font-display { font-family: 'Playfair Display', serif; }

        /* Sidebar */
        .sidebar { width: 240px; background: white; border-right: 1px solid var(--pink-100); flex-shrink: 0; display: flex; flex-direction: column; padding: 24px 0; }
        .sidebar-logo { padding: 0 20px 24px; border-bottom: 1px solid var(--pink-100); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 20px; font-size: 14px; font-weight: 500; color: #9e7a90; text-decoration: none; transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: var(--pink-50); color: #c2185b; }
        .sidebar-link i { font-size: 18px; }

        /* Main */
        main { flex: 1; padding: 32px; overflow-y: auto; }
        .stat-card { background: white; border-radius: 18px; border: 1px solid var(--pink-100); padding: 24px; }
        .badge-status { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending    { background: #fff3e0; color: #f57c00; }
        .status-processing { background: #e3f2fd; color: #1565c0; }
        .status-shipped    { background: #e8f5e9; color: #2e7d32; }
        .status-delivered  { background: #e8f5e9; color: #1b5e20; }
        .status-cancelled  { background: #fce4ec; color: #c2185b; }

        table { width: 100%; border-collapse: collapse; }
        th { font-size: 12px; font-weight: 600; color: #9e7a90; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 16px; text-align: left; border-bottom: 1px solid var(--pink-100); }
        td { font-size: 14px; padding: 12px 16px; border-bottom: 1px solid var(--pink-50); color: #4a3040; }
        tr:hover td { background: var(--pink-50); }

        select.status-select { border: 1px solid var(--pink-200); border-radius: 8px; padding: 4px 8px; font-size: 13px; background: white; color: #4a3040; cursor: pointer; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-logo">
        <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #f48fb1, #e91e8c); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <i class="ti ti-shopping-bag" style="color: white; font-size: 16px;"></i>
        </div>
        <span style="font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 700; color: #c2185b;">Blossom<span style="color: #4a3040;">Mart</span></span>
    </div>

    <a href="index.php" class="sidebar-link active"><i class="ti ti-dashboard"></i> Dashboard</a>
    <a href="orders.php" class="sidebar-link"><i class="ti ti-receipt"></i> Orders</a>
    <a href="products.php" class="sidebar-link"><i class="ti ti-package"></i> Products</a>
    <a href="add_product.php" class="sidebar-link"><i class="ti ti-circle-plus"></i> Add Product</a>
    <a href="customers.php" class="sidebar-link"><i class="ti ti-users"></i> Customers</a>

    <div style="margin-top: auto; padding: 16px 20px; border-top: 1px solid var(--pink-100);">
        <a href="<?= SITE_URL ?>/index.php" class="sidebar-link" style="padding: 8px 0;"><i class="ti ti-arrow-left"></i> Back to Store</a>
        <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link" style="padding: 8px 0; color: #d48ba0;"><i class="ti ti-logout"></i> Logout</a>
    </div>
</div>

<!-- Main -->
<main>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 class="font-display" style="font-size: 28px;">Dashboard</h1>
            <p style="color: #9e7a90; font-size: 14px; margin-top: 4px;">Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>! Here's your store overview.</p>
        </div>
        <div style="font-size: 13px; color: #9e7a90;"><?= date('l, F j, Y') ?></div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px;">
        <?php
        $stats = [
            ['ti-receipt', 'Total Orders', number_format($totalOrders), '#e3f2fd', '#1565c0'],
            ['ti-currency-dollar', 'Revenue', formatCurrency($totalRevenue), '#e8f5e9', '#2e7d32'],
            ['ti-package', 'Products', number_format($totalProducts), '#fff3e0', '#f57c00'],
            ['ti-users', 'Customers', number_format($totalUsers), '#fce4ec', '#c2185b'],
        ];
        foreach ($stats as $s):
        ?>
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: <?= $s[3] ?>; display: flex; align-items: center; justify-content: center;">
                    <i class="ti <?= $s[0] ?>" style="font-size: 22px; color: <?= $s[4] ?>;"></i>
                </div>
            </div>
            <div style="font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: #4a3040;"><?= $s[2] ?></div>
            <div style="font-size: 13px; color: #9e7a90; margin-top: 4px;"><?= $s[1] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Recent Orders -->
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="font-display" style="font-size: 18px;">Recent Orders</h3>
                <a href="orders.php" style="font-size: 13px; color: #c2185b; text-decoration: none;">View all →</a>
            </div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><strong style="color: #c2185b;"><?= htmlspecialchars($order['order_number']) ?></strong></td>
                            <td>
                                <div style="font-weight: 500;"><?= htmlspecialchars($order['customer_name']) ?></div>
                                <div style="font-size: 12px; color: #9e7a90;"><?= htmlspecialchars($order['customer_email']) ?></div>
                            </td>
                            <td><?= $order['item_count'] ?> item<?= $order['item_count'] != 1 ? 's' : '' ?></td>
                            <td><strong><?= formatCurrency($order['total']) ?></strong></td>
                            <td>
                                <select class="status-select" onchange="updateOrderStatus(<?= $order['id'] ?>, this.value)">
                                    <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td style="font-size: 12px; color: #9e7a90;"><?= date('d M', strtotime($order['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="font-display" style="font-size: 18px;">Low Stock Alert</h3>
                <i class="ti ti-alert-triangle" style="color: #f57c00; font-size: 20px;"></i>
            </div>
            <?php if (empty($lowStock)): ?>
            <div style="text-align: center; padding: 32px; color: #9e7a90;">
                <i class="ti ti-circle-check" style="font-size: 40px; color: #4caf50; display: block; margin-bottom: 8px;"></i>
                All products are well stocked!
            </div>
            <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach ($lowStock as $prod): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; border-radius: 12px; background: <?= $prod['stock'] == 0 ? '#fce4ec' : '#fff8e1' ?>; border: 1px solid <?= $prod['stock'] == 0 ? 'var(--pink-200)' : '#ffe082' ?>;">
                    <div>
                        <div style="font-size: 13px; font-weight: 600; color: #4a3040;"><?= htmlspecialchars(substr($prod['name'], 0, 30)) ?><?= strlen($prod['name']) > 30 ? '…' : '' ?></div>
                        <div style="font-size: 11px; color: #9e7a90; margin-top: 2px;">ID: #<?= $prod['id'] ?></div>
                    </div>
                    <span style="font-weight: 700; font-size: 14px; color: <?= $prod['stock'] == 0 ? '#c2185b' : '#f57c00' ?>;">
                        <?= $prod['stock'] == 0 ? '❌ 0' : $prod['stock'] . ' left' ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
function updateOrderStatus(orderId, status) {
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
