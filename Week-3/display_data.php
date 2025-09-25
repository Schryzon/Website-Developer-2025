<?php
// display_data.php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/connect.php';

function h(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$err = null;
$all_data = [];

try {
    $db = null;

    if (isset($pdo) && $pdo instanceof PDO) {
        $db = $pdo;
    } elseif (isset($connection) && $connection instanceof PDO) {
        $db = $connection;
    }

    if ($db instanceof PDO) {
        // fetch all table names
        $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $rows = $db->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
            $all_data[$table] = $rows;
        }
    } elseif (isset($connection) && $connection instanceof mysqli) {
        $tables = [];
        $result = $connection->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        $result->free();

        foreach ($tables as $table) {
            $rows = [];
            $res = $connection->query("SELECT * FROM `$table`");
            if ($res instanceof mysqli_result) {
                while ($row = $res->fetch_assoc()) {
                    $rows[] = $row;
                }
                $res->free();
            }
            $all_data[$table] = $rows;
        }
    } else {
        $err = 'No valid database connection found. Ensure connect.php sets $pdo (PDO) or $connection (PDO/mysqli).';
    }
} catch (Throwable $e) {
    $err = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Data</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        h2 { margin-top: 32px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 24px; }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 14px; }
        th { background: #f4f4f4; text-align: left; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <div class="top">
        <a href="sign-up.php">Return to sign-up</a>
    </div>

    <?php if ($err): ?>
        <p class="error"><?php echo h($err); ?></p>
    <?php else: ?>
        <?php foreach ($all_data as $table => $rows): ?>
            <h2><?php echo h($table); ?></h2>
            <?php if (empty($rows)): ?>
                <p><em>No rows.</em></p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <?php foreach (array_keys($rows[0]) as $col): ?>
                                <th><?php echo h($col); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <?php foreach ($row as $val): ?>
                                    <td><?php echo h((string)$val); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
