<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('panitia');

$my_events = $pdo->prepare("SELECT * FROM events WHERE created_by = ? ORDER BY event_date DESC");
$my_events->execute([$_SESSION['user_id']]);
$events = $my_events->fetchAll();

$stats = [];
$stats['my_events'] = count($events);
$stats['open_events'] = $pdo->prepare("SELECT COUNT(*) FROM events WHERE created_by = ? AND status = 'open'");
$stats['open_events']->execute([$_SESSION['user_id']]);
$stats['open_events'] = $stats['open_events']->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panitia Dashboard - K-Popers Event</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>🎤 PANITIA DASHBOARD</h2>
            </div>
            <div class="navbar-user">
                <span>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></span>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Event Saya</h3>
                <div class="stat-number"><?php echo $stats['my_events']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Event Aktif</h3>
                <div class="stat-number"><?php echo $stats['open_events']; ?></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Event yang Saya Kelola</h3>
                <a href="create_event.php" class="btn btn-sm btn-success">+ Buat Event Baru</a>
            </div>
            <?php if (count($events) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td>#<?php echo $event['event_id']; ?></td>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><span class="badge badge-info"><?php echo str_replace('_', ' ', $event['category']); ?></span></td>
                        <td><?php echo date('d/m/Y', strtotime($event['event_date'])); ?></td>
                        <td><?php echo $event['quota']; ?> orang</td>
                        <td>
                            <?php 
                            $badge_class = 'badge-success';
                            if ($event['status'] == 'closed') $badge_class = 'badge-warning';
                            if ($event['status'] == 'finished') $badge_class = 'badge-danger';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($event['status']); ?></span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="manage_tickets.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-primary">Kelola Tiket</a>
                                <a href="manage_merch.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-warning">Kelola Merch</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Anda belum membuat event. <a href="create_event.php">Buat event pertama Anda!</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
