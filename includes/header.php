<?php
require_once __DIR__ . '/config.php';
$cartCount = getCartCount();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' — ' : '' ?><?= SITE_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-50:  #fff0f5;
            --pink-100: #ffd6e7;
            --pink-200: #ffadd4;
            --pink-300: #ff85bf;
            --pink-400: #f06292;
            --pink-500: #e91e8c;
            --pink-600: #c2185b;
            --rose-50:  #fdf2f5;
            --rose-100: #fce4ec;
            --cream:    #fffaf9;
            --blush:    #fef1f5;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--cream);
            color: #4a3040;
        }
        h1, h2, h3, .font-display { font-family: 'Playfair Display', serif; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--pink-50); }
        ::-webkit-scrollbar-thumb { background: var(--pink-300); border-radius: 3px; }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--pink-100);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .nav-link {
            color: #7a4f6b;
            font-weight: 500;
            font-size: 14px;
            padding: 6px 14px;
            border-radius: 20px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .nav-link:hover, .nav-link.active {
            background: var(--pink-100);
            color: var(--pink-600);
        }
        .btn-primary {
            background: linear-gradient(135deg, #f48fb1, #e91e8c);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 24px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(233, 30, 140, 0.35);
        }
        .btn-outline {
            background: transparent;
            color: var(--pink-600);
            border: 1.5px solid var(--pink-300);
            border-radius: 25px;
            padding: 8px 20px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline:hover {
            background: var(--pink-50);
            border-color: var(--pink-400);
        }

        /* Product Cards */
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--pink-100);
            transition: all 0.3s ease;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(233, 30, 140, 0.12);
            border-color: var(--pink-200);
        }
        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--pink-50), var(--rose-100));
        }
        .product-img-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--pink-50), var(--rose-100));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: var(--pink-300);
        }
        .badge {
            background: linear-gradient(135deg, #f48fb1, #e91e8c);
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 12px;
        }
        .badge-sale {
            background: linear-gradient(135deg, #ffb3c1, #ff4081);
        }
        .price { font-size: 18px; font-weight: 700; color: #c2185b; }
        .price-original { font-size: 13px; color: #b8a0b0; text-decoration: line-through; }

        /* Cart sidebar */
        .cart-overlay {
            position: fixed; inset: 0; background: rgba(74,48,64,0.4);
            z-index: 100; opacity: 0; pointer-events: none; transition: opacity 0.3s;
        }
        .cart-overlay.open { opacity: 1; pointer-events: all; }
        .cart-sidebar {
            position: fixed; top: 0; right: -420px; width: 420px; max-width: 95vw;
            height: 100vh; background: white; z-index: 101;
            transition: right 0.35s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column;
            border-left: 1px solid var(--pink-100);
        }
        .cart-sidebar.open { right: 0; }

        /* Toast */
        .toast {
            position: fixed; bottom: 24px; right: 24px;
            background: white; border: 1px solid var(--pink-200);
            border-left: 4px solid #e91e8c;
            border-radius: 12px; padding: 14px 20px;
            font-size: 14px; font-weight: 500; color: #4a3040;
            box-shadow: 0 8px 32px rgba(233,30,140,0.12);
            z-index: 200; transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            max-width: 320px;
        }
        .toast.show { transform: translateX(0); }

        /* Section heading */
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: #4a3040;
        }
        .section-sub {
            font-size: 15px;
            color: #9e7a90;
            margin-top: 6px;
        }

        /* Input styles */
        .form-input {
            width: 100%;
            border: 1.5px solid var(--pink-200);
            border-radius: 12px;
            padding: 10px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: #4a3040;
            background: white;
            transition: border-color 0.2s;
            outline: none;
        }
        .form-input:focus { border-color: var(--pink-400); }

        /* Footer */
        footer {
            background: linear-gradient(160deg, #fce4ec 0%, #fdf2f5 50%, #fff0f5 100%);
            border-top: 1px solid var(--pink-100);
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.5s ease both; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        /* Quantity input */
        .qty-btn {
            width: 30px; height: 30px;
            border: 1.5px solid var(--pink-200);
            border-radius: 8px; background: white;
            color: #c2185b; font-size: 16px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .qty-btn:hover { background: var(--pink-50); border-color: var(--pink-400); }
    </style>
</head>
<body>

<!-- Toast Notification -->
<div class="toast" id="toast"></div>

<!-- Cart Overlay -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>

<!-- Cart Sidebar -->
<div class="cart-sidebar" id="cartSidebar">
    <div style="padding: 24px; border-bottom: 1px solid var(--pink-100); display: flex; justify-content: space-between; align-items: center;">
        <h3 class="font-display" style="font-size: 20px; color: #4a3040;">
            <i class="ti ti-shopping-bag" style="color: #e91e8c;"></i> Your Cart
        </h3>
        <button onclick="toggleCart()" style="background: none; border: none; cursor: pointer; color: #9e7a90; font-size: 20px;">
            <i class="ti ti-x"></i>
        </button>
    </div>
    <div id="cartItems" style="flex: 1; overflow-y: auto; padding: 16px;"></div>
    <div id="cartFooter" style="padding: 20px; border-top: 1px solid var(--pink-100);">
        <div id="cartSummary"></div>
        <a href="<?= SITE_URL ?>/checkout.php" id="checkoutBtn" style="display: none;">
            <button class="btn-primary" style="width: 100%; justify-content: center; margin-top: 12px; padding: 14px;">
                <i class="ti ti-shield-check"></i> Proceed to Checkout
            </button>
        </a>
    </div>
</div>

<!-- Navigation -->
<nav class="navbar">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; height: 68px; gap: 16px;">
        <!-- Logo -->
        <a href="<?= SITE_URL ?>/index.php" style="text-decoration: none; display: flex; align-items: center; gap: 8px; margin-right: 16px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #f48fb1, #e91e8c); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="ti ti-shopping-bag" style="color: white; font-size: 18px;"></i>
            </div>
            <span style="font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: #c2185b; letter-spacing: -0.5px;">Blossom<span style="color: #4a3040;">Mart</span></span>
        </a>

        <!-- Nav Links -->
        <div style="display: flex; gap: 4px; flex: 1;">
            <a href="<?= SITE_URL ?>/index.php" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">Home</a>
            <a href="<?= SITE_URL ?>/products.php" class="nav-link <?= $currentPage === 'products' ? 'active' : '' ?>">Shop</a>
            <a href="<?= SITE_URL ?>/products.php?category=fresh-produce" class="nav-link">Produce</a>
            <a href="<?= SITE_URL ?>/about.php" class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>">About</a>
        </div>

        <!-- Search -->
        <div style="position: relative; flex: 0 0 220px;">
            <input type="text" id="searchInput" placeholder="Search products..." class="form-input" style="padding-left: 36px; font-size: 13px; border-radius: 20px;">
            <i class="ti ti-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #c2185b; font-size: 16px;"></i>
        </div>

        <!-- Cart Button -->
        <button onclick="toggleCart()" style="position: relative; background: none; border: none; cursor: pointer; padding: 8px;">
            <i class="ti ti-shopping-cart" style="font-size: 24px; color: #c2185b;"></i>
            <span id="cartBadge" style="
                position: absolute; top: 2px; right: 2px;
                background: #e91e8c; color: white;
                border-radius: 50%; width: 18px; height: 18px;
                font-size: 10px; font-weight: 700;
                display: flex; align-items: center; justify-content: center;
                display: <?= $cartCount > 0 ? 'flex' : 'none' ?>;">
                <?= $cartCount ?>
            </span>
        </button>

        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= SITE_URL ?>/account.php" class="btn-outline" style="font-size: 13px; padding: 7px 16px;">
            <i class="ti ti-user"></i> <?= sanitize($_SESSION['user_name'] ?? 'Account') ?>
        </a>
        <a href="<?= SITE_URL ?>/logout.php" style="color: #9e7a90; font-size: 13px; text-decoration: none;">Logout</a>
        <?php else: ?>
        <a href="<?= SITE_URL ?>/login.php" class="btn-outline" style="font-size: 13px; padding: 7px 16px;">Login</a>
        <a href="<?= SITE_URL ?>/register.php" class="btn-primary" style="font-size: 13px; padding: 8px 18px;">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>
