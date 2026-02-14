<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAuth();
$userId = getCurrentUserId();

// Get date range
$days = intval($_GET['days'] ?? 30);
$startDate = date('Y-m-d', strtotime("-{$days} days"));

// Total scans
$stmt = $pdo->prepare("
    SELECT COUNT(*) as total
    FROM scan_analytics sa
    JOIN qr_slots qs ON sa.qr_slot_id = qs.id
    WHERE qs.user_id = ?
");
$stmt->execute([$userId]);
$totalScans = $stmt->fetchColumn();

// Scans by day
$stmt = $pdo->prepare("
    SELECT DATE(sa.scanned_at) as date, COUNT(*) as scans
    FROM scan_analytics sa
    JOIN qr_slots qs ON sa.qr_slot_id = qs.id
    WHERE qs.user_id = ? AND sa.scanned_at >= ?
    GROUP BY DATE(sa.scanned_at)
    ORDER BY date DESC
");
$stmt->execute([$userId, $startDate]);
$scansByDay = $stmt->fetchAll();

// Top performing QR codes
$stmt = $pdo->prepare("
    SELECT qs.short_code, il.emoji, il.name, qs.total_scans, l.address
    FROM qr_slots qs
    JOIN icon_library il ON qs.icon_id = il.id
    LEFT JOIN listings l ON qs.current_listing_id = l.id
    WHERE qs.user_id = ?
    ORDER BY qs.total_scans DESC
    LIMIT 10
");
$stmt->execute([$userId]);
$topQRs = $stmt->fetchAll();

// Recent activity
$stmt = $pdo->prepare("
    SELECT sa.scanned_at, qs.short_code, l.address
    FROM scan_analytics sa
    JOIN qr_slots qs ON sa.qr_slot_id = qs.id
    LEFT JOIN listings l ON sa.listing_id = l.id
    WHERE qs.user_id = ?
    ORDER BY sa.scanned_at DESC
    LIMIT 20
");
$stmt->execute([$userId]);
$recentScans = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Analytics - NestQR</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo"><span class="logo-text">NestQR</span></div>
            <div class="nav">
                <a href="/dashboard.php">Dashboard</a>
                <a href="/analytics.php" style="font-weight:600;">Analytics</a>
                <a href="/logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container" style="margin-top:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h1>Analytics</h1>
            <select class="input" style="width:auto;" onchange="window.location='?days='+this.value">
                <option value="7" <?php echo $days==7?'selected':''; ?>>Last 7 days</option>
                <option value="30" <?php echo $days==30?'selected':''; ?>>Last 30 days</option>
                <option value="90" <?php echo $days==90?'selected':''; ?>>Last 90 days</option>
            </select>
        </div>
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1rem;margin-bottom:2rem;">
            <div class="feature-card">
                <h3 style="color:var(--gray-600);font-size:0.875rem;margin-bottom:0.5rem;">Total Scans</h3>
                <p style="font-size:2.5rem;font-weight:700;color:var(--purple-primary);"><?php echo number_format($totalScans); ?></p>
            </div>
            <div class="feature-card">
                <h3 style="color:var(--gray-600);font-size:0.875rem;margin-bottom:0.5rem;">Active QR Codes</h3>
                <p style="font-size:2.5rem;font-weight:700;color:var(--purple-primary);"><?php echo count($topQRs); ?></p>
            </div>
            <div class="feature-card">
                <h3 style="color:var(--gray-600);font-size:0.875rem;margin-bottom:0.5rem;">Avg per Day</h3>
                <p style="font-size:2.5rem;font-weight:700;color:var(--purple-primary);">
                    <?php echo $days > 0 ? number_format($totalScans / $days, 1) : 0; ?>
                </p>
            </div>
        </div>
        
        <div class="feature-card" style="margin-bottom:2rem;">
            <h3>Scans Over Time</h3>
            <canvas id="scansChart" style="max-height:300px;"></canvas>
        </div>
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem;">
            <div class="feature-card">
                <h3>Top Performing QR Codes</h3>
                <div style="margin-top:1rem;">
                    <?php foreach($topQRs as $qr): ?>
                        <div style="display:flex;justify-content:space-between;padding:0.75rem 0;border-bottom:1px solid var(--gray-200);">
                            <div>
                                <span style="font-size:1.25rem;margin-right:0.5rem;"><?php echo $qr['emoji']; ?></span>
                                <strong><?php echo $qr['short_code']; ?></strong>
                                <?php if($qr['address']): ?>
                                    <br><small style="color:var(--gray-600);"><?php echo htmlspecialchars($qr['address']); ?></small>
                                <?php endif; ?>
                            </div>
                            <span style="font-weight:700;color:var(--purple-primary);"><?php echo number_format($qr['total_scans']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="feature-card">
                <h3>Recent Activity</h3>
                <div style="margin-top:1rem;max-height:400px;overflow-y:auto;">
                    <?php foreach($recentScans as $scan): ?>
                        <div style="padding:0.75rem 0;border-bottom:1px solid var(--gray-200);">
                            <div style="font-weight:600;"><?php echo $scan['short_code']; ?></div>
                            <?php if($scan['address']): ?>
                                <div style="font-size:0.875rem;color:var(--gray-600);"><?php echo htmlspecialchars($scan['address']); ?></div>
                            <?php endif; ?>
                            <div style="font-size:0.75rem;color:var(--gray-600);">
                                <?php echo date('M j, Y g:i A', strtotime($scan['scanned_at'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    const ctx = document.getElementById('scansChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_reverse(array_column($scansByDay, 'date'))); ?>,
            datasets: [{
                label: 'Scans',
                data: <?php echo json_encode(array_reverse(array_column($scansByDay, 'scans'))); ?>,
                borderColor: '#8e63f5',
                backgroundColor: 'rgba(142, 99, 245, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    </script>
</body>
</html>
