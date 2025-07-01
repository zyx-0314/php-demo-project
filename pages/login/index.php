<?php
declare(strict_types=1);

require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
Auth::init();

if (Auth::check()) {
    header('Location: /index.php');
    exit;
}

// call the layout you want to use from layout folder
require_once LAYOUTS_PATH . "/main.layout.php";

$error = trim((string) ($_GET['error'] ?? ''));
$error = str_replace("%", " ", $error);

$message = trim((string) ($_GET['message'] ?? ''));
$message = str_replace("%", " ", $message);

$title = "Login Page";

// functions that will render the layout of your choosing
renderMainLayout(
    function () use ($error, $message) {
        ?>
    <div class="form-container">
        <div class="login-container">
            <form action="/handlers/auth.handler.php" method="POST">
                <h1 class="title">Sign In</h1>

                <?php if (!empty($message)): ?>
                    <div class="mb-4 text-green-600">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" required class="input">
                </div>

                <div class="mb-6">
                    <label for="password" class="label">Password</label>
                    <input id="password" name="password" type="password" required class="input">
                </div>

                <?php if (!empty($error)): ?>
                    <div class="mb-4 text-red-600">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <input type="hidden" name="action" value="login">
                <button type="submit" class="button">Log In</button>
            </form>
        </div>
    </div>
    <?php
    },
    $title,
    [
        "css" => [
            "./assets/css/login.css"
        ],
    ]
);
