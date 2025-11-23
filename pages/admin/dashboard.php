<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('admin');

$stats = [];
$stats['total_users'] = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
$stats['total_events'] = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$stats['pending_payments'] = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'waiting'")->fetchColumn();
$stats['total_registrations'] = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();

$events = $pdo->query("SELECT e.*, u.full_name as creator_name FROM events e LEFT JOIN user u ON e.created_by = u.user_id ORDER BY e.event_date DESC LIMIT 10")->fetchAll();
$pending_payments = $pdo->query("SELECT p.*, r.*, e.event_name, u.full_name, u.email 
    FROM payments p 
    JOIN registrations r ON p.reg_id = r.reg_id 
    JOIN events e ON r.event_id = e.event_id 
    JOIN user u ON r.user_id = u.user_id 
    WHERE p.status = 'waiting' 
    ORDER BY p.payment_id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - K-Popers Event</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>🎵 ADMIN DASHBOARD</h2>
            </div>
            <div class="navbar-user">
                <span>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></span>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pengguna</h3>
                <div class="stat-number"><?php echo $stats['total_users']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Event</h3>
                <div class="stat-number"><?php echo $stats['total_events']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Pembayaran Pending</h3>
                <div class="stat-number"><?php echo $stats['pending_payments']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Registrasi</h3>
                <div class="stat-number"><?php echo $stats['total_registrations']; ?></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Verifikasi Pembayaran</h3>
            </div>
            <?php if (count($pending_payments) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama User</th>
                        <th>Event</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_payments as $payment): ?>
                    <tr>
                        <td>#<?php echo $payment['payment_id']; ?></td>
                        <td><?php echo htmlspecialchars($payment['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($payment['event_name']); ?></td>
                        <td>Rp <?php echo number_format($payment['amount'], 0, ',', '.'); ?></td>
                        <td><span class="badge badge-info"><?php echo strtoupper($payment['payment_method']); ?></span></td>
                        <td>
                            <?php if ($payment['proof']): ?>
                                <a href="/uploads/<?php echo $payment['proof']; ?>" target="_blank" class="btn btn-sm btn-primary">Lihat Bukti</a>
                            <?php else: ?>
                                <span class="badge badge-warning">Belum Upload</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="verify_payment.php?id=<?php echo $payment['payment_id']; ?>&action=verify" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi pembayaran ini?')">Verifikasi</a>
                                <a href="verify_payment.php?id=<?php echo $payment['payment_id']; ?>&action=reject" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pembayaran ini?')">Tolak</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Tidak ada pembayaran yang menunggu verifikasi</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Daftar Event Terbaru</h3>
                <a href="events.php" class="btn btn-sm btn-primary">Kelola Semua Event</a>
            </div>
            <?php if (count($events) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
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
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Belum ada event</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Kelola Pengguna</h3>
                <a href="users.php" class="btn btn-sm btn-primary">Lihat Semua User</a>
            </div>
        </div>
    </div>
</body>
</html>
