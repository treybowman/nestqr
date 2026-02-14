<?php
/**
 * NestQR Helper Functions
 */

/**
 * Generate a random secure token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Generate a unique short code for QR
 */
function generateShortCode($length = 6) {
    $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // No 0,O,1,I to avoid confusion
    $code = '';
    $max = strlen($characters) - 1;
    
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[random_int(0, $max)];
    }
    
    return $code;
}

/**
 * Sanitize input data
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash password securely
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user data
 */
function getCurrentUser() {
    global $pdo;
    
    if (!isLoggedIn()) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([getCurrentUserId()]);
    return $stmt->fetch();
}

/**
 * Require authentication
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: /login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Get QR slot count for user
 */
function getUserQRSlotCount($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM qr_slots WHERE user_id = ?");
    $stmt->execute([$userId]);
    return (int)$stmt->fetchColumn();
}

/**
 * Get QR slot limit based on plan
 */
function getQRSlotLimit($planTier) {
    switch ($planTier) {
        case 'free':
            return FREE_QR_LIMIT;
        case 'pro':
            return PRO_QR_LIMIT;
        case 'unlimited':
        case 'company':
            return UNLIMITED_QR_LIMIT;
        default:
            return FREE_QR_LIMIT;
    }
}

/**
 * Check if user can create more QR slots
 */
function canCreateQRSlot($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT plan_tier FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) return false;
    
    $currentCount = getUserQRSlotCount($userId);
    $limit = getQRSlotLimit($user['plan_tier']);
    
    return $currentCount < $limit;
}

/**
 * Get available icons for user based on plan
 */
function getAvailableIcons($planTier) {
    global $pdo;
    
    if (in_array($planTier, ['pro', 'unlimited', 'company'])) {
        // Pro users get all icons
        $stmt = $pdo->prepare("SELECT * FROM icon_library WHERE is_active = 1 ORDER BY sort_order");
        $stmt->execute();
    } else {
        // Free users only get free tier icons
        $stmt = $pdo->prepare("SELECT * FROM icon_library WHERE is_active = 1 AND tier = 'free' ORDER BY sort_order");
        $stmt->execute();
    }
    
    return $stmt->fetchAll();
}

/**
 * Send email using PHPMailer
 */
function sendEmail($to, $subject, $body, $isHTML = true) {
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML($isHTML);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email send failed: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Format price for display
 */
function formatPrice($price) {
    return '$' . number_format($price, 0);
}

/**
 * Get subdomain from current request
 */
function getSubdomain() {
    $host = $_SERVER['HTTP_HOST'];
    $parts = explode('.', $host);
    
    // If we have more than 2 parts and first part isn't 'www'
    if (count($parts) > 2 && $parts[0] !== 'www') {
        return $parts[0];
    }
    
    return null;
}

/**
 * Get domain key (nestqr, nestatl, etc)
 */
function getDomainKey() {
    global $ACTIVE_DOMAINS;
    
    $host = $_SERVER['HTTP_HOST'];
    
    foreach ($ACTIVE_DOMAINS as $key => $domain) {
        if (strpos($host, $key) !== false) {
            return $key;
        }
    }
    
    return 'nestqr'; // Default
}

/**
 * Check if icon can be changed (within 24 hour grace period)
 */
function canChangeIcon($qrSlotId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT icon_locked_at FROM qr_slots WHERE id = ?");
    $stmt->execute([$qrSlotId]);
    $slot = $stmt->fetch();
    
    if (!$slot || $slot['icon_locked_at'] === null) {
        return true; // Not locked yet
    }
    
    $lockedAt = new DateTime($slot['icon_locked_at']);
    $now = new DateTime();
    $diff = $now->diff($lockedAt);
    
    return $diff->h < 24 && $diff->days === 0;
}

/**
 * Lock icon after 24 hours if not already locked
 */
function lockIconIfNeeded($qrSlotId) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        UPDATE qr_slots 
        SET icon_locked_at = NOW() 
        WHERE id = ? AND icon_locked_at IS NULL AND TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 24
    ");
    $stmt->execute([$qrSlotId]);
}

/**
 * Log scan analytics
 */
function logScan($qrSlotId, $listingId = null) {
    global $pdo;
    
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $referrer = $_SERVER['HTTP_REFERER'] ?? null;
    
    $stmt = $pdo->prepare("
        INSERT INTO scan_analytics (qr_slot_id, listing_id, ip_address, user_agent, referrer)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$qrSlotId, $listingId, $ipAddress, $userAgent, $referrer]);
    
    // Update total scans count
    $stmt = $pdo->prepare("UPDATE qr_slots SET total_scans = total_scans + 1 WHERE id = ?");
    $stmt->execute([$qrSlotId]);
}

