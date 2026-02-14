<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!isValidEmail($email)) {
        $error = 'Invalid email address';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        try {
            $verificationToken = generateToken();
            $passwordHash = hashPassword($password);
            
            $stmt = $pdo->prepare("
                INSERT INTO users (email, password_hash, name, verification_token) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$email, $passwordHash, $name, $verificationToken]);
            
            // Send verification email
            $verifyLink = SITE_URL . '/verify.php?token=' . $verificationToken;
            $emailBody = "
                <h2>Welcome to NestQR!</h2>
                <p>Hi {$name},</p>
                <p>Please verify your email address by clicking the link below:</p>
                <p><a href='{$verifyLink}'>Verify Email</a></p>
                <p>Or copy this link: {$verifyLink}</p>
            ";
            
            sendEmail($email, 'Verify your NestQR account', $emailBody);
            
            $success = 'Account created! Please check your email to verify your account.';
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = 'Email already registered';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="container" style="max-width:500px;margin-top:4rem;">
        <h1>Create Account</h1>
        <?php if($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        <?php if($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
        
        <form method="POST" style="margin-top:2rem;">
            <div style="margin-bottom:1rem;">
                <label>Name</label>
                <input type="text" name="name" class="input" required>
            </div>
            <div style="margin-bottom:1rem;">
                <label>Email</label>
                <input type="email" name="email" class="input" required>
            </div>
            <div style="margin-bottom:1rem;">
                <label>Password</label>
                <input type="password" name="password" class="input" required>
            </div>
            <div style="margin-bottom:1rem;">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm" class="input" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
        </form>
        <p style="text-align:center;margin-top:1rem;">
            Already have an account? <a href="/login.php">Log in</a>
        </p>
    </div>
</body>
</html>
