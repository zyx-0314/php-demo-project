<?php
declare(strict_types=1);

require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/envSetter.util.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/updateAccount.util.php';

Auth::init();

// Only logged-in users can update
if (!Auth::check()) {
    header('Location: /pages/login/index.php');
    exit;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/account/index.php');
    exit;
}

// Build PDO
$host = 'host.docker.internal';
$port = $databases['pgPort'];
$dbUser = $databases['pgUser'];
$dbPass = $databases['pgPassword'];
$dbName = $databases['pgDB'];
$dsn = "pgsql:host={$host};port={$port};dbname={$dbName}";
$pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
// ensure schema resolution
$pdo->exec("SET search_path TO public");

// Gather raw input
$input = [
    'first_name' => $_POST['first_name'] ?? '',
    'middle_name' => $_POST['middle_name'] ?? '',
    'last_name' => $_POST['last_name'] ?? '',
    'username' => $_POST['username'] ?? '',
    'password' => $_POST['password'] ?? '',
];

// Handle file upload
$file = $_FILES['profile_image'] ?? null;

// 1) Validate
$errors = UpdateAccount::validate($pdo, $input, $file);

if (count($errors) > 0) {
    $_SESSION['update_errors'] = $errors;
    $_SESSION['update_old'] = $input;
    header('Location: /pages/account/index.php');
    exit;
}

// 2) Apply update
try {
    UpdateAccount::apply($pdo, $input, $file);

} catch (PDOException $e) {
    // In practice, most duplicates caught above –
    // but catch any unexpected SQL error.
    error_log('[updateAccount.handler] PDOException: ' . $e->getMessage());
    http_response_code(500);
    exit('Server error.');
}

// 3) Success — clear flashes and redirect
unset($_SESSION['update_errors'], $_SESSION['update_old']);
header('Location: /pages/account/index.php?message=Account%20updated%20successfully');
exit;
