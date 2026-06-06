<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

$pdo = getDB();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("UPDATE products SET status='inactive' WHERE id=?")->execute([(int)$_GET['delete']]);
    header('Location: products.php?msg=deleted');
    exit;
}

// Handle toggle featured
if (isset($_GET['feature']) && is_numeric($_GET['feature'])) {
    $pdo->prepare("UPDATE products SET featured = NOT featured WHERE id=?")->execute([(int)$_GET['feature']]);
    header('Location: products.php');
    exit;
}

$search = sanitize($_GET['q'] ?? '');
$where  = "p.status = 'active'";
$params = [];
if ($search) {
    $where .= " AND p.name LIKE ?";
    $params[] = "%$search%";
}

$products = $pdo->prepare("
    SELECT p.*, c.name as cat_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE $where
    ORDER BY p.created_at DESC
");
$products->execute($params);
$products = $products->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products — BlossomMart Admin</title>
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
        td { font-size: 14px; padding: 12px 16px; border-bottom: 1px solid var(--pink-50); color: #4a3040; vertical-align: middle; }
        tr:hover td { background: var(--pink-50); }
        .btn-sm { display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; border-radius: 8px; font-size: 12px; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
        .form-input { width: 100%; border: 1.5px solid var(--pink-200); border-radius: 12px; padding: 9px 14px; font-family: 'DM Sans', sans-serif; font-size: 14px; color: #4a3040; background: white; outline: none; }
        .form-input:focus { border-color: #e91e8c; }
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
    <a href="products.php" class="sidebar-link active"><i class="ti ti-package"></i> Products</a>
    <a href="add_product.php" class="sidebar-link"><i class="ti ti-circle-plus"></i> Add Product</a>
    <a href="customers.php" class="sidebar-link"><i class="ti ti-users"></i> Customers</a>
    <div style="margin-top: auto; padding: 16px 20px; border-top: 1px solid var(--pink-100);">
        <a href="<?= SITE_URL ?>/index.php" class="sidebar-link" style="padding: 8px 0;"><i class="ti ti-arrow-left"></i> Back to Store</a>
        <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link" style="padding: 8px 0; color: #d48ba0;"><i class="ti ti-logout"></i> Logout</a>
    </div>
</div>

<main>
    <?php if (isset($_GET['msg'])): ?>
    <div style="background: #e8f5e9; border: 1px solid #a5d6a7; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px; color: #2e7d32;">
        <i class="ti ti-circle-check"></i>
        <?= $_GET['msg'] === 'deleted' ? 'Product deactivated.' : ($_GET['msg'] === 'saved' ? 'Product saved successfully!' : 'Done!') ?>
    </div>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 28px;">Products</h1>
            <p style="color: #9e7a90; font-size: 14px; margin-top: 4px;"><?= count($products) ?> active products</p>
        </div>
        <a href="add_product.php" style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #f48fb1, #e91e8c); color: white; border-radius: 20px; padding: 10px 22px; font-weight: 600; font-size: 14px; text-decoration: none;">
            <i class="ti ti-plus"></i> Add Product
        </a>
    </div>

    <!-- Search -->
    <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; max-width: 400px;">
        <input type="text" name="q" placeholder="Search products…" class="form-input" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" style="background: linear-gradient(135deg, #f48fb1, #e91e8c); color: white; border: none; border-radius: 12px; padding: 9px 18px; cursor: pointer; font-size: 14px; white-space: nowrap;">Search</button>
    </form>

    <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); overflow: hidden;">
        <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 60px; color: #9e7a90;">
            <i class="ti ti-package-off" style="font-size: 48px; display: block; margin-bottom: 12px;"></i>
            No products found.
        </div>
        <?php else: ?>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p):
                        $icons = ['🍎','🥑','🥛','🥚','🍵','🧴','🍞','🧹','🍊','🧀','🫐','🥕'];
                        $icon = $icons[$p['id'] % count($icons)];
                    ?>
                    <tr>
                        <td style="color: #9e7a90;">#<?= $p['id'] ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--pink-50); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                                    <?= $p['image'] ? "<img src='{$p['image']}' style='width:100%;height:100%;object-fit:cover;border-radius:10px;'>" : $icon ?>
                                </div>
                                <div>
                                    <div style="font-weight: 600; font-size: 13px;"><?= htmlspecialchars($p['name']) ?></div>
                                    <?php if ($p['original_price']): ?>
                                    <div style="font-size: 11px; color: #e91e8c;">On Sale</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td style="font-size: 13px;"><?= htmlspecialchars($p['cat_name'] ?? '—') ?></td>
                        <td>
                            <div style="font-weight: 600; color: #c2185b;"><?= formatCurrency($p['price']) ?></div>
                            <?php if ($p['original_price']): ?>
                            <div style="font-size: 11px; color: #b8a0b0; text-decoration: line-through;"><?= formatCurrency($p['original_price']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: <?= $p['stock'] == 0 ? '#c2185b' : ($p['stock'] < 10 ? '#f57c00' : '#2e7d32') ?>;">
                                <?= $p['stock'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="products.php?feature=<?= $p['id'] ?>" style="text-decoration: none;">
                                <span style="font-size: 18px;"><?= $p['featured'] ? '⭐' : '☆' ?></span>
                            </a>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn-sm" style="background: #e3f2fd; color: #1565c0;">
                                    <i class="ti ti-edit"></i> Edit
                                </a>
                                <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>" class="btn-sm" style="background: #e8f5e9; color: #2e7d32;" target="_blank">
                                    <i class="ti ti-eye"></i> View
                                </a>
                                <a href="products.php?delete=<?= $p['id'] ?>" class="btn-sm" style="background: #fce4ec; color: #c2185b;" onclick="return confirm('Deactivate this product?')">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
