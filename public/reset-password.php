<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = false;

// Verify token
$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $error = 'Invalid or expired reset link';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $password = $_POST['password'];
    $confirm = $_POST['password_confirm'];
    
    if ($password !== $confirm) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        $hash = hashPassword($password);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
        $stmt->execute([$hash, $user['id']]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="container" style="max-width:500px;margin-top:4rem;">
        <h1>Reset Password</h1>
        
        <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success-message">Password reset successfully!</div>
            <a href="/login.php" class="btn btn-primary btn-block" style="margin-top:1rem;">Go to Login</a>
        <?php elseif($user): ?>
            <form method="POST" style="margin-top:2rem;">
                <div style="margin-bottom:1rem;">
                    <label>New Password</label>
                    <input type="password" name="password" class="input" required minlength="8">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirm" class="input" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
