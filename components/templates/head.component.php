<?php

require_once UTILS_PATH . "/htmlEscape.util.php";

function head($pageTitle, string $pageCss)
{
    ?>
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <meta charset="utf-8">
            <title><?= htmlEscape($pageTitle ?? 'My Shop') ?></title>

            <?php echo $pageCss != "" ? '<link rel="stylesheet" href="' . $pageCss . '">' : "" ?>
            <style>
                main {
                    min-height: 100dvh;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }
            </style>

            <!-- Libraries -->
            <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        </head>

        <body>
            <main>
                <?php
}
?>