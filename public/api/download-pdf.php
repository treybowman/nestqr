<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireAuth();
$userId = getCurrentUserId();
$qrId = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT qs.short_code, il.slug, il.name, il.emoji
    FROM qr_slots qs
    JOIN icon_library il ON qs.icon_id = il.id
    WHERE qs.id = ? AND qs.user_id = ?
");
$stmt->execute([$qrId, $userId]);
$qr = $stmt->fetch();

if (!$qr) {
    die('QR code not found');
}

// Create a simple HTML-to-PDF approach
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="nestqr_' . $qr['short_code'] . '.pdf"');

// For MVP, we'll create a printable HTML page
// In production, use library like TCPDF or Dompdf
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: letter; margin: 1in; }
        body { font-family: Arial, sans-serif; text-align: center; }
        .qr-container { margin: 2in auto; }
        .qr-code { width: 4in; height: 4in; border: 1px solid #000; }
        .icon { font-size: 48px; margin: 20px 0; }
        .short-url { font-size: 24px; font-weight: bold; margin: 20px 0; }
        .instructions { margin-top: 40px; text-align: left; }
    </style>
</head>
<body>
    <div class="qr-container">
        <div class="icon"><?php echo $qr['emoji']; ?> <?php echo htmlspecialchars($qr['name']); ?></div>
        <img src="<?php echo SITE_URL; ?>/uploads/qr-codes/qr_<?php echo $qr['short_code']; ?>_<?php echo $qr['slug']; ?>.png" class="qr-code">
        <div class="short-url">nestqr.com/<?php echo $qr['short_code']; ?></div>
    </div>
    <div class="instructions">
        <h3>Instructions:</h3>
        <ol>
            <li>Print this page</li>
            <li>Cut along dotted line</li>
            <li>Attach to yard sign</li>
            <li>Scan QR code to assign to listing</li>
        </ol>
    </div>
</body>
</html>
