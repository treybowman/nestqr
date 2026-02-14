<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$user = getCurrentUser();
$userId = getCurrentUserId();

if (!canCreateQRSlot($userId)) {
    redirect('/dashboard.php', 'QR slot limit reached. Please upgrade your plan.', 'error');
}

$icons = getAvailableIcons($user['plan_tier']);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $iconId = (int)$_POST['icon_id'];
    
    // Verify icon is available for this plan
    $stmt = $pdo->prepare("SELECT id FROM icon_library WHERE id = ? AND (tier = 'free' OR ? IN ('pro', 'unlimited', 'company'))");
    $stmt->execute([$iconId, $user['plan_tier']]);
    
    if (!$stmt->fetch()) {
        $error = 'Invalid icon selection';
    } else {
        try {
            $shortCode = generateShortCode();
            
            // Verify unique
            while(true) {
                $check = $pdo->prepare("SELECT id FROM qr_slots WHERE short_code = ?");
                $check->execute([$shortCode]);
                if (!$check->fetch()) break;
                $shortCode = generateShortCode();
            }
            
            $stmt = $pdo->prepare("INSERT INTO qr_slots (user_id, icon_id, short_code, qr_code_filename) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $iconId, $shortCode, 'pending']);
            $qrSlotId = $pdo->lastInsertId();
            
            // Get icon slug for QR generation
            $stmt = $pdo->prepare("SELECT slug FROM icon_library WHERE id = ?");
            $stmt->execute([$iconId]);
            $iconSlug = $stmt->fetchColumn();
            
            // Generate QR code (this would call the actual QR generation function)
            // For now, just update the filename
            $filename = 'qr_' . $shortCode . '_' . $iconSlug . '.png';
            $stmt = $pdo->prepare("UPDATE qr_slots SET qr_code_filename = ? WHERE id = ?");
            $stmt->execute([$filename, $qrSlotId]);
            
            redirect('/dashboard.php', 'QR code created successfully!', 'success');
        } catch(PDOException $e) {
            $error = 'Failed to create QR code. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create QR Code - NestQR</title>
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
        <h1>Create New QR Code</h1>
        <?php if($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
        
        <form method="POST" style="margin-top:2rem;">
            <div style="margin-bottom:2rem;">
                <label style="display:block;margin-bottom:1rem;font-weight:600;">Choose an Icon:</label>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:1rem;">
                    <?php foreach($icons as $icon): ?>
                        <label style="cursor:pointer;">
                            <input type="radio" name="icon_id" value="<?php echo $icon['id']; ?>" required style="display:none;" class="icon-radio">
                            <div class="icon-option" style="border:2px solid var(--gray-300);border-radius:var(--radius-md);padding:1rem;text-align:center;transition:all 0.2s;">
                                <div style="font-size:2rem;"><?php echo $icon['emoji']; ?></div>
                                <div style="font-size:0.75rem;margin-top:0.5rem;"><?php echo htmlspecialchars($icon['name']); ?></div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Create QR Code</button>
        </form>
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
