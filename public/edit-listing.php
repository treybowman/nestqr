<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$userId = getCurrentUserId();
$listingId = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ? AND user_id = ?");
$stmt->execute([$listingId, $userId]);
$listing = $stmt->fetch();

if (!$listing) {
    redirect('/dashboard.php', 'Listing not found', 'error');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update') {
        $address = sanitize($_POST['address']);
        $city = sanitize($_POST['city']);
        $state = sanitize($_POST['state']);
        $zip = sanitize($_POST['zip']);
        $price = floatval($_POST['price']);
        $beds = intval($_POST['beds']);
        $baths = floatval($_POST['baths']);
        $sqft = intval($_POST['sqft']);
        $description = sanitize($_POST['description']);
        $status = $_POST['status'];
        
        $stmt = $pdo->prepare("
            UPDATE listings 
            SET address = ?, city = ?, state = ?, zip = ?, price = ?, beds = ?, baths = ?, sqft = ?, description = ?, status = ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$address, $city, $state, $zip, $price, $beds, $baths, $sqft, $description, $status, $listingId, $userId]);
        
        redirect('/edit-listing.php?id=' . $listingId, 'Listing updated!', 'success');
    }
    
    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM listings WHERE id = ? AND user_id = ?");
        $stmt->execute([$listingId, $userId]);
        redirect('/dashboard.php', 'Listing deleted', 'success');
    }
}

$message = getFlashMessage();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Listing - NestQR</title>
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
        <h1>Edit Listing</h1>
        
        <?php if($message): ?>
            <div class="<?php echo $message['type']==='success'?'success-message':'error-message'; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" style="margin-top:2rem;">
            <input type="hidden" name="action" value="update">
            
            <div class="feature-card" style="margin-bottom:2rem;">
                <h3>Property Information</h3>
                <div style="margin-bottom:1rem;">
                    <label>Address</label>
                    <input type="text" name="address" class="input" value="<?php echo htmlspecialchars($listing['address']); ?>" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label>City</label>
                        <input type="text" name="city" class="input" value="<?php echo htmlspecialchars($listing['city']); ?>">
                    </div>
                    <div>
                        <label>State</label>
                        <input type="text" name="state" class="input" value="<?php echo htmlspecialchars($listing['state']); ?>" maxlength="2">
                    </div>
                    <div>
                        <label>ZIP</label>
                        <input type="text" name="zip" class="input" value="<?php echo htmlspecialchars($listing['zip']); ?>">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label>Price</label>
                        <input type="number" name="price" class="input" value="<?php echo $listing['price']; ?>" step="0.01">
                    </div>
                    <div>
                        <label>Beds</label>
                        <input type="number" name="beds" class="input" value="<?php echo $listing['beds']; ?>">
                    </div>
                    <div>
                        <label>Baths</label>
                        <input type="number" name="baths" class="input" value="<?php echo $listing['baths']; ?>" step="0.5">
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Square Feet</label>
                    <input type="number" name="sqft" class="input" value="<?php echo $listing['sqft']; ?>">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Description</label>
                    <textarea name="description" class="input" rows="5"><?php echo htmlspecialchars($listing['description']); ?></textarea>
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Status</label>
                    <select name="status" class="input">
                        <option value="active" <?php echo $listing['status']==='active'?'selected':''; ?>>Active</option>
                        <option value="pending" <?php echo $listing['status']==='pending'?'selected':''; ?>>Pending</option>
                        <option value="sold" <?php echo $listing['status']==='sold'?'selected':''; ?>>Sold</option>
                        <option value="inactive" <?php echo $listing['status']==='inactive'?'selected':''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div style="display:flex;gap:1rem;">
                <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                <a href="/dashboard.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
        
        <div class="feature-card" style="margin-top:2rem;border-color:#991b1b;">
            <h3 style="color:#991b1b;">Delete Listing</h3>
            <form method="POST" onsubmit="return confirm('Delete this listing? This cannot be undone.');">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-outline" style="color:#991b1b;border-color:#991b1b;">Delete Listing</button>
            </form>
        </div>
    </div>
</body>
</html>
