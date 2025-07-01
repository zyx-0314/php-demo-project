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

// 2) Load the list of your model files
//    e.g. ['users.model.sql', 'images.model.sql', …]
$models = require_once STATICDATAS_PATH . '/models.staticData.php';

// 3) For each model, derive the table name & truncate it
foreach ($models as $modelFile) {
    $table = substr($modelFile, 0, -10);  // strip “.model.sql”

    echo "⏳ Truncating table `{$table}`…\n";
    $pdo->exec("TRUNCATE TABLE \"{$table}\" RESTART IDENTITY CASCADE;");
    echo "✅ Table `{$table}` cleared.\n";
}

// 4) Clear uploads directories
$uploadsDirs = [
    UPLOAD_PATH . '/profile',
    UPLOAD_PATH . '/profile/thumbs',
    UPLOAD_PATH . '/post',
];

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

echo "🎉 All data cleared successfully!\n";
