<?php
declare(strict_types=1);

require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
Auth::init();

if (Auth::check()) {
    header('Location: /pages/dashboard/index.php');
    exit;
}

// call the layout you want to use from layout folder
include LAYOUTS_PATH . "/main.layout.php";

$error = trim((string) ($_GET['error'] ?? ''));
$error = str_replace("%", " ", $error);

$title = "Login Page";

// functions that will render the layout of your choosing
renderMainLayout(
    function () use ($error) {
        ?>
    <div class="form-container">
        <div class="login-container">
            <form action="/handlers/auth.handler.php" method="POST">
                <h1 class="title">Sign In</h1>


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
    "./assets/css/login.css"
);
