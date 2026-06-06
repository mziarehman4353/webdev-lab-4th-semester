<?php
$pageTitle = 'Checkout';
require_once 'includes/header.php';
?>

<div style="background: linear-gradient(135deg, #fff0f5 0%, #fce4ec 100%); padding: 40px 0 28px; border-bottom: 1px solid var(--pink-100);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <h1 class="font-display" style="font-size: 32px; color: #4a3040;">Checkout</h1>
        <p style="color: #9e7a90; margin-top: 6px;">Complete your order securely</p>
    </div>
</div>

<div style="max-width: 1200px; margin: 40px auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 380px; gap: 40px;" id="checkoutLayout">

    <!-- Form -->
    <div>
        <!-- Customer Details -->
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 28px; margin-bottom: 20px;">
            <h3 class="font-display" style="font-size: 20px; color: #4a3040; margin-bottom: 20px;">
                <i class="ti ti-user" style="color: #e91e8c;"></i> Customer Information
            </h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Full Name *</label>
                    <input type="text" id="cName" placeholder="Your full name" class="form-input" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Phone Number *</label>
                    <input type="tel" id="cPhone" placeholder="+62 8xx-xxxx-xxxx" class="form-input">
                </div>
                <div style="grid-column: span 2;">
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Email Address *</label>
                    <input type="email" id="cEmail" placeholder="you@email.com" class="form-input" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 28px; margin-bottom: 20px;">
            <h3 class="font-display" style="font-size: 20px; color: #4a3040; margin-bottom: 20px;">
                <i class="ti ti-map-pin" style="color: #e91e8c;"></i> Shipping Address
            </h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Street Address *</label>
                    <input type="text" id="cStreet" placeholder="Jl. Melati No. 5, RT 02/RW 03" class="form-input">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">City *</label>
                        <input type="text" id="cCity" placeholder="Klaten" class="form-input">
                    </div>
                    <div>
                        <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Province *</label>
                        <input type="text" id="cProvince" placeholder="Jawa Tengah" class="form-input">
                    </div>
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Postal Code</label>
                    <input type="text" id="cPostal" placeholder="57419" class="form-input" style="width: 180px;">
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Order Notes (optional)</label>
                    <textarea id="cNotes" placeholder="Special delivery instructions, gate code, etc." class="form-input" rows="3" style="resize: vertical;"></textarea>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 28px;">
            <h3 class="font-display" style="font-size: 20px; color: #4a3040; margin-bottom: 20px;">
                <i class="ti ti-credit-card" style="color: #e91e8c;"></i> Payment Method
            </h3>
            <div style="display: flex; flex-direction: column; gap: 10px;" id="paymentMethods">
                <?php
                $methods = [
                    ['cod', 'ti-cash', 'Cash on Delivery', 'Pay when your order arrives'],
                    ['transfer', 'ti-building-bank', 'Bank Transfer', 'BCA / Mandiri / BNI / BRI'],
                    ['ewallet', 'ti-device-mobile', 'E-Wallet', 'GoPay / OVO / Dana / ShopeePay'],
                ];
                foreach ($methods as $i => $m):
                ?>
                <label style="display: flex; align-items: center; gap: 14px; border: 1.5px solid <?= $i === 0 ? 'var(--pink-400)' : 'var(--pink-200)' ?>; border-radius: 14px; padding: 14px 18px; cursor: pointer; background: <?= $i === 0 ? 'var(--pink-50)' : 'white' ?>; transition: all 0.2s;" class="payment-opt">
                    <input type="radio" name="payment" value="<?= $m[0] ?>" <?= $i === 0 ? 'checked' : '' ?> style="accent-color: #e91e8c;" onchange="selectPayment(this.closest('label'))">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--pink-100); display: flex; align-items: center; justify-content: center;">
                        <i class="ti <?= $m[1] ?>" style="font-size: 20px; color: #e91e8c;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 14px; color: #4a3040;"><?= $m[2] ?></div>
                        <div style="font-size: 12px; color: #9e7a90; margin-top: 2px;"><?= $m[3] ?></div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div>
        <div style="background: white; border-radius: 20px; border: 1px solid var(--pink-100); padding: 28px; position: sticky; top: 90px;">
            <h3 class="font-display" style="font-size: 20px; color: #4a3040; margin-bottom: 20px;">
                <i class="ti ti-receipt" style="color: #e91e8c;"></i> Order Summary
            </h3>

            <!-- Cart Items -->
            <div id="checkoutItems" style="max-height: 320px; overflow-y: auto; margin-bottom: 20px;"></div>

            <!-- Coupon -->
            <div style="display: flex; gap: 8px; margin-bottom: 20px; padding: 16px; background: var(--pink-50); border-radius: 12px; border: 1px dashed var(--pink-300);">
                <input type="text" id="couponInput" placeholder="Promo code (e.g. BLOSSOM20)" class="form-input" style="font-size: 13px; border-radius: 10px;">
                <button onclick="applyCoupon()" class="btn-outline" style="font-size: 12px; padding: 8px 14px; border-radius: 10px; white-space: nowrap;">Apply</button>
            </div>

            <!-- Totals -->
            <div id="checkoutSummary" style="font-size: 14px; color: #7a4f6b;"></div>

            <!-- Place Order -->
            <button onclick="placeOrder()" class="btn-primary" style="width: 100%; justify-content: center; padding: 16px; font-size: 16px; margin-top: 20px; border-radius: 16px;">
                <i class="ti ti-shield-check"></i> Place Order
            </button>

            <p style="text-align: center; font-size: 12px; color: #b8a0b0; margin-top: 12px;">
                <i class="ti ti-lock"></i> Secure & encrypted checkout
            </p>
        </div>
    </div>
