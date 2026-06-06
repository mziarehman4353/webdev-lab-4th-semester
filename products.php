<?php
$pageTitle = 'Shop';
require_once 'includes/header.php';
$pdo = getDB();

// Filters
$search   = sanitize($_GET['q'] ?? '');
$category = sanitize($_GET['category'] ?? '');
$sort     = sanitize($_GET['sort'] ?? 'featured');
$minPrice = (int)($_GET['min'] ?? 0);
$maxPrice = (int)($_GET['max'] ?? 999999);

// Build query
$where = ["p.status = 'active'"];
$params = [];

if ($search) {
    $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($category) {
    $where[] = "c.slug = ?";
    $params[] = $category;
}
if ($minPrice > 0) { $where[] = "p.price >= ?"; $params[] = $minPrice; }
if ($maxPrice < 999999) { $where[] = "p.price <= ?"; $params[] = $maxPrice; }

$orderBy = match($sort) {
    'price_asc'  => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'newest'     => 'p.created_at DESC',
    'name'       => 'p.name ASC',
    default      => 'p.featured DESC, p.created_at DESC'
};

$whereStr = implode(' AND ', $where);
$sql = "SELECT p.*, c.name as cat_name, c.slug as cat_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE $whereStr ORDER BY $orderBy";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// All categories for sidebar
$catStmt = $pdo->query("SELECT c.*, COUNT(p.id) as cnt FROM categories c LEFT JOIN products p ON c.id = p.category_id AND p.status='active' GROUP BY c.id ORDER BY c.name");
$categories = $catStmt->fetchAll();

// Current category name
$currentCatName = 'All Products';
foreach ($categories as $cat) {
    if ($cat['slug'] === $category) { $currentCatName = $cat['name']; break; }
}
?>

<!-- Page Header -->
<div style="background: linear-gradient(135deg, #fff0f5 0%, #fce4ec 100%); padding: 48px 0 32px; border-bottom: 1px solid var(--pink-100);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <nav style="font-size: 13px; color: #9e7a90; margin-bottom: 12px;">
            <a href="index.php" style="color: #9e7a90; text-decoration: none;">Home</a>
            <span style="margin: 0 8px;">›</span>
            <span style="color: #c2185b;"><?= $search ? 'Search: ' . $search : $currentCatName ?></span>
        </nav>
        <h1 class="font-display" style="font-size: 36px; font-weight: 700; color: #4a3040;">
            <?= $search ? 'Results for "' . htmlspecialchars($search) . '"' : htmlspecialchars($currentCatName) ?>
        </h1>
        <p style="color: #9e7a90; margin-top: 6px;"><?= count($products) ?> products found</p>
    </div>
</div>

<div style="max-width: 1200px; margin: 0 auto; padding: 40px 24px; display: grid; grid-template-columns: 260px 1fr; gap: 40px;">

    <!-- SIDEBAR FILTERS -->
    <aside>
        <!-- Categories -->
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 24px; margin-bottom: 20px;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 600; color: #4a3040; margin-bottom: 16px;">Categories</h3>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 4px;">
                <li>
                    <a href="products.php<?= $search ? '?q='.urlencode($search) : '' ?>"
                       style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-radius: 10px; text-decoration: none; font-size: 14px; transition: all 0.2s;
                              background: <?= !$category ? 'var(--pink-50)' : 'transparent' ?>; color: <?= !$category ? '#c2185b' : '#7a4f6b' ?>; font-weight: <?= !$category ? '600' : '400' ?>;">
                        <span>All Products</span>
                        <span style="background: var(--pink-100); color: #c2185b; border-radius: 10px; padding: 1px 8px; font-size: 12px;">
                            <?= array_sum(array_column($categories, 'cnt')) ?>
                        </span>
                    </a>
                </li>
                <?php foreach ($categories as $cat): ?>
                <li>
                    <a href="products.php?category=<?= urlencode($cat['slug']) ?><?= $search ? '&q='.urlencode($search) : '' ?>"
                       style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-radius: 10px; text-decoration: none; font-size: 14px; transition: all 0.2s;
                              background: <?= $category === $cat['slug'] ? 'var(--pink-50)' : 'transparent' ?>; color: <?= $category === $cat['slug'] ? '#c2185b' : '#7a4f6b' ?>; font-weight: <?= $category === $cat['slug'] ? '600' : '400' ?>;">
                        <span><i class="ti <?= htmlspecialchars($cat['icon']) ?>" style="font-size: 14px; margin-right: 6px;"></i><?= htmlspecialchars($cat['name']) ?></span>
                        <span style="background: var(--pink-100); color: #c2185b; border-radius: 10px; padding: 1px 8px; font-size: 12px;"><?= $cat['cnt'] ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Price Filter -->
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 24px; margin-bottom: 20px;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 600; color: #4a3040; margin-bottom: 16px;">Price Range</h3>
            <form method="GET" action="products.php">
                <?php if ($category): ?><input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>"><?php endif; ?>
                <?php if ($search): ?><input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
                <?php if ($sort): ?><input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>"><?php endif; ?>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label style="font-size: 12px; color: #9e7a90; display: block; margin-bottom: 4px;">Min (Rp)</label>
                        <input type="number" name="min" value="<?= $minPrice ?: '' ?>" placeholder="0" class="form-input" style="font-size: 13px;">
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #9e7a90; display: block; margin-bottom: 4px;">Max (Rp)</label>
                        <input type="number" name="max" value="<?= $maxPrice < 999999 ? $maxPrice : '' ?>" placeholder="Any" class="form-input" style="font-size: 13px;">
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; font-size: 13px; padding: 9px;">
                    Apply Filter
                </button>
            </form>
        </div>

        <!-- Quick Price Ranges -->
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 24px;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 600; color: #4a3040; margin-bottom: 12px;">Quick Filter</h3>
            <?php
            $ranges = [
                ['Under Rp 25.000', 0, 25000],
                ['Rp 25.000 – 50.000', 25000, 50000],
                ['Rp 50.000 – 100.000', 50000, 100000],
                ['Over Rp 100.000', 100000, 999999],
            ];
            foreach ($ranges as $r):
            ?>
            <a href="products.php?<?= $category ? 'category='.urlencode($category).'&' : '' ?>min=<?= $r[1] ?>&max=<?= $r[2] ?><?= $sort ? '&sort='.urlencode($sort) : '' ?>"
               style="display: block; padding: 8px 12px; border-radius: 10px; font-size: 13px; color: #7a4f6b; text-decoration: none; transition: background 0.2s; margin-bottom: 4px;"
               onmouseover="this.style.background='var(--pink-50)'; this.style.color='#c2185b';"
               onmouseout="this.style.background=''; this.style.color='#7a4f6b';">
                <?= $r[0] ?>
            </a>
            <?php endforeach; ?>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div>
        <!-- Sort bar -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; background: white; border-radius: 14px; border: 1px solid var(--pink-100); padding: 12px 20px;">
            <span style="font-size: 14px; color: #9e7a90;"><?= count($products) ?> products</span>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 13px; color: #9e7a90;">Sort by:</span>
                <form method="GET" style="display: inline;">
                    <?php if ($category): ?><input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>"><?php endif; ?>
                    <?php if ($search): ?><input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
                    <?php if ($minPrice): ?><input type="hidden" name="min" value="<?= $minPrice ?>"><?php endif; ?>
                    <?php if ($maxPrice < 999999): ?><input type="hidden" name="max" value="<?= $maxPrice ?>"><?php endif; ?>
                    <select name="sort" onchange="this.form.submit()" class="form-input" style="font-size: 13px; width: auto; padding: 7px 14px; border-radius: 10px;">
                        <option value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>Featured</option>
                        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name A–Z</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 20px; border: 1px solid var(--pink-100);">
            <div style="font-size: 64px; margin-bottom: 16px;">🔍</div>
            <h3 style="font-family: 'Playfair Display', serif; font-size: 22px; color: #4a3040; margin-bottom: 8px;">No products found</h3>
            <p style="color: #9e7a90; font-size: 15px; margin-bottom: 24px;">Try adjusting your search or filters</p>
            <a href="products.php" class="btn-primary">Browse All Products</a>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <?php foreach ($products as $i => $product): ?>
            <div class="product-card" style="animation: fadeInUp 0.4s ease both; animation-delay: <?= ($i % 9) * 0.05 ?>s;">
                <?php if ($product['original_price']): ?>
                <div style="position: absolute; top: 12px; left: 12px; z-index: 2;">
                    <span class="badge badge-sale">-<?= round((1 - $product['price'] / $product['original_price']) * 100) ?>%</span>
                </div>
                <?php endif; ?>
                <?php if ($product['featured']): ?>
                <div style="position: absolute; top: 12px; right: 12px; z-index: 2;">
                    <span class="badge">⭐ Top Pick</span>
                </div>
                <?php endif; ?>

                <?php if ($product['image']): ?>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                <?php else: ?>
                <div class="product-img-placeholder">
                    <?php $icons = ['🍎','🥑','🥛','🥚','🍵','🧴','🍞','🧹','🍊','🧀','🫐','🥕']; echo $icons[$product['id'] % count($icons)]; ?>
                </div>
                <?php endif; ?>

                <div style="padding: 16px;">
                    <div style="font-size: 11px; color: #c2185b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">
                        <?= htmlspecialchars($product['cat_name'] ?? '') ?>
                    </div>
                    <h3 style="font-size: 14px; font-weight: 600; color: #4a3040; line-height: 1.4; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= htmlspecialchars($product['name']) ?>
                    </h3>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: auto;">
                        <div>
                            <div class="price" style="font-size: 16px;"><?= formatCurrency($product['price']) ?></div>
                            <?php if ($product['original_price']): ?>
                            <div class="price-original"><?= formatCurrency($product['original_price']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn-outline" style="font-size: 11px; padding: 6px 10px;">
                                Detail
                            </a>
                            <?php if ($product['stock'] > 0): ?>
                            <button onclick="addToCart(<?= $product['id'] ?>, '<?= addslashes($product['name']) ?>', <?= $product['price'] ?>, '<?= addslashes($product['image'] ?? '') ?>')"
                                    class="btn-primary" style="font-size: 11px; padding: 6px 10px;">
                                <i class="ti ti-cart-plus"></i>
                            </button>
                            <?php else: ?>
                            <button disabled style="font-size: 11px; padding: 6px 10px; background: #ddd; border-radius: 25px; border: none; color: #888; cursor: not-allowed;">
                                Out of Stock
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($product['stock'] > 0 && $product['stock'] < 10): ?>
                    <div style="margin-top: 8px; font-size: 11px; color: #e91e8c;">
                        <i class="ti ti-alert-circle"></i> Only <?= $product['stock'] ?> left!
                    </div>
                    <?php elseif ($product['stock'] == 0): ?>
                    <div style="margin-top: 8px; font-size: 11px; color: #d48ba0;">Out of stock</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
