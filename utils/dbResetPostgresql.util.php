<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

require_once 'bootstrap.php';

require_once UTILS_PATH . '/envSetter.util.php';

$host = $databases['pgHost'];
$port = $databases['pgPort'];
$username = $databases['pgUser'];
$password = $databases['pgPassword'];
$dbname = $databases['pgDB'];

$dsn = "pgsql:host={$databases['pgHost']};port={$port};dbname={$dbname}";
echo $dsn;
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

echo "Applying schema from database/users.model.sql…\n";

$sql = file_get_contents('database/users.model.sql');

if ($sql === false) {
    throw new RuntimeException("Could not read database/users.model.sql");
} else {
    echo "Creation Success from the database/users.model.sql";
}

$pdo->exec($sql);

echo "Truncating tables…\n";
foreach (['users'] as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}

echo "✅ PostgreSQL reset complete!\n";
