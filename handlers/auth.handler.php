<?php
declare(strict_types=1);
require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

// Initialize session
Auth::init();

$host = 'host.docker.internal';
$port = $databases['pgPort'];
$username = $databases['pgUser'];
$password = $databases['pgPassword'];
$dbname = $databases['pgDB'];

// Connect to Postgres
$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$action = $_REQUEST['action'] ?? null;

// --- LOGIN ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');
    $passwordInput = trim($_POST['password'] ?? '');
    if (Auth::login($pdo, $usernameInput, $passwordInput)) {
        $user = Auth::user();

        if ($user["role"] == "team lead") {
            header('Location: /pages/users/index.php');
        } else {
            header('Location: /index.php');
        }
        exit;
    } else {
        header('Location: /pages/login/index.php?error=Invalid%Credentials');
        exit;
    }
}

// --- LOGOUT ---
elseif ($action === 'logout') {
    Auth::init();
    Auth::logout();
    header('Location: /pages/login/index.php');
    exit;
}

// If no valid action, redirect to login
header('Location: /pages/login/index.php');
exit;
