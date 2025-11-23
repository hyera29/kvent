<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('member');

$available_events = $pdo->query("SELECT e.*, 
    (SELECT COUNT(*) FROM registrations WHERE event_id = e.event_id) as registered_count 
    FROM events e 
    WHERE e.status = 'open' AND e.event_date >= CURDATE() 
    ORDER BY e.event_date ASC")->fetchAll();

$my_registrations = $pdo->prepare("SELECT r.*, e.event_name, e.event_date, e.location, p.status as payment_status 
    FROM registrations r 
    JOIN events e ON r.event_id = e.event_id 
    LEFT JOIN payments p ON r.reg_id = p.reg_id 
    WHERE r.user_id = ? 
    ORDER BY r.reg_date DESC");
$my_registrations->execute([$_SESSION['user_id']]);
$my_registrations = $my_registrations->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - K-Popers Event</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>💜 MEMBER DASHBOARD</h2>
            </div>
            <div class="navbar-user">
                <span>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></span>
                <a href="merch.php" class="btn btn-sm btn-warning">🛍️ Merchandise</a>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h3>Event yang Tersedia</h3>
            </div>
            <?php if (count($available_events) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Kuota</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($available_events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><span class="badge badge-info"><?php echo str_replace('_', ' ', $event['category']); ?></span></td>
                        <td><?php echo date('d/m/Y', strtotime($event['event_date'])); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td><?php echo $event['quota']; ?> orang</td>
                        <td><?php echo $event['registered_count']; ?> orang</td>
                        <td>
                            <?php if ($event['registered_count'] < $event['quota']): ?>
                                <a href="register_event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-success">Daftar</a>
                            <?php else: ?>
                                <span class="badge badge-danger">PENUH</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Belum ada event yang tersedia saat ini</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Riwayat Registrasi Saya</h3>
            </div>
            <?php if (count($my_registrations) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Event</th>
                        <th>Tanggal Event</th>
                        <th>Lokasi</th>
                        <th>Status Registrasi</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($my_registrations as $reg): ?>
                    <tr>
                        <td>#<?php echo $reg['reg_id']; ?></td>
                        <td><?php echo htmlspecialchars($reg['event_name']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($reg['event_date'])); ?></td>
                        <td><?php echo htmlspecialchars($reg['location']); ?></td>
                        <td>
                            <?php 
                            $badge_class = 'badge-warning';
                            if ($reg['status'] == 'paid') $badge_class = 'badge-success';
                            if ($reg['status'] == 'canceled') $badge_class = 'badge-danger';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($reg['status']); ?></span>
                        </td>
                        <td>
                            <?php if ($reg['payment_status']): ?>
                                <?php 
                                $badge_class = 'badge-warning';
                                if ($reg['payment_status'] == 'verified') $badge_class = 'badge-success';
                                if ($reg['payment_status'] == 'rejected') $badge_class = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($reg['payment_status']); ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning">BELUM BAYAR</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($reg['status'] == 'pending' && !$reg['payment_status']): ?>
                                <a href="payment.php?reg_id=<?php echo $reg['reg_id']; ?>" class="btn btn-sm btn-primary">Bayar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Anda belum pernah mendaftar event</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
