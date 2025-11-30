<?php
require_once 'config/db.php';
require_once 'config/session.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } elseif (strlen($username) < 4) {
        $error = 'Username minimal 4 karakter!';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = 'Username hanya boleh huruf, angka, dan underscore!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($full_name) > 100) {
        $error = 'Nama lengkap terlalu panjang (max 100 karakter)!';
    } else {
        $check_username = $pdo->prepare("SELECT user_id FROM user WHERE username = ?");
        $check_username->execute([$username]);
        
        if ($check_username->fetch()) {
            $error = 'Username sudah digunakan! Silakan pilih username lain.';
        } else {
            $check_email = $pdo->prepare("SELECT user_id FROM user WHERE email = ?");
            $check_email->execute([$email]);
            
            if ($check_email->fetch()) {
                $error = 'Email sudah terdaftar! Silakan gunakan email lain.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                try {
                    $stmt = $pdo->prepare("INSERT INTO user (username, email, password, full_name, role, created_at) VALUES (?, ?, ?, ?, 'member', NOW())");
                    $stmt->execute([$username, $email, $hashed_password, $full_name]);
                    
                    $success = 'Registrasi berhasil! Silakan login dengan akun Anda.';
                    
                    $_POST = [];
                } catch (PDOException $e) {
                    $error = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - K-Popers Event</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .register-form {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        
        .success-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        
        .back-link a:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box register-form">
            <h2>💜 Daftar Akun K-Popers 💜</h2>
            <p style="text-align: center; color: #ffffffff; margin-bottom: 30px;">
                Bergabunglah dengan komunitas K-pop dan ikuti event seru!
            </p>
            
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <?= htmlspecialchars($success) ?>
                    <br><br>
                    <a href="index.php" style="color: white; text-decoration: underline;">Klik di sini untuk login</a>
                </div>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                        <small style="color: #666;">Username untuk login (tanpa spasi)</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required>
                        <small style="color: #666;">Minimal 6 karakter</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Konfirmasi Password *</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        Daftar Sekarang
                    </button>
                </form>
                
                <div class="back-link">
                    Sudah punya akun? <a href="index.php">Login di sini</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
