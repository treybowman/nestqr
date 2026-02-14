<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$userId = getCurrentUserId();
$slotId = intval($_GET['slot'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM qr_slots WHERE id = ? AND user_id = ?");
$stmt->execute([$slotId, $userId]);
$slot = $stmt->fetch();

if (!$slot) {
    redirect('/dashboard.php', 'QR code not found', 'error');
}

// Get user's listings
$stmt = $pdo->prepare("SELECT * FROM listings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$listings = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $listingId = intval($_POST['listing_id']);
    
    $stmt = $pdo->prepare("SELECT id FROM listings WHERE id = ? AND user_id = ?");
    $stmt->execute([$listingId, $userId]);
    
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("UPDATE qr_slots SET current_listing_id = ? WHERE id = ?");
        $stmt->execute([$listingId, $slotId]);
        
        redirect('/listing.php?code=' . $slot['short_code'], '', '');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assign Listing - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem;background:linear-gradient(135deg,var(--purple-primary),var(--purple-dark));">
        <div style="max-width:500px;width:100%;background:white;border-radius:var(--radius-xl);padding:2rem;">
            <h1 style="margin-bottom:1rem;">Assign QR Code</h1>
            <p style="color:var(--gray-600);margin-bottom:2rem;">This QR code (<?php echo $slot['short_code']; ?>) is not assigned. Select a listing:</p>
            
            <?php if(empty($listings)): ?>
                <p style="margin-bottom:1rem;">No listings found. <a href="/create-listing.php">Create one first</a>.</p>
            <?php else: ?>
                <form method="POST">
                    <select name="listing_id" class="input" required style="margin-bottom:1rem;">
                        <option value="">Select a listing...</option>
                        <?php foreach($listings as $listing): ?>
                            <option value="<?php echo $listing['id']; ?>">
                                <?php echo htmlspecialchars($listing['address']); ?> - <?php echo formatPrice($listing['price']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Assign Listing</button>
                </form>
            <?php endif; ?>
            
            <a href="/dashboard.php" style="display:block;text-align:center;margin-top:1rem;color:var(--gray-600);">Skip for now</a>
        </div>
    </div>
</body>
</html>
