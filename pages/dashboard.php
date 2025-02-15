<?php
// Query statistics
$stats = [
    'members' => $db->query("SELECT COUNT(*) FROM members")->fetchColumn(),
    'active_promotions' => $db->query("SELECT COUNT(*) FROM promotions WHERE status = 'active'")->fetchColumn(),
    'pending_reviews' => $db->query("SELECT COUNT(*) FROM testimonials WHERE status = 'pending'")->fetchColumn(),
    'gallery_images' => $db->query("SELECT COUNT(*) FROM gallery")->fetchColumn()
];

// Recent activities
$activities = $db->query("
    SELECT a.*, u.name as user_name 
    FROM activities a 
    LEFT JOIN users u ON a.user_id = u.id 
    ORDER BY a.created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h2 class="mb-4">แดชบอร์ด</h2>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">สมาชิกทั้งหมด</h5>
                    <h2 class="card-text"><?php echo $stats['members']; ?></h2>
                </div>
            </div>
        </div>
        <!-- ... Similar cards for other stats ... -->
    </div>

    <!-- Recent Activities -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">กิจกรรมล่าสุด</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($activities as $activity): ?>
                        <div class="activity-item d-flex align-items-center py-2">
                            <div class="activity-icon bg-success text-white rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <p class="mb-0"><?php echo htmlspecialchars($activity['description']); ?></p>
                                <small class="text-muted">
                                    โดย <?php echo htmlspecialchars($activity['user_name']); ?> - 
                                    <?php echo date('d/m/Y H:i', strtotime($activity['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- ... Other dashboard components ... -->
    </div>
</div> 