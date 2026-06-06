<?php
$pageTitle = 'About Us';
require_once 'includes/header.php';
?>

<div style="background: linear-gradient(135deg, #fff0f5 0%, #fce4ec 100%); padding: 64px 0; border-bottom: 1px solid var(--pink-100);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px; text-align: center;">
        <h1 class="section-title" style="font-size: 48px;">About BlossomMart</h1>
        <p class="section-sub" style="font-size: 18px; max-width: 600px; margin: 12px auto 0;">
            A local general store with heart — bringing fresh, quality products to your doorstep since 2020.
        </p>
    </div>
</div>

<section style="padding: 72px 0;">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;">
        <div>
            <div style="font-size: 90px; text-align: center; margin-bottom: 16px;">🌸</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <?php
                $milestones = [
                    ['2020', 'Founded in Klaten'],
                    ['200+', 'Products Available'],
                    ['1.2K+', 'Happy Customers'],
                    ['4.9★', 'Average Rating'],
                ];
                foreach ($milestones as $m):
                ?>
                <div style="background: linear-gradient(135deg, #fff0f5, #fce4ec); border-radius: 16px; padding: 16px; text-align: center; border: 1px solid var(--pink-100);">
                    <div style="font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; color: #c2185b;"><?= $m[0] ?></div>
                    <div style="font-size: 12px; color: #9e7a90; margin-top: 4px;"><?= $m[1] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div>
            <h2 class="section-title" style="font-size: 32px; margin-bottom: 16px;">Our Story</h2>
            <p style="color: #7a4f6b; font-size: 15px; line-height: 1.8; margin-bottom: 16px;">
                BlossomMart started as a small neighborhood kiosk run by a family who believed that everyone deserves access to fresh, quality food at fair prices. What began as a roadside stand in Klaten has blossomed into a full online general store serving thousands of families across Central Java.
            </p>
            <p style="color: #7a4f6b; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                We work directly with local farmers and producers to bring you the freshest groceries, household essentials, and personal care products — all curated with love and delivered with care.
            </p>
            <a href="products.php" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #f48fb1, #e91e8c); color: white; border-radius: 25px; padding: 12px 28px; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(233,30,140,0.35)'"
               onmouseout="this.style.transform=''; this.style.boxShadow=''">
                <i class="ti ti-shopping-bag"></i> Shop Our Products
            </a>
        </div>
    </div>
</section>

<section style="background: white; padding: 72px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px; text-align: center;">
        <h2 class="section-title" style="margin-bottom: 8px;">Our Values</h2>
        <p class="section-sub" style="margin-bottom: 48px;">The principles that guide everything we do</p>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
            <?php
            $values = [
                ['🌿', 'Freshness', 'Only the freshest products make it to our shelves. We source daily and stock responsibly.'],
                ['🤝', 'Community', 'We invest in local farmers and producers, keeping money in our community.'],
                ['💯', 'Transparency', 'Clear pricing, honest ingredients, no hidden fees. Ever.'],
                ['🌍', 'Sustainability', 'Eco-friendly packaging and zero-waste partnerships with our suppliers.'],
            ];
            foreach ($values as $v):
            ?>
            <div style="border-radius: 20px; padding: 28px 20px; text-align: center; border: 1px solid var(--pink-100); transition: all 0.3s;"
                 onmouseover="this.style.background='var(--pink-50)'; this.style.transform='translateY(-4px)'; this.style.borderColor='var(--pink-200)'"
                 onmouseout="this.style.background=''; this.style.transform=''; this.style.borderColor='var(--pink-100)'">
                <div style="font-size: 40px; margin-bottom: 14px;"><?= $v[0] ?></div>
                <h3 style="font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 600; color: #4a3040; margin-bottom: 10px;"><?= $v[1] ?></h3>
                <p style="font-size: 13px; color: #9e7a90; line-height: 1.7;"><?= $v[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
