<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('member');

if (!isset($_GET['event_id'])) {
    header('Location: dashboard.php');
    exit();
}

$event_id = $_GET['event_id'];

$event = $pdo->prepare("SELECT * FROM events WHERE event_id = ? AND status = 'open'");
$event->execute([$event_id]);
$event = $event->fetch();

if (!$event) {
    header('Location: dashboard.php');
    exit();
}

$tickets = $pdo->prepare("SELECT * FROM tickets WHERE event_id = ? AND stock > 0");
$tickets->execute([$event_id]);
$tickets = $tickets->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    
    $ticket = $pdo->prepare("SELECT * FROM tickets WHERE ticked_id = ?");
    $ticket->execute([$ticket_id]);
    $ticket = $ticket->fetch();
    
    if ($ticket && $ticket['stock'] > 0) {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id, ticket_id, status) VALUES (?, ?, ?, 'pending')");
            $stmt->execute([$_SESSION['user_id'], $event_id, $ticket_id]);
            
            $reg_id = $pdo->lastInsertId();
            
            $stmt = $pdo->prepare("INSERT INTO payments (reg_id, amount, status) VALUES (?, ?, 'waiting')");
            $stmt->execute([$reg_id, $ticket['price']]);
            
            $stmt = $pdo->prepare("UPDATE tickets SET stock = stock - 1 WHERE ticked_id = ?");
            $stmt->execute([$ticket_id]);
            
            $pdo->commit();
            
            header('Location: payment.php?reg_id=' . $reg_id);
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Gagal mendaftar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event - Member</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>📝 DAFTAR EVENT</h2>
            </div>
            <div class="navbar-user">
                <a href="dashboard.php" class="btn btn-sm btn-primary">Kembali</a>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
            </div>
            
            <div style="padding: 20px;">
                <p><strong>Kategori:</strong> <?php echo str_replace('_', ' ', $event['category']); ?></p>
                <p><strong>Tanggal:</strong> <?php echo date('d/m/Y', strtotime($event['event_date'])); ?></p>
                <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                <p><strong>Kuota:</strong> <?php echo $event['quota']; ?> orang</p>
                <p><strong>Deskripsi:</strong> <?php echo nl2br(htmlspecialchars($event['descriptions'] ?? '-')); ?></p>
            </div>
            
            <?php if (count($tickets) > 0): ?>
            <form method="POST" action="">
                <h4 style="padding: 0 20px;">Pilih Tiket</h4>
                <?php foreach ($tickets as $ticket): ?>
                <div style="padding: 15px 20px; border: 2px solid #f0f0f0; margin: 10px 20px; border-radius: 10px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="ticket_id" value="<?php echo $ticket['ticked_id']; ?>" required style="margin-right: 15px;">
                        <div style="flex: 1;">
                            <strong><?php echo htmlspecialchars($ticket['ticked_type']); ?></strong> - 
                            <span style="color: #667eea; font-weight: bold;">Rp <?php echo number_format($ticket['price'], 0, ',', '.'); ?></span>
                            <br>
                            <small style="color: #999;">Tersisa: <?php echo $ticket['stock']; ?> tiket</small>
                        </div>
                    </label>
                </div>
                <?php endforeach; ?>
                
                <div style="padding: 20px;">
                    <button type="submit" class="btn btn-success btn-block">Daftar Sekarang</button>
                </div>
            </form>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Tiket sudah habis untuk event ini</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
