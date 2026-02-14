<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$user = getCurrentUser();
$userId = getCurrentUserId();

$message = getFlashMessage();
$availableDomains = array_keys($ACTIVE_DOMAINS);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $name = sanitize($_POST['name']);
        $phone = sanitize($_POST['phone']);
        $bio = sanitize($_POST['bio']);
        
        $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, bio = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $bio, $userId]);
        
        redirect('/settings.php', 'Profile updated successfully!', 'success');
    }
    
    if ($action === 'update_preferences') {
        $theme = $_POST['theme_preference'];
        $authPref = $_POST['auth_preference'];
        $domain = sanitize($_POST['preferred_domain']);
        
        $stmt = $pdo->prepare("UPDATE users SET theme_preference = ?, auth_preference = ?, preferred_domain = ? WHERE id = ?");
        $stmt->execute([$theme, $authPref, $domain, $userId]);
        
        redirect('/settings.php', 'Preferences updated!', 'success');
    }
    
    if ($action === 'update_branding') {
        if (in_array($user['plan_tier'], ['pro', 'unlimited', 'company'])) {
            $brandColor = sanitize($_POST['custom_brand_color']);
            
            $logoUrl = $user['custom_brand_logo'];
            if (!empty($_FILES['logo']['name'])) {
                $upload = uploadImage($_FILES['logo'], 'logos');
                if ($upload['success']) {
                    $logoUrl = $upload['url'];
                }
            }
            
            $stmt = $pdo->prepare("UPDATE users SET custom_brand_logo = ?, custom_brand_color = ? WHERE id = ?");
            $stmt->execute([$logoUrl, $brandColor, $userId]);
            
            redirect('/settings.php', 'Branding updated!', 'success');
        }
    }
    
    if ($action === 'change_password') {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if (verifyPassword($currentPassword, $user['password_hash'])) {
            if ($newPassword === $confirmPassword && strlen($newPassword) >= 8) {
                $newHash = hashPassword($newPassword);
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$newHash, $userId]);
                
                redirect('/settings.php', 'Password changed successfully!', 'success');
            } else {
                redirect('/settings.php', 'New passwords do not match or too short', 'error');
            }
        } else {
            redirect('/settings.php', 'Current password incorrect', 'error');
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo"><span class="logo-text">NestQR</span></div>
            <div class="nav">
                <a href="/dashboard.php">Dashboard</a>
                <a href="/settings.php" style="font-weight:600;">Settings</a>
                <a href="/logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container" style="max-width:800px;margin-top:2rem;">
        <h1>Settings</h1>
        
        <?php if($message): ?>
            <div class="<?php echo $message['type'] === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top:2rem;">
            <div class="feature-card" style="margin-bottom:2rem;">
                <h2>Profile Information</h2>
                <form method="POST" style="margin-top:1rem;">
                    <input type="hidden" name="action" value="update_profile">
                    <div style="margin-bottom:1rem;">
                        <label>Name</label>
                        <input type="text" name="name" class="input" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label>Email</label>
                        <input type="email" class="input" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        <small style="color:var(--gray-600);">Email cannot be changed</small>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label>Phone</label>
                        <input type="tel" name="phone" class="input" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label>Bio</label>
                        <textarea name="bio" class="input" rows="3"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Profile</button>
                </form>
            </div>
            
            <div class="feature-card" style="margin-bottom:2rem;">
                <h2>Preferences</h2>
                <form method="POST" style="margin-top:1rem;">
                    <input type="hidden" name="action" value="update_preferences">
                    <div style="margin-bottom:1rem;">
                        <label>Theme</label>
                        <select name="theme_preference" class="input">
                            <option value="light" <?php echo $user['theme_preference'] === 'light' ? 'selected' : ''; ?>>Light</option>
                            <option value="dark" <?php echo $user['theme_preference'] === 'dark' ? 'selected' : ''; ?>>Dark</option>
                        </select>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label>Authentication Method</label>
                        <select name="auth_preference" class="input">
                            <option value="login" <?php echo $user['auth_preference'] === 'login' ? 'selected' : ''; ?>>Full Login</option>
                            <option value="pin" <?php echo $user['auth_preference'] === 'pin' ? 'selected' : ''; ?>>PIN Code</option>
                            <option value="magic_link" <?php echo $user['auth_preference'] === 'magic_link' ? 'selected' : ''; ?>>Magic Link</option>
                        </select>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label>Preferred Domain</label>
                        <select name="preferred_domain" class="input">
                            <?php foreach($ACTIVE_DOMAINS as $key => $domain): ?>
                                <option value="<?php echo $key; ?>" <?php echo $user['preferred_domain'] === $key ? 'selected' : ''; ?>>
                                    <?php echo $domain['name']; ?> (<?php echo $key; ?>.com)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </form>
            </div>
            
            <?php if (in_array($user['plan_tier'], ['pro', 'unlimited', 'company'])): ?>
                <div class="feature-card" style="margin-bottom:2rem;">
                    <h2>Custom Branding <span style="background:var(--purple-primary);color:white;padding:0.25rem 0.5rem;border-radius:0.25rem;font-size:0.75rem;">PRO</span></h2>
                    <form method="POST" enctype="multipart/form-data" style="margin-top:1rem;">
                        <input type="hidden" name="action" value="update_branding">
                        <div style="margin-bottom:1rem;">
                            <label>Logo</label>
                            <?php if($user['custom_brand_logo']): ?>
                                <img src="<?php echo htmlspecialchars($user['custom_brand_logo']); ?>" style="max-width:200px;margin-bottom:1rem;display:block;">
                            <?php endif; ?>
                            <input type="file" name="logo" accept="image/*" class="input">
                        </div>
                        <div style="margin-bottom:1rem;">
                            <label>Brand Color</label>
                            <input type="color" name="custom_brand_color" class="input" value="<?php echo $user['custom_brand_color'] ?: '#8e63f5'; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Branding</button>
                    </form>
                </div>
            <?php endif; ?>
            
            <div class="feature-card" style="margin-bottom:2rem;">
                <h2>Change Password</h2>
                <form method="POST" style="margin-top:1rem;">
                    <input type="hidden" name="action" value="change_password">
                    <div style="margin-bottom:1rem;">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="input" required>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="input" required minlength="8">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" class="input" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>
            
            <div class="feature-card">
                <h2>Plan & Billing</h2>
                <p>Current Plan: <strong style="text-transform:capitalize;"><?php echo $user['plan_tier']; ?></strong></p>
                <?php if($user['plan_tier'] === 'free'): ?>
                    <a href="/upgrade.php" class="btn btn-primary" style="margin-top:1rem;">Upgrade to Pro</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
