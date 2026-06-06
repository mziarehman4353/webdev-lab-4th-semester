<?php
$pageTitle = 'Home';
require_once 'includes/header.php';
$pdo = getDB();

// Fetch featured products
$featuredStmt = $pdo->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.featured = 1 AND p.status = 'active' LIMIT 6");
$featured = $featuredStmt->fetchAll();

// Fetch categories
$catStmt = $pdo->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id AND p.status='active' GROUP BY c.id ORDER BY c.name");
$categories = $catStmt->fetchAll();
?>

<!-- HERO SECTION -->
<section style="background: linear-gradient(135deg, #fff0f5 0%, #fce4ec 50%, #fdf2f5 100%); padding: 80px 0; overflow: hidden; position: relative;">
    <!-- Decorative circles -->
    <div style="position: absolute; top: -60px; right: -60px; width: 300px; height: 300px; border-radius: 50%; background: rgba(244,143,177,0.12); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -80px; left: -40px; width: 250px; height: 250px; border-radius: 50%; background: rgba(233,30,140,0.06); pointer-events: none;"></div>

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;">
        <div class="fade-in-up">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: white; border: 1px solid var(--pink-200); border-radius: 20px; padding: 6px 16px; font-size: 13px; font-weight: 600; color: #c2185b; margin-bottom: 24px;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #e91e8c; animation: pulse 1.5s infinite;"></span>
                Free delivery over Rp 150.000
            </div>
            <h1 class="font-display" style="font-size: 54px; font-weight: 700; color: #4a3040; line-height: 1.15; margin-bottom: 20px;">
                Fresh Goods,<br>
                <span style="color: #e91e8c; position: relative;">
                    Delivered
                    <svg style="position: absolute; bottom: -4px; left: 0; width: 100%;" viewBox="0 0 200 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 8C40 4 80 2 100 6C120 10 160 8 198 5" stroke="#f48fb1" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </span>
                <br>with Love 🌸
            </h1>
            <p style="font-size: 17px; color: #9e7a90; line-height: 1.7; margin-bottom: 36px; max-width: 400px;">
                Everything you need, from farm-fresh produce to everyday household essentials — curated with care, delivered to your door.
            </p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="products.php" class="btn-primary" style="font-size: 16px; padding: 14px 32px;">
                    <i class="ti ti-shopping-bag"></i> Shop Now
                </a>
                <a href="products.php?category=fresh-produce" class="btn-outline" style="font-size: 16px; padding: 13px 28px;">
                    <i class="ti ti-leaf"></i> Fresh Picks
                </a>
            </div>

            <!-- Stats -->
            <div style="display: flex; gap: 32px; margin-top: 48px;">
                <div>
                    <div style="font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; color: #c2185b;">200+</div>
                    <div style="font-size: 13px; color: #9e7a90; margin-top: 2px;">Products</div>
                </div>
                <div style="width: 1px; background: var(--pink-200);"></div>
                <div>
                    <div style="font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; color: #c2185b;">1.2K+</div>
                    <div style="font-size: 13px; color: #9e7a90; margin-top: 2px;">Happy Customers</div>
                </div>
                <div style="width: 1px; background: var(--pink-200);"></div>
                <div>
                    <div style="font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; color: #c2185b;">4.9★</div>
                    <div style="font-size: 13px; color: #9e7a90; margin-top: 2px;">Rating</div>
                </div>
            </div>
        </div>

        <!-- Hero image area -->
        <div class="fade-in-up delay-2" style="position: relative; display: flex; justify-content: center;">
            <div style="width: 420px; height: 420px; border-radius: 50%; background: linear-gradient(135deg, #fce4ec, #f8bbd0); display: flex; align-items: center; justify-content: center; position: relative;">
                <div style="font-size: 140px; line-height: 1;">🛒</div>

                <!-- Floating badges -->
                <div style="position: absolute; top: 40px; right: -20px; background: white; border-radius: 16px; padding: 12px 16px; box-shadow: 0 8px 32px rgba(233,30,140,0.15); border: 1px solid var(--pink-100);">
                    <div style="font-size: 11px; color: #9e7a90; font-weight: 500;">Today's Offer</div>
                    <div style="font-size: 16px; font-weight: 700; color: #c2185b;">Up to 30% OFF</div>
                </div>
                <div style="position: absolute; bottom: 60px; left: -30px; background: white; border-radius: 16px; padding: 12px 16px; box-shadow: 0 8px 32px rgba(233,30,140,0.15); border: 1px solid var(--pink-100);">
                    <div style="font-size: 11px; color: #9e7a90; font-weight: 500;">Fresh Arrival</div>
                    <div style="font-size: 16px;">🌿 Organic Picks</div>
                </div>
                <div style="position: absolute; top: 50%; right: -50px; transform: translateY(-50%); background: #e91e8c; color: white; border-radius: 14px; padding: 10px 14px; font-size: 13px; font-weight: 600;">
                    <i class="ti ti-truck"></i> Fast Delivery
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VALUE PROPS -->
<section style="background: white; padding: 24px 0; border-bottom: 1px solid var(--pink-100);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
        <?php
        $props = [
            ['ti-truck', 'Free Delivery', 'Orders over Rp 150k'],
            ['ti-shield-check', 'Secure Payments', 'Multiple methods'],
            ['ti-leaf', 'Farm Fresh', 'Direct from farms'],
            ['ti-headset', 'Support 7/7', '8AM – 9PM daily'],
        ];
        foreach ($props as $p):
        ?>
        <div style="display: flex; align-items: center; gap: 12px; padding: 16px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--pink-50); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="ti <?= $p[0] ?>" style="font-size: 20px; color: #e91e8c;"></i>
            </div>
            <div>
                <div style="font-weight: 600; font-size: 14px; color: #4a3040;"><?= $p[1] ?></div>
                <div style="font-size: 12px; color: #9e7a90; margin-top: 1px;"><?= $p[2] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- CATEGORIES -->
