
<!-- Footer -->
<footer style="margin-top: 80px; padding: 60px 0 32px;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 48px;">
            <!-- Brand -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #f48fb1, #e91e8c); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="ti ti-shopping-bag" style="color: white; font-size: 18px;"></i>
                    </div>
                    <span style="font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: #c2185b;">Blossom<span style="color: #4a3040;">Mart</span></span>
                </div>
                <p style="color: #9e7a90; font-size: 14px; line-height: 1.8; max-width: 280px;">
                    Your neighborhood general store, reimagined. Fresh products, lovingly curated for your everyday needs.
                </p>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <a href="#" style="width: 36px; height: 36px; border-radius: 50%; background: white; border: 1px solid var(--pink-200); display: flex; align-items: center; justify-content: center; color: #c2185b; text-decoration: none; transition: all 0.2s;"
                       onmouseover="this.style.background='var(--pink-100)'" onmouseout="this.style.background='white'">
                        <i class="ti ti-brand-instagram"></i>
                    </a>
                    <a href="#" style="width: 36px; height: 36px; border-radius: 50%; background: white; border: 1px solid var(--pink-200); display: flex; align-items: center; justify-content: center; color: #c2185b; text-decoration: none; transition: all 0.2s;"
                       onmouseover="this.style.background='var(--pink-100)'" onmouseout="this.style.background='white'">
                        <i class="ti ti-brand-facebook"></i>
                    </a>
                    <a href="#" style="width: 36px; height: 36px; border-radius: 50%; background: white; border: 1px solid var(--pink-200); display: flex; align-items: center; justify-content: center; color: #c2185b; text-decoration: none; transition: all 0.2s;"
                       onmouseover="this.style.background='var(--pink-100)'" onmouseout="this.style.background='white'">
                        <i class="ti ti-brand-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Shop Links -->
            <div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 600; color: #4a3040; margin-bottom: 16px;">Shop</h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px;">
                    <?php
                    $footerCats = [
                        ['Fresh Produce', 'fresh-produce'],
                        ['Dairy & Eggs', 'dairy-eggs'],
                        ['Snacks', 'snacks-beverages'],
                        ['Bakery', 'bakery'],
                        ['Personal Care', 'personal-care'],
                    ];
                    foreach ($footerCats as $cat):
                    ?>
                    <li><a href="<?= SITE_URL ?>/products.php?category=<?= $cat[1] ?>" style="color: #9e7a90; text-decoration: none; font-size: 14px; transition: color 0.2s;"
                           onmouseover="this.style.color='#c2185b'" onmouseout="this.style.color='#9e7a90'"><?= $cat[0] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Info Links -->
            <div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 600; color: #4a3040; margin-bottom: 16px;">Info</h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px;">
                    <li><a href="<?= SITE_URL ?>/about.php" style="color: #9e7a90; text-decoration: none; font-size: 14px; transition: color 0.2s;"
                           onmouseover="this.style.color='#c2185b'" onmouseout="this.style.color='#9e7a90'">About Us</a></li>
                    <li><a href="#" style="color: #9e7a90; text-decoration: none; font-size: 14px; transition: color 0.2s;"
                           onmouseover="this.style.color='#c2185b'" onmouseout="this.style.color='#9e7a90'">Privacy Policy</a></li>
                    <li><a href="#" style="color: #9e7a90; text-decoration: none; font-size: 14px; transition: color 0.2s;"
                           onmouseover="this.style.color='#c2185b'" onmouseout="this.style.color='#9e7a90'">Terms of Service</a></li>
                    <li><a href="#" style="color: #9e7a90; text-decoration: none; font-size: 14px; transition: color 0.2s;"
                           onmouseover="this.style.color='#c2185b'" onmouseout="this.style.color='#9e7a90'">Return Policy</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 600; color: #4a3040; margin-bottom: 16px;">Contact</h4>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; gap: 8px; align-items: flex-start;">
                        <i class="ti ti-map-pin" style="color: #e91e8c; font-size: 16px; margin-top: 2px;"></i>
                        <span style="font-size: 14px; color: #9e7a90; line-height: 1.6;">Jl. Melati No. 12, Klaten, Jawa Tengah</span>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <i class="ti ti-phone" style="color: #e91e8c; font-size: 16px;"></i>
                        <span style="font-size: 14px; color: #9e7a90;">+62 274 123 456</span>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <i class="ti ti-mail" style="color: #e91e8c; font-size: 16px;"></i>
                        <span style="font-size: 14px; color: #9e7a90;">hello@blossommart.com</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div style="border-top: 1px solid var(--pink-100); padding-top: 24px; display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: 13px; color: #b8a0b0;">© <?= date('Y') ?> BlossomMart. Made with <i class="ti ti-heart-filled" style="color: #e91e8c;"></i> in Klaten.</p>
            <div style="display: flex; gap: 12px; align-items: center;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" style="height: 20px; opacity: 0.5;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" style="height: 20px; opacity: 0.5;">
                <span style="font-size: 12px; color: #b8a0b0;">COD Available</span>
            </div>
        </div>
    </div>
