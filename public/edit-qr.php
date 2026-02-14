<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$userId = getCurrentUserId();
$qrId = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM qr_slots WHERE id = ? AND user_id = ?");
$stmt->execute([$qrId, $userId]);
$qrSlot = $stmt->fetch();

if (!$qrSlot) {
    redirect('/dashboard.php', 'QR code not found', 'error');
}

$canChangeIcon = canChangeIcon($qrId);

// Get user's listings for assignment
$stmt = $pdo->prepare("SELECT id, address, status FROM listings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$listings = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'assign_listing') {
        $listingId = intval($_POST['listing_id']);
        
        // Verify listing belongs to user
        $stmt = $pdo->prepare("SELECT id FROM listings WHERE id = ? AND user_id = ?");
        $stmt->execute([$listingId, $userId]);
        
        if ($stmt->fetch()) {
            $stmt = $pdo->prepare("UPDATE qr_slots SET current_listing_id = ? WHERE id = ?");
            $stmt->execute([$listingId, $qrId]);
            redirect('/dashboard.php', 'QR code assigned to listing!', 'success');
        }
    }
    
    if ($action === 'unassign') {
        $stmt = $pdo->prepare("UPDATE qr_slots SET current_listing_id = NULL WHERE id = ?");
        $stmt->execute([$qrId]);
        redirect('/edit-qr.php?id=' . $qrId, 'QR code unassigned', 'success');
    }
    
    if ($action === 'change_icon' && $canChangeIcon) {
        $iconId = intval($_POST['icon_id']);
        $stmt = $pdo->prepare("UPDATE qr_slots SET icon_id = ? WHERE id = ?");
        $stmt->execute([$iconId, $qrId]);
        redirect('/edit-qr.php?id=' . $qrId, 'Icon updated!', 'success');
    }
    
    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM qr_slots WHERE id = ? AND user_id = ?");
        $stmt->execute([$qrId, $userId]);
        redirect('/dashboard.php', 'QR code deleted', 'success');
    }
}

$user = getCurrentUser();
$icons = getAvailableIcons($user['plan_tier']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit QR Code - NestQR</title>
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
        <h1>Edit QR Code</h1>
        
        <div class="feature-card" style="margin-top:2rem;">
            <h3>QR Code: <?php echo $qrSlot['short_code']; ?></h3>
            <p>nestqr.com/<?php echo $qrSlot['short_code']; ?></p>
            <p>Total Scans: <?php echo number_format($qrSlot['total_scans']); ?></p>
        </div>
        
        <?php if($canChangeIcon): ?>
            <div class="feature-card" style="margin-top:1rem;">
                <h3>Change Icon (24-hour grace period)</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="change_icon">
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:1rem;margin:1rem 0;">
                        <?php foreach($icons as $icon): ?>
                            <label style="cursor:pointer;">
                                <input type="radio" name="icon_id" value="<?php echo $icon['id']; ?>" required style="display:none;" class="icon-radio">
                                <div class="icon-option" style="border:2px solid var(--gray-300);border-radius:var(--radius-md);padding:1rem;text-align:center;">
                                    <div style="font-size:2rem;"><?php echo $icon['emoji']; ?></div>
                                    <div style="font-size:0.75rem;margin-top:0.5rem;"><?php echo $icon['name']; ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Icon</button>
                </form>
            </div>
        <?php else: ?>
            <div class="feature-card" style="margin-top:1rem;">
                <p style="color:var(--gray-600);">Icon is locked (24 hours passed). Delete and create new QR to change icon.</p>
            </div>
        <?php endif; ?>
        
        <div class="feature-card" style="margin-top:1rem;">
            <h3>Assign to Listing</h3>
            <?php if($qrSlot['current_listing_id']): ?>
                <p>Currently assigned to listing ID: <?php echo $qrSlot['current_listing_id']; ?></p>
                <form method="POST" style="margin-top:1rem;">
                    <input type="hidden" name="action" value="unassign">
                    <button type="submit" class="btn btn-outline">Unassign</button>
                </form>
            <?php endif; ?>
            
            <form method="POST" style="margin-top:1rem;">
                <input type="hidden" name="action" value="assign_listing">
                <select name="listing_id" class="input" required>
                    <option value="">Select a listing...</option>
                    <?php foreach($listings as $listing): ?>
                        <option value="<?php echo $listing['id']; ?>" <?php echo $qrSlot['current_listing_id'] == $listing['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($listing['address']); ?> (<?php echo $listing['status']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary" style="margin-top:1rem;">Assign</button>
            </form>
        </div>
        
        <div class="feature-card" style="margin-top:1rem;border-color:var(--gray-300);">
            <h3 style="color:#991b1b;">Danger Zone</h3>
            <form method="POST" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-outline" style="color:#991b1b;border-color:#991b1b;">Delete QR Code</button>
            </form>
        </div>
    </div>
    
    <script>
    document.querySelectorAll('.icon-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.icon-option').forEach(opt => {
                opt.style.borderColor = 'var(--gray-300)';
                opt.style.background = 'white';
            });
            if(this.checked) {
                this.nextElementSibling.style.borderColor = 'var(--purple-primary)';
                this.nextElementSibling.style.background = 'var(--gray-50)';
            }
        });
    });
    </script>
</body>
</html>
