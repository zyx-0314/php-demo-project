<?php

require_once UTILS_PATH . "/htmlEscape.util.php";

function head($pageTitle, array $pageCss = [])
{
    ?>
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <meta charset="utf-8">
            <title><?= htmlEscape($pageTitle ?? 'My Shop') ?></title>

            <?php
            if (!empty($pageCss)) {
                foreach ($pageCss as $cssFile) {
                    echo "<link rel=\"stylesheet\" href=\"{$cssFile}\">\n";
                }
            }
            ?>

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