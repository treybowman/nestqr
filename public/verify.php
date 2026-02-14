<?php
require_once '../includes/config.php';

$token = $_GET['token'] ?? '';
$message = '';
$success = false;

if (!empty($token)) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ? AND is_verified = 0");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);
        $message = 'Email verified! You can now log in.';
        $success = true;
    } else {
        $message = 'Invalid or expired verification link.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Verification - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="container" style="max-width:600px;margin-top:4rem;text-align:center;">
        <h1>Email Verification</h1>
        <div class="<?php echo $success ? 'success-message' : 'error-message'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php if($success): ?>
            <a href="/login.php" class="btn btn-primary" style="margin-top:2rem;">Go to Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
