<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
requireRole('admin');

$users = $pdo->query("SELECT * FROM user ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <h2>🎵 KELOLA PENGGUNA</h2>
            </div>
            <div class="navbar-user">
                <a href="dashboard.php" class="btn btn-sm btn-primary">Kembali</a>
                <a href="/pages/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="card">
            <div class="card-header">
                <h3>Daftar Pengguna</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Nama Lengkap</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?php echo $user['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td>
                            <?php 
                            $badge_class = 'badge-primary';
                            if ($user['role'] == 'admin') $badge_class = 'badge-danger';
                            if ($user['role'] == 'panitia') $badge_class = 'badge-warning';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($user['role']); ?></span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
