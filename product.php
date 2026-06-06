<?php
require_once 'includes/config.php';
$pdo = getDB();

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: products.php'); exit; }

$stmt = $pdo->prepare("SELECT p.*, c.name as cat_name, c.slug as cat_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ? AND p.status = 'active'");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { header('Location: products.php'); exit; }

// Related products
$relStmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 'active' LIMIT 4");
$relStmt->execute([$product['category_id'], $id]);
$related = $relStmt->fetchAll();

$pageTitle = $product['name'];
require_once 'includes/header.php';

$icons = ['🍎','🥑','🥛','🥚','🍵','🧴','🍞','🧹','🍊','🧀','🫐','🥕'];
$icon = $icons[$product['id'] % count($icons)];
?>

<!-- Breadcrumb -->
<div style="background: white; border-bottom: 1px solid var(--pink-100); padding: 14px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px; font-size: 13px; color: #9e7a90;">
        <a href="index.php" style="color: #9e7a90; text-decoration: none;">Home</a>
        <span style="margin: 0 8px;">›</span>
        <a href="products.php" style="color: #9e7a90; text-decoration: none;">Shop</a>
        <span style="margin: 0 8px;">›</span>
        <a href="products.php?category=<?= htmlspecialchars($product['cat_slug']) ?>" style="color: #9e7a90; text-decoration: none;"><?= htmlspecialchars($product['cat_name']) ?></a>
        <span style="margin: 0 8px;">›</span>
        <span style="color: #c2185b;"><?= htmlspecialchars($product['name']) ?></span>
    </div>
</div>

