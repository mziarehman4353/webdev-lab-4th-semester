<?php
$pageTitle = 'Register';
require_once 'includes/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($_POST['name'] ?? '');
    $email    = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone    = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$name || !$email || !$password) {
        $error = 'Please fill all required fields.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $pdo = getDB();
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = 'Email already registered. Please login.';
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");
            $stmt->execute([$name, $email, $phone, $hashed]);
            $userId = $pdo->lastInsertId();
            $_SESSION['user_id']   = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email']= $email;
            $_SESSION['user_role'] = 'customer';
            header('Location: ' . SITE_URL . '/index.php');
            exit;
        }
    }
}

require_once 'includes/header.php';
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 24px; background: linear-gradient(135deg, #fff0f5 0%, #fce4ec 50%, #fdf2f5 100%);">
    <div style="width: 100%; max-width: 480px;">
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #f48fb1, #e91e8c); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <i class="ti ti-shopping-bag" style="color: white; font-size: 28px;"></i>
            </div>
            <h1 class="font-display" style="font-size: 28px; color: #4a3040;">Create Account</h1>
            <p style="color: #9e7a90; margin-top: 4px; font-size: 14px;">Join BlossomMart today — it's free!</p>
        </div>

        <div style="background: white; border-radius: 24px; padding: 36px; border: 1px solid var(--pink-100); box-shadow: 0 8px 40px rgba(233,30,140,0.08);">
            <?php if ($error): ?>
            <div style="background: #fce4ec; border: 1px solid #f48fb1; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px; color: #c2185b;">
                <i class="ti ti-alert-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div>
                        <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Full Name *</label>
                        <input type="text" name="name" placeholder="Your name" class="form-input" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Phone</label>
                        <input type="tel" name="phone" placeholder="+62 8xx..." class="form-input" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Email Address *</label>
                    <input type="email" name="email" placeholder="you@email.com" class="form-input" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div>
                        <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Password *</label>
                        <input type="password" name="password" placeholder="Min. 6 chars" class="form-input" required>
                    </div>
                    <div>
                        <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Confirm *</label>
                        <input type="password" name="confirm" placeholder="Repeat password" class="form-input" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-size: 15px; margin-top: 6px; border-radius: 14px;">
                    <i class="ti ti-user-plus"></i> Create Account
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--pink-100);">
                <p style="font-size: 14px; color: #9e7a90;">
                    Already have an account?
                    <a href="login.php" style="color: #c2185b; font-weight: 600; text-decoration: none;">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
