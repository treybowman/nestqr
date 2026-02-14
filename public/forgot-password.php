<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        $resetToken = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?");
        $stmt->execute([$resetToken, $expires, $user['id']]);
        
        $resetLink = SITE_URL . '/reset-password.php?token=' . $resetToken;
        $emailBody = "
            <h2>Reset Your Password</h2>
            <p>Click the link below to reset your password:</p>
            <p><a href='{$resetLink}'>Reset Password</a></p>
            <p>This link expires in 1 hour.</p>
            <p>If you didn't request this, ignore this email.</p>
        ";
        
        sendEmail($email, 'Reset your NestQR password', $emailBody);
    }
    
    // Always show success to prevent email enumeration
    $success = true;
    $message = 'If that email exists, we sent password reset instructions.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="container" style="max-width:500px;margin-top:4rem;">
        <h1>Forgot Password</h1>
        
        <?php if($message): ?>
            <div class="<?php echo $success ? 'success-message' : 'error-message'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" style="margin-top:2rem;">
            <div style="margin-bottom:1rem;">
                <label>Email Address</label>
                <input type="email" name="email" class="input" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
        </form>
        
        <p style="text-align:center;margin-top:1rem;">
            <a href="/login.php">Back to Login</a>
        </p>
    </div>
</body>
</html>