</div>

<!-- Success Modal (hidden initially via JS) -->
<div id="successModal" style="display: none; position: fixed; inset: 0; background: rgba(74,48,64,0.5); z-index: 200; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 28px; padding: 48px; max-width: 480px; width: 90%; text-align: center; animation: fadeInUp 0.4s ease;">
        <div style="font-size: 64px; margin-bottom: 16px;">🎀</div>
        <h2 class="font-display" style="font-size: 28px; color: #4a3040; margin-bottom: 10px;">Order Placed!</h2>
        <p style="color: #9e7a90; font-size: 15px; margin-bottom: 8px;">Thank you for your order.</p>
        <p id="successOrderNum" style="color: #c2185b; font-size: 18px; font-weight: 700; margin-bottom: 24px;"></p>
        <p style="color: #9e7a90; font-size: 14px; margin-bottom: 32px;">A confirmation will be sent to your email. We'll prepare your order with love! 💕</p>
        <a href="index.php" class="btn-primary" style="justify-content: center; padding: 14px 32px; font-size: 15px;">Continue Shopping</a>
    </div>
</div>

<script>
function selectPayment(el) {
    document.querySelectorAll('.payment-opt').forEach(l => {
        l.style.borderColor = 'var(--pink-200)';
        l.style.background = 'white';
    });
    el.style.borderColor = 'var(--pink-400)';
    el.style.background = 'var(--pink-50)';
}

let discount = 0;

function applyCoupon() {
    const code = document.getElementById('couponInput').value.trim().toUpperCase();
    if (code === 'BLOSSOM20') {
        discount = 0.2;
        showToast('🎉 Coupon applied! 20% off!');
        renderCheckoutSummary();
    } else {
        showToast('Invalid coupon code.');
    }
}

function renderCheckoutItems() {
    const el = document.getElementById('checkoutItems');
    if (!el) return;
    if (cart.length === 0) {
        el.innerHTML = '<p style="text-align: center; color: #9e7a90; padding: 20px;">No items in cart.</p>';
        return;
    }
    el.innerHTML = cart.map(item => `
        <div style="display: flex; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--pink-100);">
            <div style="width: 44px; height: 44px; border-radius: 10px; background: var(--pink-50); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                ${item.image ? `<img src="${item.image}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">` : '🛒'}
            </div>
            <div style="flex: 1; min-width: 0;">
                <p style="font-size: 13px; font-weight: 500; color: #4a3040; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.name}</p>
                <p style="font-size: 12px; color: #9e7a90; margin-top: 2px;">× ${item.qty}</p>
            </div>
            <span style="font-size: 13px; font-weight: 600; color: #c2185b; white-space: nowrap;">${formatRp(item.price * item.qty)}</span>
        </div>
    `).join('');
}

function renderCheckoutSummary() {
    const el = document.getElementById('checkoutSummary');
    if (!el) return;
    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const discountAmt = Math.round(subtotal * discount);
    const shipping = 15000;
    const total = subtotal - discountAmt + shipping;

    el.innerHTML = `
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;"><span>Subtotal</span><span style="font-weight: 600;">${formatRp(subtotal)}</span></div>
        ${discount > 0 ? `<div style="display: flex; justify-content: space-between; margin-bottom: 8px; color: #388e3c;"><span>Discount (20%)</span><span style="font-weight: 600;">−${formatRp(discountAmt)}</span></div>` : ''}
        <div style="display: flex; justify-content: space-between; margin-bottom: 14px;"><span>Shipping</span><span style="font-weight: 600;">${formatRp(shipping)}</span></div>
        <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; color: #c2185b; border-top: 1px solid var(--pink-100); padding-top: 14px;">
            <span>Total</span><span>${formatRp(total)}</span>
        </div>`;
}

function placeOrder() {
    // Validate
    const name = document.getElementById('cName').value.trim();
    const phone = document.getElementById('cPhone').value.trim();
    const email = document.getElementById('cEmail').value.trim();
    const street = document.getElementById('cStreet').value.trim();
    const city = document.getElementById('cCity').value.trim();
    const province = document.getElementById('cProvince').value.trim();
    const notes = document.getElementById('cNotes').value.trim();
    const payment = document.querySelector('input[name="payment"]:checked')?.value || 'cod';

    if (!name || !phone || !email || !street || !city || !province) {
        showToast('⚠️ Please fill all required fields!');
        return;
    }
    if (cart.length === 0) {
        showToast('Your cart is empty!');
        return;
    }

    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const discountAmt = Math.round(subtotal * discount);
    const shipping = 15000;
    const total = subtotal - discountAmt + shipping;
    const address = `${street}, ${city}, ${province}`;

    const orderData = { name, phone, email, address, notes, payment, cart, subtotal, shipping, discount: discountAmt, total };

    fetch(`${SITE_URL}/order.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Clear cart
            cart = [];
            saveCart();
            // Show success
            document.getElementById('successOrderNum').textContent = 'Order #' + data.order_number;
            const modal = document.getElementById('successModal');
            modal.style.display = 'flex';
        } else {
            showToast('Error: ' + (data.error || 'Could not place order.'));
        }
    })
    .catch(() => showToast('Network error. Please try again.'));
}

// Init on load
document.addEventListener('DOMContentLoaded', () => {
    renderCheckoutItems();
    renderCheckoutSummary();
});
</script>

<?php require_once 'includes/footer.php'; ?>
