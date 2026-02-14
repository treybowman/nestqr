<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$userId = getCurrentUserId();
$qrId = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT qs.*, il.slug, il.name as icon_name, il.emoji
    FROM qr_slots qs
    JOIN icon_library il ON qs.icon_id = il.id
    WHERE qs.id = ? AND qs.user_id = ?
");
$stmt->execute([$qrId, $userId]);
$qr = $stmt->fetch();

if (!$qr) {
    die('QR code not found');
}

// Generate QR if not exists
$qrPath = UPLOAD_PATH . 'qr-codes/' . $qr['qr_code_filename'];
if (!file_exists($qrPath)) {
    generateQRCode($qr['short_code'], $qr['slug']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Download QR - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo"><span class="logo-text">NestQR</span></div>
            <div class="nav">
                <a href="/dashboard.php">Dashboard</a>
                <a href="/logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container" style="max-width:800px;margin-top:2rem;">
        <h1>Download QR Code</h1>
        
        <div class="feature-card" style="margin-top:2rem;text-align:center;">
            <h2><?php echo $qr['emoji']; ?> <?php echo htmlspecialchars($qr['icon_name']); ?></h2>
            <p style="font-size:1.25rem;color:var(--purple-primary);font-weight:600;">
                nestqr.com/<?php echo $qr['short_code']; ?>
            </p>
            
            <?php if(file_exists($qrPath)): ?>
                <img src="/uploads/qr-codes/<?php echo $qr['qr_code_filename']; ?>" style="max-width:400px;margin:2rem auto;display:block;border:1px solid var(--gray-200);border-radius:var(--radius-md);">
            <?php else: ?>
                <p>QR code is being generated...</p>
            <?php endif; ?>
            
            <div style="display:grid;gap:1rem;max-width:400px;margin:2rem auto;">
                <a href="/uploads/qr-codes/<?php echo $qr['qr_code_filename']; ?>" download class="btn btn-primary btn-block">
                    Download PNG (Web)
                </a>
                <a href="/uploads/qr-codes/qr_<?php echo $qr['short_code']; ?>_print.png" download class="btn btn-primary btn-block">
                    Download PNG (Print Quality)
                </a>
                <a href="/api/download-svg.php?id=<?php echo $qrId; ?>" class="btn btn-outline btn-block">
                    Download SVG (Vector)
                </a>
                <a href="/api/download-pdf.php?id=<?php echo $qrId; ?>" class="btn btn-outline btn-block">
                    Download PDF (Full Page)
                </a>
            </div>
        </div>
        
        <div class="feature-card" style="margin-top:1rem;">
            <h3>Instructions</h3>
            <ol style="padding-left:1.5rem;color:var(--gray-700);">
                <li style="margin-bottom:0.5rem;">Download your preferred format</li>
                <li style="margin-bottom:0.5rem;">Print the QR code (8"×8" minimum for yard signs)</li>
                <li style="margin-bottom:0.5rem;">Attach to your yard sign</li>
                <li style="margin-bottom:0.5rem;">Scan to assign to a listing</li>
            </ol>
        </div>
    </div>
</body>
</html>
