<?php
declare(strict_types=1);

require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

Auth::init();

// call the layout you want to use from layout folder
require_once LAYOUTS_PATH . "/main.layout.php";

// 1) Must be logged in...
if (!Auth::check()) {
    header('Location: /pages/login/index.php');
    exit;
}

// 2) ...and must be a Team Lead
$current = Auth::user();
if ($current['role'] !== 'team lead') {
    header('Location: /errors/unauthorized.error.php');
    exit;
}

$users = require_once HANDLERS_PATH . "/userView.handler.php";

// 4) Render
$title = "All Users";

renderMainLayout(
    function () use ($users) {
        ?>
    <div class="table-container">
        <h1 class="mb-6 title">All Registered Users</h1>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['id']) ?></td>
                        <td>
                            <?= htmlspecialchars($u['first_name']) ?>
                            <?= $u['middle_name'] ? htmlspecialchars($u['middle_name']) : '' ?>
                            <?= htmlspecialchars($u['last_name']) ?>
                        </td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($u['role'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    },
    $title,
);
