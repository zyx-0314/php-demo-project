<?php
declare(strict_types=1);

require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

// call the layout you want to use from layout folder
require_once LAYOUTS_PATH . "/main.layout.php";

Auth::init();

// Redirect logged-in users away from signup
if (Auth::check()) {
    header('Location: /index.php');
    exit;
}

// Pull any flash errors and old input, then clear them
$errors = $_SESSION['signup_errors'] ?? [];
$old = $_SESSION['signup_old'] ?? [];
unset($_SESSION['signup_errors'], $_SESSION['signup_old']);

$title = "Sign Up";

// Render the main layout
renderMainLayout(
    function () use ($errors, $old) {
        ?>
    <div class="form-container">
        <div class="signup-container">
            <h1 class="title">Create Account</h1>

            <?php if (!empty($errors)): ?>
                <div class="mb-4 text-red-600">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/handlers/signup.handler.php" method="POST">
                <div class="mb-4">
                    <label for="first_name" class="label">First Name</label>
                    <input id="first_name" name="first_name" type="text" required class="input"
                        value="<?= htmlspecialchars($old['first_name'] ?? '') ?>">
                </div>

                <div class="mb-4">
                    <label for="middle_name" class="label">Middle Name</label>
                    <input id="middle_name" name="middle_name" type="text" class="input"
                        value="<?= htmlspecialchars($old['middle_name'] ?? '') ?>">
                </div>

                <div class="mb-4">
                    <label for="last_name" class="label">Last Name</label>
                    <input id="last_name" name="last_name" type="text" required class="input"
                        value="<?= htmlspecialchars($old['last_name'] ?? '') ?>">
                </div>

                <div class="mb-4">
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" required class="input"
                        value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                </div>

                <div class="mb-4">
                    <label for="password" class="label">Password</label>
                    <input id="password" name="password" type="password" required class="input">
                </div>

                <div class="mb-6">
                    <label for="role" class="label">Role</label>
                    <select id="role" name="role" required class="input">
                        <option value="">-- Select Role --</option>
                        <option value="team lead" <?= isset($old['role']) && $old['role'] === 'team lead' ? 'selected' : '' ?>>
                            Team Lead
                        </option>
                        <option value="member" <?= isset($old['role']) && $old['role'] === 'member' ? 'selected' : '' ?>>
                            Member
                        </option>
                    </select>
                </div>

                <button type="submit" class="button">Sign Up</button>
            </form>
        </div>
    </div>
    <?php
    },
    $title,
    [
        "css" => [
            "./assets/css/signup.css"
        ],
    ]
);
