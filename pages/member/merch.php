<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('member');

$merch_items = $pdo->query("SELECT m.*, e.event_name FROM merch m JOIN events e ON m.event_id = e.event_id WHERE m.stock > 0 ORDER BY m.merch_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['merch_id'])) {
    $merch_id = $_POST['merch_id'];
    $quantity = $_POST['quantity'] ?? 1;
    
    $stmt = $pdo->prepare("INSERT INTO merch_orders (user_id, merch_id, quantity, status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$_SESSION['user_id'], $merch_id, $quantity]);
    
    header('Location: merch.php');
    exit();
}

$my_orders = $pdo->prepare("SELECT mo.*, m.item_name, m.price, e.event_name 
    FROM merch_orders mo 
    JOIN merch m ON mo.merch_id = m.merch_id 
    JOIN events e ON m.event_id = e.event_id 
    WHERE mo.user_id = ? 
    ORDER BY mo.created_at DESC");
$my_orders->execute([$_SESSION['user_id']]);
$my_orders = $my_orders->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchandise - Member</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>🛍️ MERCHANDISE</h2>
            </div>
            <div class="navbar-user">
                <a href="dashboard.php" class="btn btn-sm btn-primary">Kembali</a>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h3>Merchandise yang Tersedia</h3>
            </div>
            <?php if (count($merch_items) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Event</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($merch_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['event_name']); ?></td>
                        <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                        <td><?php echo $item['stock']; ?> pcs</td>
                        <td>
                            <form method="POST" action="" style="display: inline-flex; gap: 10px; align-items: center;">
                                <input type="hidden" name="merch_id" value="<?php echo $item['merch_id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $item['stock']; ?>" style="width: 60px; padding: 5px;">
                                <button type="submit" class="btn btn-sm btn-success">Pesan</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Belum ada merchandise yang tersedia</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Pesanan Saya</h3>
            </div>
            <?php if (count($my_orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                        <th>Event</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($my_orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['event_name']); ?></td>
                        <td><?php echo $order['quantity']; ?> pcs</td>
                        <td>Rp <?php echo number_format($order['price'] * $order['quantity'], 0, ',', '.'); ?></td>
                        <td>
                            <?php 
                            $badge_class = 'badge-warning';
                            if ($order['status'] == 'paid') $badge_class = 'badge-success';
                            if ($order['status'] == 'sent') $badge_class = 'badge-primary';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($order['status']); ?></span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align: center; padding: 20px; color: #999;">Anda belum pernah memesan merchandise</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
