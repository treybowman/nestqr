<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$userId = getCurrentUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $zip = sanitize($_POST['zip']);
    $price = floatval($_POST['price']);
    $beds = intval($_POST['beds']);
    $baths = floatval($_POST['baths']);
    $sqft = intval($_POST['sqft']);
    $description = sanitize($_POST['description']);
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO listings (user_id, address, city, state, zip, price, beds, baths, sqft, description, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
        ");
        $stmt->execute([$userId, $address, $city, $state, $zip, $price, $beds, $baths, $sqft, $description]);
        $listingId = $pdo->lastInsertId();
        
        // Handle photo uploads
        if (!empty($_FILES['photos']['name'][0])) {
            $photoCount = count($_FILES['photos']['name']);
            for ($i = 0; $i < $photoCount; $i++) {
                if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $_FILES['photos']['name'][$i],
                        'type' => $_FILES['photos']['type'][$i],
                        'tmp_name' => $_FILES['photos']['tmp_name'][$i],
                        'error' => $_FILES['photos']['error'][$i],
                        'size' => $_FILES['photos']['size'][$i]
                    ];
                    
                    $upload = uploadImage($file, 'photos');
                    if ($upload['success']) {
                        $stmt = $pdo->prepare("INSERT INTO listing_photos (listing_id, photo_url, sort_order, moderation_status) VALUES (?, ?, ?, ?)");
                        $modStatus = $upload['moderation']['safe'] ? 'approved' : 'pending';
                        $stmt->execute([$listingId, $upload['url'], $i, $modStatus]);
                    }
                }
            }
        }
        
        redirect('/dashboard.php', 'Listing created successfully!', 'success');
    } catch(PDOException $e) {
        $error = 'Failed to create listing. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Listing - NestQR</title>
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
        <h1>Create New Listing</h1>
        
        <form method="POST" enctype="multipart/form-data" style="margin-top:2rem;">
            <div class="feature-card" style="margin-bottom:2rem;">
                <h3>Property Information</h3>
                <div style="margin-bottom:1rem;">
                    <label>Address *</label>
                    <input type="text" name="address" class="input" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label>City *</label>
                        <input type="text" name="city" class="input" required>
                    </div>
                    <div>
                        <label>State *</label>
                        <input type="text" name="state" class="input" required maxlength="2">
                    </div>
                    <div>
                        <label>ZIP</label>
                        <input type="text" name="zip" class="input" maxlength="10">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label>Price *</label>
                        <input type="number" name="price" class="input" required step="0.01">
                    </div>
                    <div>
                        <label>Beds *</label>
                        <input type="number" name="beds" class="input" required min="0">
                    </div>
                    <div>
                        <label>Baths *</label>
                        <input type="number" name="baths" class="input" required step="0.5" min="0">
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Square Feet</label>
                    <input type="number" name="sqft" class="input" min="0">
                </div>
                <div style="margin-bottom:1rem;">
                    <label>Description</label>
                    <textarea name="description" class="input" rows="5"></textarea>
                </div>
            </div>
            
            <div class="feature-card" style="margin-bottom:2rem;">
                <h3>Photos</h3>
                <input type="file" name="photos[]" accept="image/*" multiple class="input">
                <small style="color:var(--gray-600);display:block;margin-top:0.5rem;">
                    Upload multiple photos. First photo will be the main image.
                </small>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg btn-block">Create Listing</button>
        </form>
    </div>
</body>
</html>
