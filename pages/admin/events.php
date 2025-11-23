<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('admin');

$events = $pdo->query("SELECT e.*, u.full_name as creator_name FROM events e LEFT JOIN user u ON e.created_by = u.user_id ORDER BY e.event_date DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Event - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>🎵 KELOLA EVENT</h2>
            </div>
            <div class="navbar-user">
                <a href="dashboard.php" class="btn btn-sm btn-primary">Kembali</a>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h3>Semua Event</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td>#<?php echo $event['event_id']; ?></td>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><span class="badge badge-info"><?php echo str_replace('_', ' ', $event['category']); ?></span></td>
                        <td><?php echo date('d/m/Y', strtotime($event['event_date'])); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td><?php echo $event['quota']; ?> orang</td>
                        <td>
                            <?php 
                            $badge_class = 'badge-success';
                            if ($event['status'] == 'closed') $badge_class = 'badge-warning';
                            if ($event['status'] == 'finished') $badge_class = 'badge-danger';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($event['status']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($event['creator_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
