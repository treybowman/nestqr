<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$shortCode = sanitize($_GET['code'] ?? '');

if (empty($shortCode)) {
    header('Location: /');
    exit;
}

// Get QR slot and listing
$stmt = $pdo->prepare("
    SELECT 
        qs.*,
        l.*,
        u.name as agent_name,
        u.phone as agent_phone,
        u.email as agent_email,
        u.photo_url as agent_photo
    FROM qr_slots qs
    LEFT JOIN listings l ON qs.current_listing_id = l.id
    LEFT JOIN users u ON qs.user_id = u.id
    WHERE qs.short_code = ?
");
$stmt->execute([$shortCode]);
$data = $stmt->fetch();

if (!$data) {
    echo "QR code not found";
    exit;
}

// Log the scan
logScan($data['id'], $data['current_listing_id']);

// If no listing assigned, show assignment screen (if agent is logged in)
if (!$data['current_listing_id']) {
    if (isLoggedIn() && getCurrentUserId() == $data['user_id']) {
        header('Location: /assign-listing.php?slot=' . $data['id']);
        exit;
    } else {
        echo "This QR code is not currently assigned to a listing.";
        exit;
    }
}

// Get photos
$stmt = $pdo->prepare("SELECT photo_url FROM listing_photos WHERE listing_id = ? ORDER BY sort_order");
$stmt->execute([$data['current_listing_id']]);
$photos = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($data['address']); ?> - NestQR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div style="min-height:100vh;background:linear-gradient(135deg,var(--purple-primary),var(--purple-dark));color:white;">
        <div class="container" style="padding:2rem 1rem;">
            <div style="max-width:600px;margin:0 auto;background:white;border-radius:var(--radius-xl);overflow:hidden;color:var(--gray-900);">
                
                <?php if(!empty($photos)): ?>
                    <img src="<?php echo htmlspecialchars($photos[0]); ?>" style="width:100%;height:300px;object-fit:cover;">
                <?php endif; ?>
                
                <div style="padding:2rem;">
                    <h1 style="font-size:2rem;margin-bottom:0.5rem;"><?php echo htmlspecialchars($data['address']); ?></h1>
                    <p style="font-size:2.5rem;font-weight:700;color:var(--purple-primary);margin-bottom:1rem;">
                        <?php echo formatPrice($data['price']); ?>
                    </p>
                    
                    <div style="display:flex;gap:2rem;margin-bottom:2rem;padding:1rem;background:var(--gray-50);border-radius:var(--radius-md);">
                        <div><strong><?php echo $data['beds']; ?></strong> Beds</div>
                        <div><strong><?php echo $data['baths']; ?></strong> Baths</div>
                        <?php if($data['sqft']): ?>
                            <div><strong><?php echo number_format($data['sqft']); ?></strong> sq ft</div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($data['description']): ?>
                        <div style="margin-bottom:2rem;">
                            <h3 style="margin-bottom:0.5rem;">Description</h3>
                            <p style="color:var(--gray-700);line-height:1.6;"><?php echo nl2br(htmlspecialchars($data['description'])); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div style="border-top:1px solid var(--gray-200);padding-top:1.5rem;">
                        <h3 style="margin-bottom:1rem;">Contact Agent</h3>
                        <div style="display:flex;gap:1rem;align-items:center;margin-bottom:1rem;">
                            <?php if($data['agent_photo']): ?>
                                <img src="<?php echo htmlspecialchars($data['agent_photo']); ?>" style="width:60px;height:60px;border-radius:50%;object-fit:cover;">
                            <?php endif; ?>
                            <div>
                                <div style="font-weight:600;font-size:1.125rem;"><?php echo htmlspecialchars($data['agent_name']); ?></div>
                                <?php if($data['agent_phone']): ?>
                                    <div style="color:var(--gray-600);"><?php echo htmlspecialchars($data['agent_phone']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a href="mailto:<?php echo htmlspecialchars($data['agent_email']); ?>" class="btn btn-primary btn-block btn-lg">Contact Agent</a>
                    </div>
                </div>
            </div>
            
            <div style="text-align:center;margin-top:2rem;opacity:0.8;">
                <small>Powered by NestQR</small>
            </div>
        </div>
    </div>
</body>
</html>
