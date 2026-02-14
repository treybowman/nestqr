<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && verifyPassword($password, $user['password_hash'])) {
            if (!$user['is_verified']) {
                $error = 'Please verify your email before logging in';
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                
                $redirect = $_GET['redirect'] ?? '/dashboard.php';
                header('Location: ' . $redirect);
                exit;
            }
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Log In - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="container" style="max-width:500px;margin-top:4rem;">
        <h1>Log In</h1>
        <?php if($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        
        <form method="POST" style="margin-top:2rem;">
            <div style="margin-bottom:1rem;">
                <label>Email</label>
                <input type="email" name="email" class="input" required>
            </div>
            <div style="margin-bottom:1rem;">
                <label>Password</label>
                <input type="password" name="password" class="input" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
        </form>
        <p style="text-align:center;margin-top:1rem;">
            Don't have an account? <a href="/signup.php">Sign up</a>
        </p>
    </div>
</body>
</html>
