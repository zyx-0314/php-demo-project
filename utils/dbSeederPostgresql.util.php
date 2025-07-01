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

// 2) Load model filenames (e.g. ['users.model.sql','images.model.sql',...])
$models = require STATICDATAS_PATH . '/models.staticData.php';

$uploadsDirs = [
    UPLOAD_PATH . '/profile',
    UPLOAD_PATH . '/profile/thumbs',
    UPLOAD_PATH . '/post',
];

// 3) Clear uploads directories
foreach ($uploadsDirs as $dir) {
    echo "Clearing uploads in {$dir}…\n";
    if (!is_dir($dir)) {
        echo "❌ Directory does not exist: {$dir}\n";
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

// 4) Drop & recreate each table, then seed if logic exists
foreach ($models as $modelFile) {
    $table = substr($modelFile, 0, -10);  // “users.model.sql” → “users”
    echo "⏳ Resetting table `{$table}`…\n";

    // a) Drop
    $pdo->exec("DROP TABLE IF EXISTS \"{$table}\" CASCADE;");

    // b) Re-create
    $schemaSql = file_get_contents(DATABASE_PATH . "/{$modelFile}");
    if ($schemaSql === false) {
        throw new RuntimeException("Cannot read schema: {$modelFile}");
    }
    $pdo->exec($schemaSql);
    echo "✅ Table `{$table}` recreated.\n";

    // c) Seed data
    switch ($table) {
        case 'users':
            echo "→ Seeding users…\n";
            $users = require DUMMIES_PATH . '/users.staticData.php';
            $stmt = $pdo->prepare("
                INSERT INTO public.\"users\"
                  (username, role, first_name, last_name, password)
                VALUES (:username, :role, :fn, :ln, :pw)
            ");
            foreach ($users as $u) {
                $stmt->execute([
                    ':username' => $u['username'],
                    ':role' => $u['role'],
                    ':fn' => $u['first_name'],
                    ':ln' => $u['last_name'],
                    ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
                ]);
            }
            echo "✅ Users seeded.\n";
            break;
        default:
            echo "ℹ️  No seeder for `{$table}`, skipping.\n";
    }
}

echo "🎉 Database reset & seed complete!\n";
