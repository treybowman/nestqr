<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

requireAuth();
$userId = getCurrentUserId();
$qrId = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT qs.short_code, il.slug
    FROM qr_slots qs
    JOIN icon_library il ON qs.icon_id = il.id
    WHERE qs.id = ? AND qs.user_id = ?
");
$stmt->execute([$qrId, $userId]);
$qr = $stmt->fetch();

if (!$qr) {
    die('QR code not found');
}

// Generate SVG QR code
require_once '../../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\SvgWriter;

$url = SITE_URL . '/' . $qr['short_code'];

$result = Builder::create()
    ->writer(new SvgWriter())
    ->data($url)
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->size(600)
    ->margin(10)
    ->build();

header('Content-Type: application/svg+xml');
header('Content-Disposition: attachment; filename="nestqr_' . $qr['short_code'] . '.svg"');
echo $result->getString();
