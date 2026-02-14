<?php
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

// Honeypot check
if (!empty($_POST['website'])) {
    header('Location: /?success=1');
    exit;
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /?error=' . urlencode('Invalid email address'));
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO email_capture (email, ip_address, user_agent) VALUES (?, ?, ?)");
    $stmt->execute([
        $email,
        $_SERVER['REMOTE_ADDR'] ?? null,
        $_SERVER['HTTP_USER_AGENT'] ?? null
    ]);
    
    header('Location: /?success=1');
} catch(PDOException $e) {
    if ($e->getCode() == 23000) { // Duplicate entry
        header('Location: /?success=1'); // Don't reveal if already exists
    } else {
        header('Location: /?error=' . urlencode('Something went wrong. Please try again.'));
    }
}
exit;
