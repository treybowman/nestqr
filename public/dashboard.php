<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();

$user = getCurrentUser();
$userId = getCurrentUserId();

// Get QR slots
$stmt = $pdo->prepare("
    SELECT qs.*, il.emoji, il.name as icon_name, l.address, l.status
    FROM qr_slots qs
    JOIN icon_library il ON qs.icon_id = il.id
    LEFT JOIN listings l ON qs.current_listing_id = l.id
    WHERE qs.user_id = ?
    ORDER BY qs.created_at DESC
");
$stmt->execute([$userId]);
$qrSlots = $stmt->fetchAll();

// Get listings
$stmt = $pdo->prepare("SELECT * FROM listings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$listings = $stmt->fetchAll();

$slotCount = count($qrSlots);
$slotLimit = getQRSlotLimit($user['plan_tier']);
$canCreate = $slotCount < $slotLimit;

// Get total scans
$stmt = $pdo->prepare("SELECT SUM(total_scans) as total FROM qr_slots WHERE user_id = ?");
$stmt->execute([$userId]);
$totalScans = $stmt->fetchColumn() ?? 0;

$message = getFlashMessage();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo"><span class="logo-text">NestQR</span></div>
            <div class="nav">
                <a href="/dashboard.php" style="font-weight:600;">Dashboard</a>
                <a href="/analytics.php">Analytics</a>
                <a href="/settings.php">Settings</a>
                <a href="/logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container" style="margin-top:2rem;">
        <h1>Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        
        <?php if($message): ?>
            <div class="<?php echo $message['type']==='success'?'success-message':'error-message'; ?>" style="margin-top:1rem;">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin:2rem 0;">
            <div class="feature-card">
                <h3>Total Scans</h3>
                <p style="font-size:2rem;font-weight:700;color:var(--purple-primary);"><?php echo number_format($totalScans); ?></p>
            </div>
            <div class="feature-card">
                <h3>QR Codes</h3>
                <p style="font-size:2rem;font-weight:700;color:var(--purple-primary);">
                    <?php echo $slotCount; ?> / <?php echo $slotLimit == UNLIMITED_QR_LIMIT ? '∞' : $slotLimit; ?>
                </p>
            </div>
            <div class="feature-card">
                <h3>Active Listings</h3>
                <p style="font-size:2rem;font-weight:700;color:var(--purple-primary);"><?php echo count($listings); ?></p>
            </div>
            <div class="feature-card">
                <h3>Plan</h3>
                <p style="font-size:1.5rem;font-weight:600;text-transform:capitalize;"><?php echo $user['plan_tier']; ?></p>
                <?php if($user['plan_tier'] === 'free'): ?>
                    <a href="/upgrade.php" style="font-size:0.875rem;color:var(--purple-primary);">Upgrade →</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- QR Codes Section -->
        <div style="display:flex;justify-content:space-between;align-items:center;margin:2rem 0;">
            <h2>Your QR Codes</h2>
            <?php if($canCreate): ?>
                <a href="/create-qr.php" class="btn btn-primary">+ New QR Code</a>
            <?php else: ?>
                <span style="color:var(--gray-600);">Limit reached. <a href="/settings.php">Upgrade plan</a></span>
            <?php endif; ?>
        </div>
        
        <?php if(empty($qrSlots)): ?>
            <div class="feature-card" style="text-align:center;padding:3rem;">
                <p>No QR codes yet. Create your first one to get started!</p>
                <?php if($canCreate): ?>
                    <a href="/create-qr.php" class="btn btn-primary" style="margin-top:1rem;">Create QR Code</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="display:grid;gap:1rem;">
                <?php foreach($qrSlots as $slot): ?>
                    <div class="feature-card">
                        <div style="display:flex;justify-content:space-between;align-items:start;">
                            <div>
                                <h3 style="display:flex;align-items:center;gap:0.5rem;">
                                    <span style="font-size:1.5rem;"><?php echo $slot['emoji']; ?></span>
                                    <?php echo htmlspecialchars($slot['icon_name']); ?>
                                </h3>
                                <p style="color:var(--gray-600);">nestqr.com/<?php echo $slot['short_code']; ?></p>
                                <?php if($slot['current_listing_id']): ?>
                                    <p style="margin-top:0.5rem;">
                                        📍 <?php echo htmlspecialchars($slot['address']); ?>
                                        <span style="color:var(--gray-600);">(<?php echo $slot['status']; ?>)</span>
                                    </p>
                                <?php else: ?>
                                    <p style="margin-top:0.5rem;color:var(--gray-600);">Unassigned</p>
                                <?php endif; ?>
                                <p style="margin-top:0.5rem;font-size:0.875rem;">
                                    👁 <?php echo number_format($slot['total_scans']); ?> scans
                                </p>
                            </div>
                            <div style="display:flex;gap:0.5rem;">
                                <a href="/edit-qr.php?id=<?php echo $slot['id']; ?>" class="btn btn-outline">Edit</a>
                                <a href="/download-qr.php?id=<?php echo $slot['id']; ?>" class="btn btn-primary">Download</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Listings Section -->
        <div style="display:flex;justify-content:space-between;align-items:center;margin:3rem 0 2rem 0;">
            <h2>Your Listings</h2>
            <a href="/create-listing.php" class="btn btn-primary">+ New Listing</a>
        </div>
        
        <?php if(empty($listings)): ?>
            <div class="feature-card" style="text-align:center;padding:3rem;">
                <p>No listings yet. Create one to assign to your QR codes!</p>
                <a href="/create-listing.php" class="btn btn-primary" style="margin-top:1rem;">Create Listing</a>
            </div>
        <?php else: ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem;">
                <?php foreach($listings as $listing): ?>
                    <div class="feature-card">
                        <h3 style="font-size:1.125rem;margin-bottom:0.5rem;"><?php echo htmlspecialchars($listing['address']); ?></h3>
                        <p style="font-size:1.5rem;font-weight:700;color:var(--purple-primary);margin-bottom:0.5rem;">
                            <?php echo formatPrice($listing['price']); ?>
                        </p>
                        <p style="color:var(--gray-600);font-size:0.875rem;margin-bottom:1rem;">
                            <?php echo $listing['beds']; ?> beds · <?php echo $listing['baths']; ?> baths
                            <?php if($listing['sqft']): ?>· <?php echo number_format($listing['sqft']); ?> sqft<?php endif; ?>
                        </p>
                        <p style="margin-bottom:1rem;">
                            <span style="padding:0.25rem 0.75rem;border-radius:1rem;font-size:0.75rem;background:<?php 
                                echo $listing['status']==='active' ? '#d1fae5' : ($listing['status']==='sold' ? '#fee2e2' : '#e5e7eb');
                            ?>;color:<?php 
                                echo $listing['status']==='active' ? '#065f46' : ($listing['status']==='sold' ? '#991b1b' : '#374151');
                            ?>;"><?php echo ucfirst($listing['status']); ?></span>
                        </p>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="/edit-listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-outline" style="flex:1;">Edit</a>
                            <a href="/listing.php?code=<?php 
                                // Find QR code for this listing
                                foreach($qrSlots as $slot) {
                                    if($slot['current_listing_id'] == $listing['id']) {
                                        echo $slot['short_code'];
                                        break;
                                    }
                                }
                            ?>" class="btn btn-primary" style="flex:1;" target="_blank">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
