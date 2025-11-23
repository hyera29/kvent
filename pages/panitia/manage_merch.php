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
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    $stmt = $pdo->prepare("INSERT INTO merch (event_id, item_name, price, stock) VALUES (?, ?, ?, ?)");
    $stmt->execute([$event_id, $item_name, $price, $stock]);
    
    header('Location: manage_merch.php?event_id=' . $event_id);
    exit();
}

$merch = $pdo->prepare("SELECT * FROM merch WHERE event_id = ?");
$merch->execute([$event_id]);
$merch = $merch->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Merchandise - Panitia</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>🛍️ KELOLA MERCHANDISE</h2>
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
                <h4>Tambah Merchandise Baru</h4>
                <div class="form-group">
                    <label>Nama Item</label>
                    <input type="text" name="item_name" placeholder="Contoh: Lightstick, Photocard, T-Shirt" required>
                </div>
                
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stock" min="1" required>
                </div>
                
                <button type="submit" class="btn btn-success">Tambah Merchandise</button>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Daftar Merchandise</h3>
            </div>
            <?php if (count($merch) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Item</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($merch as $item): ?>
                    <tr>
                        <td>#<?php echo $item['merch_id']; ?></td>
                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                        <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                        <td><?php echo $item['stock']; ?> pcs</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Belum ada merchandise untuk event ini</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
