<?php
require_once 'db.php';

$migration_file = __DIR__ . '/.migration_done';

if (!file_exists($migration_file)) {
    try {
        $stmt = $pdo->query("SELECT user_id, password FROM user WHERE password NOT LIKE '$2y$%' LIMIT 1");
        $needMigration = $stmt->fetch();
        
        if ($needMigration) {
            $pdo->beginTransaction();
            
            $users = $pdo->query("SELECT user_id, password FROM user WHERE password NOT LIKE '$2y$%'")->fetchAll();
            
            foreach ($users as $user) {
                $hashed = password_hash($user['password'], PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE user SET password = ? WHERE user_id = ?");
                $update->execute([$hashed, $user['user_id']]);
            }
            
            $pdo->commit();
            
            file_put_contents($migration_file, date('Y-m-d H:i:s'));
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
    }
}
?>