</footer>

<!-- Core JS -->
<script>
const SITE_URL = '<?= SITE_URL ?>';
const CURRENCY = '<?= CURRENCY ?>';

// ── Cart State (synced with server via sessionStorage fallback) ──
let cart = JSON.parse(localStorage.getItem('bm_cart') || '[]');

function saveCart() {
    localStorage.setItem('bm_cart', JSON.stringify(cart));
    updateCartBadge();
    renderCart();
    // Sync to server session
    fetch(`${SITE_URL}/cart.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'sync', cart })
    });
}

function addToCart(id, name, price, image) {
    const existing = cart.find(i => i.id == id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ id, name, price: parseFloat(price), image, qty: 1 });
    }
    saveCart();
    showToast(`✓ ${name} added to cart!`);
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id != id);
    saveCart();
}

function updateQty(id, delta) {
    const item = cart.find(i => i.id == id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    saveCart();
}

function getCartTotal() {
    return cart.reduce((sum, i) => sum + i.price * i.qty, 0);
}

function getCartCount() {
    return cart.reduce((sum, i) => sum + i.qty, 0);
}

function updateCartBadge() {
    const count = getCartCount();
    const badge = document.getElementById('cartBadge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

function formatRp(amount) {
    return CURRENCY + ' ' + amount.toLocaleString('id-ID');
}

function renderCart() {
    const el = document.getElementById('cartItems');
    const summary = document.getElementById('cartSummary');
    const btn = document.getElementById('checkoutBtn');
    if (!el) return;

    if (cart.length === 0) {
        el.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; color: #b8a0b0;">
                <i class="ti ti-shopping-cart-off" style="font-size: 56px; display: block; margin-bottom: 12px;"></i>
                <p style="font-size: 15px;">Your cart is empty</p>
                <p style="font-size: 13px; margin-top: 6px;">Add some lovely products!</p>
            </div>`;
        if (summary) summary.innerHTML = '';
        if (btn) btn.style.display = 'none';
        return;
    }

    el.innerHTML = cart.map(item => `
        <div style="display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--pink-100);">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: var(--pink-50); flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                ${item.image ? `<img src="${item.image}" style="width: 100%; height: 100%; object-fit: cover;">` : `<i class="ti ti-shopping-bag" style="font-size: 24px; color: var(--pink-300);"></i>`}
            </div>
            <div style="flex: 1; min-width: 0;">
                <p style="font-size: 13px; font-weight: 500; color: #4a3040; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.name}</p>
                <p style="font-size: 13px; color: #c2185b; font-weight: 600; margin-top: 4px;">${formatRp(item.price)}</p>
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                    <button class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
                    <span style="font-size: 14px; font-weight: 600; min-width: 20px; text-align: center;">${item.qty}</span>
                    <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                    <button onclick="removeFromCart(${item.id})" style="background: none; border: none; color: #d48ba0; cursor: pointer; margin-left: auto; font-size: 16px;">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    const subtotal = getCartTotal();
    const shipping = 15000;
    const total = subtotal + shipping;

    if (summary) {
        summary.innerHTML = `
            <div style="font-size: 14px; color: #7a4f6b;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Subtotal</span><span style="font-weight: 600;">${formatRp(subtotal)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span>Shipping</span><span style="font-weight: 600;">${formatRp(shipping)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 700; color: #c2185b; border-top: 1px solid var(--pink-100); padding-top: 12px;">
                    <span>Total</span><span>${formatRp(total)}</span>
                </div>
            </div>`;
    }

    if (btn) btn.style.display = 'block';
}

function toggleCart() {
    const sidebar = document.getElementById('cartSidebar');
    const overlay = document.getElementById('cartOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
}

// Toast
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

// Search
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    let searchTimer;
    searchInput.addEventListener('input', e => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const q = e.target.value.trim();
            if (q.length > 1) {
                window.location.href = `${SITE_URL}/products.php?q=${encodeURIComponent(q)}`;
            }
        }, 500);
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                const q = e.target.value.trim();
                if (q) window.location.href = `${SITE_URL}/products.php?q=${encodeURIComponent(q)}`;
            }
        });
    });
}

// Init
updateCartBadge();
renderCart();
</script>
</body>
</html>
