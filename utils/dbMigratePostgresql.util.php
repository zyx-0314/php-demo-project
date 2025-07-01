<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';
require_once 'bootstrap.php';
require_once UTILS_PATH . '/envSetter.util.php';

// Check Connection
$host = $databases['pgHost'];
$port = $databases['pgPort'];
$username = $databases['pgUser'];
$password = $databases['pgPassword'];
$dbname = $databases['pgDB'];

$dsn = "pgsql:host={$databases['pgHost']};port={$port};dbname={$dbname}";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Load the list of model SQL filenames
$models = require_once STATICDATAS_PATH . '/models.staticData.php';

foreach ($models as $modelFile) {
    $table = substr($modelFile, 0, -10);  // remove “.model.sql”
    echo "⏳ Resetting table `{$table}` using `database/{$modelFile}`…\n";

    // 1) Drop if exists
    $pdo->exec("DROP TABLE IF EXISTS \"{$table}\" CASCADE;");

    // 2) Load & apply SQL
    $path = DATABASE_PATH . "/{$modelFile}";
    if (!is_readable($path) || ($sql = file_get_contents($path)) === false) {
        throw new RuntimeException("Cannot read schema file: {$path}");
    }
    $pdo->exec($sql);

    echo "✅ Table `{$table}` recreated.\n";
}

$uploadsDirs = [
    UPLOAD_PATH . '/profile',
    UPLOAD_PATH . '/profile/thumbs',
    UPLOAD_PATH . '/post',
];

// Clear uploads directories
foreach ($uploadsDirs as $dir) {
    echo "Clearing uploads in {$dir}…\n";
    if (!is_dir($dir)) {
        return;
    }

    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($it as $item) {
        if ($item->isFile()) {
            @unlink($item->getRealPath());
        }
    }
    echo "✅ Cleared {$dir}\n";
}

echo "🎉 All migrations applied successfully!\n";
