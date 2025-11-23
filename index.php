<?php
require_once 'config/db.php';
require_once 'config/init.php';
require_once 'config/session.php';

if (isLoggedIn()) {
    switch($_SESSION['role']) {
        case 'admin':
            header('Location: /pages/admin/dashboard.php');
            break;
        case 'panitia':
            header('Location: /pages/panitia/dashboard.php');
            break;
        case 'member':
            header('Location: /pages/member/dashboard.php');
            break;
    }
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        
        switch($user['role']) {
            case 'admin':
                header('Location: /pages/admin/dashboard.php');
                break;
            case 'panitia':
                header('Location: /pages/panitia/dashboard.php');
                break;
            case 'member':
                header('Location: /pages/member/dashboard.php');
                break;
        }
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Event K-Popers</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>🎵 K-POPERS EVENT</h1>
                <p>Sistem Informasi Manajemen Event</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            
            <div class="login-info">
                <h3>Akun Testing:</h3>
                <p><strong>Admin:</strong> admin1 / password</p>
                <p><strong>Panitia:</strong> panitia / password</p>
                <p><strong>Member:</strong> member / password</p>
            </div>
        </div>
    </div>
</body>
</html>
