<?php
$pageTitle = 'Login';
require_once 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email']= $user['email'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: ' . SITE_URL . '/index.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please fill all fields.';
    }
}

require_once 'includes/header.php';
?>

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 24px; background: linear-gradient(135deg, #fff0f5 0%, #fce4ec 50%, #fdf2f5 100%);">
    <div style="width: 100%; max-width: 440px;">
        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #f48fb1, #e91e8c); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <i class="ti ti-shopping-bag" style="color: white; font-size: 28px;"></i>
            </div>
            <h1 class="font-display" style="font-size: 28px; color: #4a3040;">Welcome back</h1>
            <p style="color: #9e7a90; margin-top: 4px; font-size: 14px;">Sign in to your BlossomMart account</p>
        </div>

        <div style="background: white; border-radius: 24px; padding: 36px; border: 1px solid var(--pink-100); box-shadow: 0 8px 40px rgba(233,30,140,0.08);">
            <?php if ($error): ?>
            <div style="background: #fce4ec; border: 1px solid #f48fb1; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px; color: #c2185b;">
                <i class="ti ti-alert-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Email Address</label>
                    <div style="position: relative;">
                        <i class="ti ti-mail" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #c2185b; font-size: 16px;"></i>
                        <input type="email" name="email" placeholder="you@email.com" class="form-input" style="padding-left: 38px;" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: 600; color: #7a4f6b; display: block; margin-bottom: 6px;">Password</label>
                    <div style="position: relative;">
                        <i class="ti ti-lock" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #c2185b; font-size: 16px;"></i>
                        <input type="password" name="password" placeholder="Your password" class="form-input" style="padding-left: 38px;" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-size: 15px; margin-top: 8px; border-radius: 14px;">
                    <i class="ti ti-login"></i> Sign In
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--pink-100);">
                <p style="font-size: 14px; color: #9e7a90;">
                    Don't have an account?
                    <a href="register.php" style="color: #c2185b; font-weight: 600; text-decoration: none;">Sign up free</a>
                </p>
            </div>

            <!-- Demo credentials -->
            <div style="background: var(--pink-50); border-radius: 12px; padding: 12px 14px; margin-top: 16px; font-size: 12px; color: #9e7a90; border: 1px dashed var(--pink-200);">
                <strong style="color: #c2185b;">Demo Admin:</strong> admin@blossommart.com / password123
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