<div style="max-width: 1200px; margin: 40px auto; padding: 0 24px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start;">

        <!-- Product Image -->
        <div class="fade-in-up">
            <div style="border-radius: 28px; overflow: hidden; border: 1px solid var(--pink-100); background: linear-gradient(135deg, var(--pink-50), var(--rose-100));">
                <?php if ($product['image']): ?>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100%; height: 420px; object-fit: cover; display: block;">
                <?php else: ?>
                <div style="height: 420px; display: flex; align-items: center; justify-content: center; font-size: 120px;">
                    <?= $icon ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Badges row -->
            <div style="display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
                <?php if ($product['featured']): ?>
                <span style="background: var(--pink-50); color: #c2185b; border: 1px solid var(--pink-200); border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 600;">⭐ Top Pick</span>
                <?php endif; ?>
                <?php if ($product['original_price']): ?>
                <span class="badge badge-sale" style="font-size: 12px; padding: 5px 14px; border-radius: 20px;">
                    Save <?= round((1 - $product['price'] / $product['original_price']) * 100) ?>%
                </span>
                <?php endif; ?>
                <span style="background: #e8f5e9; color: #388e3c; border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 600;">
                    <i class="ti ti-leaf"></i> <?= htmlspecialchars($product['cat_name']) ?>
                </span>
            </div>
        </div>

        <!-- Product Details -->
        <div class="fade-in-up delay-1">
            <h1 class="font-display" style="font-size: 32px; font-weight: 700; color: #4a3040; line-height: 1.3; margin-bottom: 16px;">
                <?= htmlspecialchars($product['name']) ?>
            </h1>

            <!-- Price -->
            <div style="display: flex; align-items: baseline; gap: 12px; margin-bottom: 24px;">
                <span class="price" style="font-size: 28px;"><?= formatCurrency($product['price']) ?></span>
                <?php if ($product['original_price']): ?>
                <span class="price-original" style="font-size: 17px;"><?= formatCurrency($product['original_price']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <p style="font-size: 15px; color: #7a4f6b; line-height: 1.8; margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid var(--pink-100);">
                <?= htmlspecialchars($product['description']) ?>
            </p>

            <!-- Stock Status -->
            <div style="margin-bottom: 24px;">
                <?php if ($product['stock'] > 10): ?>
                <span style="color: #388e3c; font-size: 14px; font-weight: 600;"><i class="ti ti-circle-check"></i> In Stock (<?= $product['stock'] ?> available)</span>
                <?php elseif ($product['stock'] > 0): ?>
                <span style="color: #e91e8c; font-size: 14px; font-weight: 600;"><i class="ti ti-alert-circle"></i> Low Stock — Only <?= $product['stock'] ?> left!</span>
                <?php else: ?>
                <span style="color: #d32f2f; font-size: 14px; font-weight: 600;"><i class="ti ti-circle-x"></i> Out of Stock</span>
                <?php endif; ?>
            </div>

            <!-- Quantity + Add to Cart -->
            <?php if ($product['stock'] > 0): ?>
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px; background: var(--pink-50); border: 1.5px solid var(--pink-200); border-radius: 14px; padding: 8px 16px;">
                    <button onclick="if(qtyInput.value>1) qtyInput.value--" class="qty-btn" style="width: 28px; height: 28px;">−</button>
                    <input type="number" id="qtyInput" value="1" min="1" max="<?= $product['stock'] ?>"
                           style="width: 40px; text-align: center; border: none; background: transparent; font-size: 16px; font-weight: 600; color: #4a3040; outline: none;">
                    <button onclick="if(qtyInput.value < <?= $product['stock'] ?>) qtyInput.value++" class="qty-btn" style="width: 28px; height: 28px;">+</button>
                </div>
                <button onclick="addToCartQty()" class="btn-primary" style="flex: 1; justify-content: center; padding: 14px; font-size: 15px;">
                    <i class="ti ti-shopping-cart"></i> Add to Cart
                </button>
            </div>
            <?php endif; ?>

            <!-- Buy Now -->
            <?php if ($product['stock'] > 0): ?>
            <button onclick="addToCartQty(); setTimeout(() => window.location.href='checkout.php', 300)" class="btn-outline" style="width: 100%; justify-content: center; padding: 13px; font-size: 15px; margin-bottom: 28px;">
                <i class="ti ti-zap"></i> Buy Now
            </button>
            <?php endif; ?>

            <!-- Product Details Table -->
            <div style="background: var(--pink-50); border-radius: 16px; padding: 20px; border: 1px solid var(--pink-100);">
                <h4 style="font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 600; color: #4a3040; margin-bottom: 14px;">Product Details</h4>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td style="color: #9e7a90; padding: 6px 0;">Category</td>
                        <td style="font-weight: 500; color: #4a3040; padding: 6px 0; text-align: right;"><?= htmlspecialchars($product['cat_name']) ?></td>
                    </tr>
                    <tr>
                        <td style="color: #9e7a90; padding: 6px 0;">Availability</td>
                        <td style="font-weight: 500; color: #4a3040; padding: 6px 0; text-align: right;"><?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?></td>
                    </tr>
                    <tr>
                        <td style="color: #9e7a90; padding: 6px 0;">Delivery</td>
                        <td style="font-weight: 500; color: #4a3040; padding: 6px 0; text-align: right;">Same-day (order by 2PM)</td>
                    </tr>
                    <tr>
                        <td style="color: #9e7a90; padding: 6px 0;">Returns</td>
                        <td style="font-weight: 500; color: #4a3040; padding: 6px 0; text-align: right;">24-hour easy return</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related)): ?>
    <div style="margin-top: 72px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <h2 class="section-title">You May Also Like</h2>
            <a href="products.php?category=<?= htmlspecialchars($product['cat_slug']) ?>" class="btn-outline">
                View All <i class="ti ti-arrow-right"></i>
            </a>
        </div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
            <?php foreach ($related as $rel): ?>
            <div class="product-card">
                <?php if ($rel['image']): ?>
                <img src="<?= htmlspecialchars($rel['image']) ?>" class="product-img" alt="<?= htmlspecialchars($rel['name']) ?>">
                <?php else: ?>
                <div class="product-img-placeholder" style="height: 160px;">
                    <?= $icons[$rel['id'] % count($icons)] ?>
                </div>
                <?php endif; ?>
                <div style="padding: 14px;">
                    <h4 style="font-size: 13px; font-weight: 600; color: #4a3040; margin-bottom: 10px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($rel['name']) ?></h4>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="price" style="font-size: 15px;"><?= formatCurrency($rel['price']) ?></span>
                        <button onclick="addToCart(<?= $rel['id'] ?>, '<?= addslashes($rel['name']) ?>', <?= $rel['price'] ?>, '<?= addslashes($rel['image'] ?? '') ?>')"
                                class="btn-primary" style="font-size: 11px; padding: 5px 10px;">
                            <i class="ti ti-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function addToCartQty() {
    const qty = parseInt(document.getElementById('qtyInput').value) || 1;
    const id = <?= $product['id'] ?>;
    const name = '<?= addslashes($product['name']) ?>';
    const price = <?= $product['price'] ?>;
    const image = '<?= addslashes($product['image'] ?? '') ?>';

    const existing = cart.find(i => i.id == id);
    if (existing) {
        existing.qty += qty;
    } else {
        cart.push({ id, name, price, image, qty });
    }
    saveCart();
    showToast(`✓ ${name} × ${qty} added to cart!`);
}
</script>

<?php require_once 'includes/footer.php'; ?>
