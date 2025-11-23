<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('panitia');

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'] ?? '';
    $descriptions = $_POST['descriptions'] ?? '';
    $category = $_POST['category'] ?? 'other';
    $event_date = $_POST['event_date'] ?? '';
    $location = $_POST['location'] ?? '';
    $quota = $_POST['quota'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO events (event_name, descriptions, category, event_date, location, quota, status, created_by) VALUES (?, ?, ?, ?, ?, ?, 'open', ?)");
        $stmt->execute([$event_name, $descriptions, $category, $event_date, $location, $quota, $_SESSION['user_id']]);
        
        $success = 'Event berhasil dibuat!';
        header('Location: dashboard.php');
        exit();
    } catch (Exception $e) {
        $error = 'Gagal membuat event: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Event Baru - Panitia</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>📝 BUAT EVENT BARU</h2>
            </div>
            <div class="navbar-user">
                <a href="dashboard.php" class="btn btn-sm btn-primary">Kembali</a>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h3>Form Event Baru</h3>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Nama Event *</label>
                    <input type="text" name="event_name" required>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="descriptions" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Kategori *</label>
                    <select name="category" required>
                        <option value="dance_cover">Dance Cover</option>
                        <option value="random_play_dance">Random Play Dance</option>
                        <option value="noraebang">Noraebang (Karaoke)</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Event *</label>
                    <input type="date" name="event_date" required>
                </div>
                
                <div class="form-group">
                    <label>Lokasi *</label>
                    <input type="text" name="location" required>
                </div>
                
                <div class="form-group">
                    <label>Kuota Peserta *</label>
                    <input type="number" name="quota" min="1" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Buat Event</button>
            </form>
        </div>
    </div>
</body>
</html>
