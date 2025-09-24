<?php
// display_data.php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/connect.php';

function h(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$rows = [];
$err  = null;

try {
    if (isset($pdo) && $pdo instanceof PDO) {
        $stmt = $pdo->query('SELECT * FROM users');
        $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    } elseif (isset($connection) && $connection instanceof PDO) {
        $stmt = $connection->query('SELECT * FROM users');
        $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    } elseif (isset($connection) && $connection instanceof mysqli) {
        $result = $connection->query('SELECT * FROM users');
        if ($result instanceof mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }
    } else {
        $err = 'No valid database connectionection found. Ensure connectionect.php sets $pdo (PDO) or $connection (PDO/mysqli).';
    }
} catch (Throwable $e) {
    $err = $e->getMessage();
}

$headers = !empty($rows) ? array_keys($rows[0]) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; text-align: left; }
        .top { margin-bottom: 16px; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <div class="top">
        <a href="sign-up.php">Return to sign-up</a>
    </div>

    <?php if ($err): ?>
        <p class="error"><?php echo h($err); ?></p>
    <?php elseif (empty($rows)): ?>
        <p>No users found.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <?php foreach ($headers as $col): ?>
                    <th><?php echo h((string)$col); ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <?php foreach ($headers as $col): ?>
                        <td><?php echo h((string)($row[$col] ?? '')); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="top" style="margin-top:16px;">
        <a href="sign-up.php">Return to sign-up</a>
    </div>
</body>
</html>