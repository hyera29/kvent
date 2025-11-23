<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('panitia');

if (!isset($_GET['event_id'])) {
    header('Location: dashboard.php');
    exit();
}

$event_id = $_GET['event_id'];

$event = $pdo->prepare("SELECT * FROM events WHERE event_id = ? AND created_by = ?");
$event->execute([$event_id, $_SESSION['user_id']]);
$event = $event->fetch();

if (!$event) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_type = $_POST['ticked_type'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    $stmt = $pdo->prepare("INSERT INTO tickets (event_id, ticked_type, price, stock) VALUES (?, ?, ?, ?)");
    $stmt->execute([$event_id, $ticket_type, $price, $stock]);
    
    header('Location: manage_tickets.php?event_id=' . $event_id);
    exit();
}

$tickets = $pdo->prepare("SELECT * FROM tickets WHERE event_id = ?");
$tickets->execute([$event_id]);
$tickets = $tickets->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tiket - Panitia</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>🎫 KELOLA TIKET</h2>
            </div>
            <div class="navbar-user">
                <a href="dashboard.php" class="btn btn-sm btn-primary">Kembali</a>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h3>Event: <?php echo htmlspecialchars($event['event_name']); ?></h3>
            </div>
            
            <form method="POST" action="">
                <h4>Tambah Tiket Baru</h4>
                <div class="form-group">
                    <label>Jenis Tiket</label>
                    <input type="text" name="ticked_type" placeholder="Contoh: VIP, Reguler, Early Bird" required>
                </div>
                
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stock" min="1" required>
                </div>
                
                <button type="submit" class="btn btn-success">Tambah Tiket</button>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Daftar Tiket</h3>
            </div>
            <?php if (count($tickets) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jenis Tiket</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td>#<?php echo $ticket['ticked_id']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['ticked_type']); ?></td>
                        <td>Rp <?php echo number_format($ticket['price'], 0, ',', '.'); ?></td>
                        <td><?php echo $ticket['stock']; ?> tiket</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Belum ada tiket untuk event ini</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
