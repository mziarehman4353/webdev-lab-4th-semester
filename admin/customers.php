<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

$pdo = getDB();
$customers = $pdo->query("
    SELECT u.*, COUNT(o.id) as order_count, COALESCE(SUM(o.total),0) as total_spent
    FROM users u
    LEFT JOIN orders o ON u.email = o.customer_email
    WHERE u.role = 'customer'
    GROUP BY u.id
    ORDER BY u.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers — BlossomMart Admin</title>
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
    <a href="orders.php" class="sidebar-link"><i class="ti ti-receipt"></i> Orders</a>
    <a href="products.php" class="sidebar-link"><i class="ti ti-package"></i> Products</a>
    <a href="add_product.php" class="sidebar-link"><i class="ti ti-circle-plus"></i> Add Product</a>
    <a href="customers.php" class="sidebar-link active"><i class="ti ti-users"></i> Customers</a>
    <div style="margin-top: auto; padding: 16px 20px; border-top: 1px solid var(--pink-100);">
        <a href="<?= SITE_URL ?>/index.php" class="sidebar-link" style="padding: 8px 0;"><i class="ti ti-arrow-left"></i> Back to Store</a>
        <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link" style="padding: 8px 0; color: #d48ba0;"><i class="ti ti-logout"></i> Logout</a>
    </div>
</div>

<main>
    <div style="margin-bottom: 28px;">
        <h1 style="font-size: 28px;">Customers</h1>
        <p style="color: #9e7a90; font-size: 14px; margin-top: 4px;"><?= count($customers) ?> registered customers</p>
    </div>

    <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); overflow: hidden;">
        <?php if (empty($customers)): ?>
        <div style="text-align: center; padding: 60px; color: #9e7a90;">
            <i class="ti ti-users-off" style="font-size: 48px; display: block; margin-bottom: 12px;"></i>
            No customers yet.
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Orders</th>
                    <th>Total Spent</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c):
                    $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $c['name']), 0, 2)));
                    $colors   = ['#fce4ec','#e3f2fd','#e8f5e9','#fff3e0','#f3e5f5'];
                    $textCols = ['#c2185b','#1565c0','#2e7d32','#f57c00','#7b1fa2'];
                    $ci = $c['id'] % 5;
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: <?= $colors[$ci] ?>; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 13px; color: <?= $textCols[$ci] ?>; flex-shrink: 0;">
                                <?= htmlspecialchars($initials) ?>
                            </div>
                            <div>
                                <div style="font-weight: 600;"><?= htmlspecialchars($c['name']) ?></div>
                                <div style="font-size: 12px; color: #9e7a90;"><?= htmlspecialchars($c['email']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size: 13px; color: #9e7a90;"><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
                    <td>
                        <span style="background: var(--pink-50); color: #c2185b; border-radius: 20px; padding: 3px 12px; font-size: 13px; font-weight: 600;">
                            <?= $c['order_count'] ?>
                        </span>
                    </td>
                    <td><strong style="color: <?= $c['total_spent'] > 0 ? '#c2185b' : '#9e7a90' ?>;"><?= formatCurrency($c['total_spent']) ?></strong></td>
                    <td style="font-size: 12px; color: #9e7a90;"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
