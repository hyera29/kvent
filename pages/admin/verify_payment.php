<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('admin');

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header('Location: dashboard.php');
    exit();
}

$payment_id = $_GET['id'];
$action = $_GET['action'];

try {
    $pdo->beginTransaction();
    
    if ($action === 'verify') {
        $stmt = $pdo->prepare("UPDATE payments SET status = 'verified' WHERE payment_id = ?");
        $stmt->execute([$payment_id]);
        
        $payment = $pdo->prepare("SELECT reg_id FROM payments WHERE payment_id = ?");
        $payment->execute([$payment_id]);
        $reg = $payment->fetch();
        
        if ($reg) {
            $stmt = $pdo->prepare("UPDATE registrations SET status = 'paid' WHERE reg_id = ?");
            $stmt->execute([$reg['reg_id']]);
        }
        
        $_SESSION['success'] = 'Pembayaran berhasil diverifikasi!';
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE payments SET status = 'rejected' WHERE payment_id = ?");
        $stmt->execute([$payment_id]);
        
        $_SESSION['success'] = 'Pembayaran ditolak!';
    }
    
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
}

header('Location: dashboard.php');
exit();
?>