/**
 * Moderate image using external API (if enabled)
 */
function moderateImage($imageUrl) {
    if (!MODERATION_ENABLED) {
        return ['safe' => true, 'message' => 'Moderation disabled'];
    }
    
    // This is a placeholder - implement actual API call based on your chosen service
    // Example for Sightengine:
    /*
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sightengine.com/1.0/check.json');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'url' => $imageUrl,
        'models' => 'nudity,offensive,gore',
        'api_user' => MODERATION_API_USER,
        'api_secret' => MODERATION_API_SECRET
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    
    // Check results and return safe/unsafe
    */
    
    return ['safe' => true, 'message' => 'Image approved'];
}

/**
 * Upload and process image
 */
function uploadImage($file, $type = 'photos') {
    // Check file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'message' => 'File too large. Maximum ' . (MAX_UPLOAD_SIZE / 1048576) . 'MB'];
    }
    
    // Check file type
    if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, and WebP allowed.'];
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_', true) . '.' . $extension;
    $uploadPath = UPLOAD_PATH . $type . '/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $url = UPLOAD_URL . $type . '/' . $filename;
        
        // Optional: Moderate image
        $moderation = moderateImage($url);
        
        return [
            'success' => true,
            'url' => $url,
            'filename' => $filename,
            'moderation' => $moderation
        ];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

/**
 * Redirect with message
 */
function redirect($url, $message = null, $type = 'info') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = [
            'text' => $_SESSION['flash_message'],
            'type' => $_SESSION['flash_type'] ?? 'info'
        ];
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return $message;
    }
    return null;
}

/**
 * Generate QR code with logos
 * Requires: composer require endroid/qr-code
 */
function generateQRCode($shortCode, $iconSlug) {
    require_once __DIR__ . '/../vendor/autoload.php';
    
    use Endroid\QrCode\Builder\Builder;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
    use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
    use Endroid\QrCode\Writer\PngWriter;
    
    $url = SITE_URL . '/' . $shortCode;
    
    // Create QR code
    $result = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data($url)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(QR_CODE_SIZE)
        ->margin(10)
        ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->build();
    
    // Save base QR
    $qrPath = UPLOAD_PATH . 'qr-codes/qr_' . $shortCode . '_base.png';
    $result->saveToFile($qrPath);
    
    // Now add logos using GD
    $qrImage = imagecreatefrompng($qrPath);
    
    // Add center logo (NestQR logo)
    $logoPath = __DIR__ . '/../public/assets/images/nestqr-icon.png';
    if (file_exists($logoPath)) {
        $logo = imagecreatefrompng($logoPath);
        $logoSize = (QR_CODE_SIZE * QR_LOGO_SIZE_PERCENT) / 100;
        $logoResized = imagescale($logo, $logoSize, $logoSize);
        
        $x = (QR_CODE_SIZE - $logoSize) / 2;
        $y = (QR_CODE_SIZE - $logoSize) / 2;
        
        imagecopy($qrImage, $logoResized, $x, $y, 0, 0, $logoSize, $logoSize);
        imagedestroy($logo);
        imagedestroy($logoResized);
    }
    
    // Add corner icon
    $iconPath = __DIR__ . '/../public/assets/icons/' . $iconSlug . '.png';
    if (file_exists($iconPath)) {
        $icon = imagecreatefrompng($iconPath);
        $iconSize = (QR_CODE_SIZE * QR_ICON_SIZE_PERCENT) / 100;
        $iconResized = imagescale($icon, $iconSize, $iconSize);
        
        $x = QR_CODE_SIZE - $iconSize - 30;
        $y = QR_CODE_SIZE - $iconSize - 30;
        
        imagecopy($qrImage, $iconResized, $x, $y, 0, 0, $iconSize, $iconSize);
        imagedestroy($icon);
        imagedestroy($iconResized);
    }
    
    // Save final QR
    $finalPath = UPLOAD_PATH . 'qr-codes/qr_' . $shortCode . '_' . $iconSlug . '.png';
    imagepng($qrImage, $finalPath);
    imagedestroy($qrImage);
    
    // Also create high-res version for printing
    $resultHD = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data($url)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(QR_CODE_PRINT_SIZE)
        ->margin(50)
        ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->build();
    
    $qrPathHD = UPLOAD_PATH . 'qr-codes/qr_' . $shortCode . '_print.png';
    $resultHD->saveToFile($qrPathHD);
    
    return 'qr_' . $shortCode . '_' . $iconSlug . '.png';
}
