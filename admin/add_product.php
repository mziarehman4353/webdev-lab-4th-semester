<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

$pdo = getDB();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = sanitize($_POST['name'] ?? '');
    $categoryId   = (int)($_POST['category_id'] ?? 0);
    $price        = (float)($_POST['price'] ?? 0);
    $origPrice    = $_POST['original_price'] ? (float)$_POST['original_price'] : null;
    $stock        = (int)($_POST['stock'] ?? 0);
    $description  = sanitize($_POST['description'] ?? '');
    $featured     = isset($_POST['featured']) ? 1 : 0;
    $slug         = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name)) . '-' . time();

    // Handle image upload
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if (!in_array($ext, $allowed)) {
            $error = 'Invalid image format. Use JPG, PNG, or WebP.';
        } else {
            $filename = 'product_' . time() . '.' . $ext;
            $uploadDir = dirname(__DIR__) . '/uploads/products/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                $imagePath = SITE_URL . '/uploads/products/' . $filename;
            }
        }
    }

    if (!$error && $name && $price > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO products (category_id, name, slug, description, price, original_price, stock, image, featured, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
        ");
        $stmt->execute([$categoryId ?: null, $name, $slug, $description, $price, $origPrice, $stock, $imagePath, $featured]);
        header('Location: products.php?msg=saved');
        exit;
    } elseif (!$error) {
        $error = 'Please fill in the required fields (name and price).';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product — BlossomMart Admin</title>
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
        .form-input { width: 100%; border: 1.5px solid var(--pink-200); border-radius: 12px; padding: 10px 14px; font-family: 'DM Sans', sans-serif; font-size: 14px; color: #4a3040; background: white; outline: none; transition: border-color 0.2s; }
        .form-input:focus { border-color: #e91e8c; }
        label { font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px; }
        .field { margin-bottom: 18px; }
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
    <a href="add_product.php" class="sidebar-link active"><i class="ti ti-circle-plus"></i> Add Product</a>
    <a href="customers.php" class="sidebar-link"><i class="ti ti-users"></i> Customers</a>
    <div style="margin-top: auto; padding: 16px 20px; border-top: 1px solid var(--pink-100);">
        <a href="<?= SITE_URL ?>/index.php" class="sidebar-link" style="padding: 8px 0;"><i class="ti ti-arrow-left"></i> Back to Store</a>
        <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link" style="padding: 8px 0; color: #d48ba0;"><i class="ti ti-logout"></i> Logout</a>
    </div>
</div>

<main>
    <div style="max-width: 740px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 28px;">
            <a href="products.php" style="color: #9e7a90; text-decoration: none; font-size: 14px;"><i class="ti ti-arrow-left"></i> Products</a>
            <span style="color: #d4c0cc;">›</span>
            <h1 style="font-size: 24px;">Add New Product</h1>
        </div>

        <?php if ($error): ?>
        <div style="background: #fce4ec; border: 1px solid #f48fb1; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px; color: #c2185b;">
            <i class="ti ti-alert-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 28px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--pink-100);">Basic Information</h3>

                <div class="field">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. Fresh Organic Apples (1kg)" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="field">
                        <label>Category</label>
                        <select name="category_id" class="form-input">
                            <option value="">— Select category —</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label>Stock Quantity *</label>
                        <input type="number" name="stock" class="form-input" placeholder="0" value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>" min="0" required>
                    </div>
                </div>

                <div class="field">
                    <label>Description</label>
                    <textarea name="description" class="form-input" rows="4" placeholder="Describe the product…" style="resize: vertical;"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 28px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--pink-100);">Pricing</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="field">
                        <label>Selling Price (Rp) *</label>
                        <input type="number" name="price" class="form-input" placeholder="25000" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" min="0" step="500" required>
                    </div>
                    <div class="field">
                        <label>Original Price (Rp) <span style="font-weight: 400; color: #9e7a90;">— for sale display</span></label>
                        <input type="number" name="original_price" class="form-input" placeholder="Leave blank if no discount" value="<?= htmlspecialchars($_POST['original_price'] ?? '') ?>" min="0" step="500">
                    </div>
                </div>
            </div>

            <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 28px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--pink-100);">Image & Options</h3>

                <div class="field">
                    <label>Product Image</label>
                    <div style="border: 2px dashed var(--pink-200); border-radius: 14px; padding: 28px; text-align: center; cursor: pointer; transition: all 0.2s; position: relative;"
                         id="dropzone"
                         onmouseover="this.style.background='var(--pink-50)'" onmouseout="this.style.background=''">
                        <div id="previewContainer" style="display: none; margin-bottom: 12px;">
                            <img id="imgPreview" style="max-height: 140px; border-radius: 10px; margin: 0 auto;">
                        </div>
                        <i class="ti ti-photo-up" style="font-size: 32px; color: #c2185b; display: block; margin-bottom: 8px;"></i>
                        <p style="font-size: 14px; color: #9e7a90;">Click or drag to upload image</p>
                        <p style="font-size: 12px; color: #b8a0b0; margin-top: 4px;">JPG, PNG, WebP — max 5MB</p>
                        <input type="file" name="image" accept="image/*" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewImage(event)">
                    </div>
                </div>

                <div class="field">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="featured" <?= isset($_POST['featured']) ? 'checked' : '' ?> style="accent-color: #e91e8c; width: 18px; height: 18px;">
                        <span>Mark as Featured Product (shown on homepage)</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #f48fb1, #e91e8c); color: white; border: none; border-radius: 20px; padding: 12px 28px; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 15px; cursor: pointer;">
                    <i class="ti ti-device-floppy"></i> Save Product
                </button>
                <a href="products.php" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; color: #9e7a90; border: 1.5px solid var(--pink-200); border-radius: 20px; padding: 11px 24px; font-weight: 500; font-size: 15px; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</main>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const container = document.getElementById('previewContainer');
        const img = document.getElementById('imgPreview');
        img.src = e.target.result;
        container.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