<section style="padding: 64px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-sub">Explore our curated selection across every aisle</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px;">
            <?php
            $catColors = ['#fce4ec','#e8f5e9','#fff3e0','#e3f2fd','#fce4ec','#f3e5f5'];
            $ci = 0;
            foreach ($categories as $cat):
            $bgColor = $catColors[$ci % count($catColors)]; $ci++;
            ?>
            <a href="products.php?category=<?= htmlspecialchars($cat['slug']) ?>" style="text-decoration: none;">
                <div style="background: <?= $bgColor ?>; border-radius: 20px; padding: 24px 16px; text-align: center; transition: all 0.3s; cursor: pointer; border: 2px solid transparent;"
                     onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='var(--pink-200)'; this.style.boxShadow='0 8px 24px rgba(233,30,140,0.1)'"
                     onmouseout="this.style.transform=''; this.style.borderColor='transparent'; this.style.boxShadow=''">
                    <i class="ti <?= htmlspecialchars($cat['icon']) ?>" style="font-size: 36px; color: #c2185b; display: block; margin-bottom: 10px;"></i>
                    <div style="font-weight: 600; font-size: 13px; color: #4a3040; line-height: 1.3;"><?= htmlspecialchars($cat['name']) ?></div>
                    <div style="font-size: 11px; color: #9e7a90; margin-top: 4px;"><?= $cat['product_count'] ?> items</div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section style="padding: 64px 0; background: linear-gradient(180deg, var(--cream) 0%, var(--blush) 100%);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
            <div>
                <h2 class="section-title">Featured Products</h2>
                <p class="section-sub">Our most loved picks, handpicked for you</p>
            </div>
            <a href="products.php" class="btn-outline">View All <i class="ti ti-arrow-right"></i></a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
            <?php foreach ($featured as $i => $product): ?>
            <div class="product-card fade-in-up" style="animation-delay: <?= $i * 0.08 ?>s;">
                <?php if ($product['original_price']): ?>
                <div style="position: absolute; top: 12px; left: 12px; z-index: 2;">
                    <span class="badge badge-sale">
                        -<?= round((1 - $product['price'] / $product['original_price']) * 100) ?>%
                    </span>
                </div>
                <?php endif; ?>
                <?php if ($product['image']): ?>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                <?php else: ?>
                <div class="product-img-placeholder">
                    <?php
                    $icons = ['🍎','🥑','🥛','🥚','🍵','🧴','🍞','🧹','🍊','🧀'];
                    echo $icons[$product['id'] % count($icons)];
                    ?>
                </div>
                <?php endif; ?>

                <div style="padding: 16px;">
                    <div style="font-size: 11px; color: #c2185b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                        <?= htmlspecialchars($product['cat_name'] ?? 'General') ?>
                    </div>
                    <h3 style="font-size: 15px; font-weight: 600; color: #4a3040; line-height: 1.4; margin-bottom: 8px;">
                        <?= htmlspecialchars($product['name']) ?>
                    </h3>
                    <p style="font-size: 13px; color: #9e7a90; line-height: 1.5; margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= htmlspecialchars($product['description']) ?>
                    </p>

                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div class="price"><?= formatCurrency($product['price']) ?></div>
                            <?php if ($product['original_price']): ?>
                            <div class="price-original"><?= formatCurrency($product['original_price']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn-outline" style="font-size: 12px; padding: 6px 12px;">
                                View
                            </a>
                            <button onclick="addToCart(<?= $product['id'] ?>, '<?= addslashes($product['name']) ?>', <?= $product['price'] ?>, '<?= addslashes($product['image'] ?? '') ?>')"
                                    class="btn-primary" style="font-size: 12px; padding: 6px 12px;">
                                <i class="ti ti-cart-plus"></i>
                            </button>
                        </div>
                    </div>

                    <?php if ($product['stock'] < 10 && $product['stock'] > 0): ?>
                    <div style="margin-top: 10px; font-size: 12px; color: #e91e8c; font-weight: 500;">
                        <i class="ti ti-alert-circle"></i> Only <?= $product['stock'] ?> left!
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PROMO BANNER -->
<section style="padding: 64px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div style="background: linear-gradient(135deg, #c2185b 0%, #e91e8c 50%, #f48fb1 100%); border-radius: 28px; padding: 56px 60px; display: grid; grid-template-columns: 1fr auto; gap: 40px; align-items: center; position: relative; overflow: hidden;">
            <!-- Decorative -->
            <div style="position: absolute; top: -40px; right: 200px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.08); pointer-events: none;"></div>
            <div style="position: absolute; bottom: -60px; right: 100px; width: 280px; height: 280px; border-radius: 50%; background: rgba(255,255,255,0.05); pointer-events: none;"></div>
            <div>
                <div style="display: inline-block; background: rgba(255,255,255,0.2); color: white; border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 600; margin-bottom: 16px; letter-spacing: 0.5px;">
                    LIMITED TIME OFFER
                </div>
                <h2 class="font-display" style="font-size: 40px; color: white; font-weight: 700; margin-bottom: 12px; line-height: 1.2;">
                    Get 20% OFF<br>Your First Order
                </h2>
                <p style="color: rgba(255,255,255,0.85); font-size: 16px; margin-bottom: 28px;">
                    Use code <strong>BLOSSOM20</strong> at checkout. New customers only.
                </p>
                <a href="products.php" style="display: inline-flex; align-items: center; gap: 8px; background: white; color: #c2185b; border-radius: 25px; padding: 14px 32px; font-weight: 700; font-size: 15px; text-decoration: none; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform=''">
                    Claim Offer <i class="ti ti-arrow-right"></i>
                </a>
            </div>
            <div style="font-size: 100px; text-align: center; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.2));">
                🎀
            </div>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section style="padding: 64px 0; background: white;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div style="text-align: center; margin-bottom: 48px;">
            <h2 class="section-title">Why BlossomMart?</h2>
            <p class="section-sub">We take the stress out of grocery shopping</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
            <?php
            $reasons = [
                ['🌿', 'Locally Sourced', 'We partner directly with local farmers and producers to bring you the freshest products while supporting our community.'],
                ['🚚', 'Same-Day Delivery', 'Order before 2PM and receive your groceries the same evening. Fast, reliable, and contactless options available.'],
                ['💝', 'Customer First', 'Not happy with your purchase? We offer hassle-free returns and replacements within 24 hours — no questions asked.'],
            ];
            foreach ($reasons as $r):
            ?>
            <div style="text-align: center; padding: 32px 24px; border-radius: 24px; border: 1px solid var(--pink-100); transition: all 0.3s;"
                 onmouseover="this.style.background='var(--pink-50)'; this.style.borderColor='var(--pink-200)'; this.style.transform='translateY(-4px)'"
                 onmouseout="this.style.background=''; this.style.borderColor='var(--pink-100)'; this.style.transform=''">
                <div style="font-size: 48px; margin-bottom: 16px;"><?= $r[0] ?></div>
                <h3 style="font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 600; color: #4a3040; margin-bottom: 12px;"><?= $r[1] ?></h3>
                <p style="font-size: 14px; color: #9e7a90; line-height: 1.7;"><?= $r[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.85); }
}
</style>

<?php require_once 'includes/footer.php'; ?>
