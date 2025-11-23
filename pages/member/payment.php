<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('member');

if (!isset($_GET['reg_id'])) {
    header('Location: dashboard.php');
    exit();
}

$reg_id = $_GET['reg_id'];

$registration = $pdo->prepare("SELECT r.*, e.event_name, e.event_date, t.ticked_type, p.* 
    FROM registrations r 
    JOIN events e ON r.event_id = e.event_id 
    JOIN tickets t ON r.ticket_id = t.ticked_id 
    LEFT JOIN payments p ON r.reg_id = p.reg_id 
    WHERE r.reg_id = ? AND r.user_id = ?");
$registration->execute([$reg_id, $_SESSION['user_id']]);
$registration = $registration->fetch();

if (!$registration) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $payment_id = $registration['payment_id'];
    
    $proof_file = null;
    if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $max_size = 5 * 1024 * 1024;
        
        $file_size = $_FILES['proof']['size'];
        $file_ext = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
        $tmp_name = $_FILES['proof']['tmp_name'];
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $tmp_name);
        finfo_close($finfo);
        
        $allowed_mimes = ['image/jpeg', 'image/png'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $error = 'Format file harus JPG, JPEG, atau PNG!';
        } elseif (!in_array($mime_type, $allowed_mimes)) {
            $error = 'File harus berupa gambar valid (JPG/PNG)!';
        } elseif ($file_size > $max_size) {
            $error = 'Ukuran file maksimal 5MB!';
        } else {
            $upload_dir = '../../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $proof_file = 'payment_' . uniqid() . '_' . $payment_id . '.' . $file_ext;
            move_uploaded_file($tmp_name, $upload_dir . $proof_file);
        }
    }
    
    if (!isset($error)) {
        $stmt = $pdo->prepare("UPDATE payments SET payment_method = ?, proof = ?, status = 'waiting' WHERE payment_id = ?");
        $stmt->execute([$payment_method, $proof_file, $payment_id]);
        
        header('Location: dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Member</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>💳 PEMBAYARAN</h2>
            </div>
            <div class="navbar-user">
                <a href="dashboard.php" class="btn btn-sm btn-primary">Kembali</a>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h3>Detail Pembayaran</h3>
            </div>
            
            <div style="padding: 20px; background: #f9f9f9; margin: 20px; border-radius: 10px;">
                <h4>Informasi Registrasi</h4>
                <p><strong>Event:</strong> <?php echo htmlspecialchars($registration['event_name']); ?></p>
                <p><strong>Tanggal Event:</strong> <?php echo date('d/m/Y', strtotime($registration['event_date'])); ?></p>
                <p><strong>Jenis Tiket:</strong> <?php echo htmlspecialchars($registration['ticked_type']); ?></p>
                <p><strong>Total Pembayaran:</strong> <span style="font-size: 24px; color: #667eea; font-weight: bold;">Rp <?php echo number_format($registration['amount'], 0, ',', '.'); ?></span></p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($registration['status'] == 'waiting' || !$registration['proof']): ?>
            <form method="POST" action="" enctype="multipart/form-data" style="padding: 20px;">
                <div class="form-group">
                    <label>Metode Pembayaran *</label>
                    <select name="payment_method" required>
                        <option value="qris">QRIS</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Upload Bukti Pembayaran *</label>
                    <input type="file" name="proof" accept="image/jpeg,image/png,image/jpg" required>
                    <small style="color: #999;">Format: JPG, PNG, JPEG. Max 5MB</small>
                </div>
                
                <button type="submit" class="btn btn-success btn-block">Kirim Bukti Pembayaran</button>
            </form>
            <?php else: ?>
                <div style="padding: 20px; text-align: center;">
                    <p><strong>Status Pembayaran:</strong> 
                        <?php 
                        $badge_class = 'badge-warning';
                        if ($registration['status'] == 'verified') $badge_class = 'badge-success';
                        if ($registration['status'] == 'rejected') $badge_class = 'badge-danger';
                        ?>
                        <span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($registration['status']); ?></span>
                    </p>
                    <?php if ($registration['proof']): ?>
                        <p><a href="/uploads/<?php echo $registration['proof']; ?>" target="_blank" class="btn btn-primary">Lihat Bukti Pembayaran</a></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
