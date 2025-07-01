<?php
declare(strict_types=1);

require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

Auth::init();

// call the layout you want to use from layout folder
require_once LAYOUTS_PATH . "/main.layout.php";

// Only for logged-in users
if (!Auth::check()) {
    header('Location: /pages/login/index.php');
    exit;
}

// Pull current user data
$current = Auth::user();

// Pull any flash errors, old input, or success message
$errors = $_SESSION['update_errors'] ?? [];
$old = $_SESSION['update_old'] ?? [];
$message = trim((string) ($_GET['message'] ?? ''));

// Clear flashes
unset($_SESSION['update_errors'], $_SESSION['update_old']);

$title = "My Account";

// Render
renderMainLayout(
    function () use ($current, $errors, $old, $message) {
        ?>
    <div class="form-container">
        <div class="account-container">
            <h1 class="title">My Account</h1>

            <?php if ($message): ?>
                <div class="mb-4 text-green-600">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="mb-4 text-red-600">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/handlers/updateAccount.handler.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="profile_image" class="label">Profile Picture</label><br>
                    <?php if (!empty($current['profile_image_path'])): ?>
                        <img id="imagePreview" src="<?= htmlspecialchars($current['profile_image_path']) ?>"
                            alt="Current avatar" style="max-width:150px; max-height:150px; display:block; margin-bottom:0.5em;">
                    <?php else: ?>
                        <img id="imagePreview" src="/assets/img/default-avatar.png" alt="Default avatar"
                            style="max-width:150px; max-height:150px; display:block; margin-bottom:0.5em;">
                    <?php endif; ?>

                    <input id="profile_image" name="profile_image" type="file" accept="image/jpeg,image/png,image/gif"
                        class="input" style="padding:0.25em 0;">
                </div>

                <div class="mb-4">
                    <label for="first_name" class="label">First Name</label>
                    <input id="first_name" name="first_name" type="text" required class="input"
                        value="<?= htmlspecialchars($old['first_name'] ?? $current['first_name']) ?>">
                </div>

                <div class="mb-4">
                    <label for="middle_name" class="label">Middle Name</label>
                    <input id="middle_name" name="middle_name" type="text" class="input"
                        value="<?= htmlspecialchars($old['middle_name'] ?? ($current['middle_name'] ?? '')) ?>">
                </div>

                <div class="mb-4">
                    <label for="last_name" class="label">Last Name</label>
                    <input id="last_name" name="last_name" type="text" required class="input"
                        value="<?= htmlspecialchars($old['last_name'] ?? $current['last_name']) ?>">
                </div>

                <div class="mb-4">
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" required class="input"
                        value="<?= htmlspecialchars($old['username'] ?? $current['username']) ?>">
                </div>

                <div class="mb-6">
                    <label for="password" class="label">New Password <small>(leave blank to keep current)</small></label>
                    <input id="password" name="password" type="password" class="input">
                </div>
                <button type="submit" class="button">Update Account</button>
            </form>
        </div>
    </div>
    <?php
    },
    $title,
    [
        'js' => [
            "./assets/js/script.js"
        ]
    ]
);
