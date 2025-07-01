<?php
http_response_code(404);

require_once LAYOUTS_PATH . '/main.layout.php';

renderMainLayout(function () {
    ?>
    <section class="flex flex-col justify-center items-center p-8 h-full text-center">
        <h1 class="mb-4 font-extrabold text-gray-800 text-6xl">Unauthorized</h1>
        <p class="mb-6 text-gray-600 text-xl">Oops! Your Unauthorized for the page you’re looking for.</p>
        <a href="/index.php" class="inline-block bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded font-medium text-white">
            Go Back Home
        </a>
    </section>
    <?php
}, 'Page Not Found');
