<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$user = getCurrentUser();

// Simple admin check - only user ID 1 for now
if ($user['id'] !== 1) {
    die('Access denied. Admin only.');
}

$message = getFlashMessage();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_domain') {
        $domain = sanitize($_POST['domain']);
        $marketName = sanitize($_POST['market_name']);
        $launchDate = sanitize($_POST['launch_date']);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO active_domains (domain, market_name, is_active, launched_at) VALUES (?, ?, 1, ?)");
            $stmt->execute([$domain, $marketName, $launchDate]);
            redirect('/admin/domains.php', 'Domain added successfully!', 'success');
        } catch(PDOException $e) {
            $error = 'Failed to add domain: ' . $e->getMessage();
        }
    }
    
    if ($action === 'toggle_active') {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("UPDATE active_domains SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$id]);
        redirect('/admin/domains.php', 'Domain status updated', 'success');
    }
}

// Get all domains
$stmt = $pdo->query("SELECT * FROM active_domains ORDER BY is_active DESC, launched_at DESC");
$domains = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Domain Management - NestQR Admin</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo"><span class="logo-text">NestQR Admin</span></div>
            <div class="nav">
                <a href="/dashboard.php">Dashboard</a>
                <a href="/admin/domains.php" style="font-weight:600;">Domains</a>
                <a href="/logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container" style="margin-top:2rem;">
        <h1>Domain Management</h1>
        
        <?php if($message): ?>
            <div class="<?php echo $message['type'] === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endif; ?>
        
        <div class="feature-card" style="margin:2rem 0;">
            <h2>Add New Market Domain</h2>
            <form method="POST" style="margin-top:1rem;">
                <input type="hidden" name="action" value="add_domain">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label>Domain Key (e.g., nestdfw)</label>
                        <input type="text" name="domain" class="input" required placeholder="nestdfw">
                    </div>
                    <div>
                        <label>Market Name</label>
                        <input type="text" name="market_name" class="input" required placeholder="Dallas">
                    </div>
                    <div>
                        <label>Launch Date</label>
                        <input type="date" name="launch_date" class="input" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Domain</button>
            </form>
            
            <div style="margin-top:2rem;padding:1.5rem;background:var(--gray-50);border-radius:var(--radius-md);">
                <h3>Setup Instructions</h3>
                <p style="margin-bottom:1rem;">After adding a domain, follow these steps:</p>
                
                <h4>1. Cloudflare DNS</h4>
                <pre style="background:var(--gray-900);color:white;padding:1rem;border-radius:var(--radius-sm);overflow-x:auto;">
Type: A
Name: @
IPv4: YOUR_SERVER_IP
Proxy: ON (orange cloud)

Type: A
Name: *
IPv4: YOUR_SERVER_IP
Proxy: ON (orange cloud)</pre>
                
                <h4 style="margin-top:1rem;">2. SSL/TLS</h4>
                <p>Set to "Full (strict)" and enable "Always Use HTTPS"</p>
                
                <h4 style="margin-top:1rem;">3. Web Server</h4>
                <p>Apache: .htaccess handles wildcards automatically</p>
                <p>Nginx: Add domain to server_name directive</p>
                
                <h4 style="margin-top:1rem;">4. Update Config</h4>
                <p>Add to <code>includes/config.php</code> $ACTIVE_DOMAINS array:</p>
                <pre style="background:var(--gray-900);color:white;padding:1rem;border-radius:var(--radius-sm);overflow-x:auto;">
'nestdfw' => [
    'name' => 'Dallas',
    'url' => 'https://nestdfw.com',
    'is_primary' => false
]</pre>
            </div>
        </div>
        
        <div class="feature-card">
            <h2>Active Domains</h2>
            <table style="width:100%;margin-top:1rem;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:2px solid var(--gray-300);">
                        <th style="text-align:left;padding:0.75rem;">Domain</th>
                        <th style="text-align:left;padding:0.75rem;">Market</th>
                        <th style="text-align:left;padding:0.75rem;">Launched</th>
                        <th style="text-align:left;padding:0.75rem;">Status</th>
                        <th style="text-align:left;padding:0.75rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($domains as $domain): ?>
                        <tr style="border-bottom:1px solid var(--gray-200);">
                            <td style="padding:0.75rem;"><strong><?php echo $domain['domain']; ?>.com</strong></td>
                            <td style="padding:0.75rem;"><?php echo htmlspecialchars($domain['market_name']); ?></td>
                            <td style="padding:0.75rem;"><?php echo $domain['launched_at'] ? date('M j, Y', strtotime($domain['launched_at'])) : '-'; ?></td>
                            <td style="padding:0.75rem;">
                                <span style="padding:0.25rem 0.75rem;border-radius:1rem;background:<?php echo $domain['is_active'] ? '#d1fae5' : '#fee2e2'; ?>;color:<?php echo $domain['is_active'] ? '#065f46' : '#991b1b'; ?>;">
                                    <?php echo $domain['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td style="padding:0.75rem;">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="toggle_active">
                                    <input type="hidden" name="id" value="<?php echo $domain['id']; ?>">
                                    <button type="submit" class="btn btn-outline" style="padding:0.25rem 0.75rem;font-size:0.875rem;">
                                        <?php echo $domain['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
