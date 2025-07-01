<?php
declare(strict_types=1);

function navHeader(array $navList, ?array $user = null): void
{
    ?>
    <header>
        <nav class="bg-gray-300 dark:bg-gray-500 px-4 lg:px-6 py-2.5 border-gray-200">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                <a href="/index.php" class="flex items-center">
                    <img src="/assets/img/nyebe_white.png" class="mr-3 h-6 sm:h-9" alt="Nyebe Logo" />
                    <span class="self-center font-semibold dark:text-white text-lg whitespace-nowrap">
                        <span class="font-black">NYEBE:</span> AD-TaskManager
                    </span>
                </a>

                <div class="flex items-center lg:order-2">
                    <?php if ($user): ?>
                        <?php
                        $name = htmlspecialchars($user['first_name']);
                        $role = htmlspecialchars($user['role'] ?? '');
                        ?>
                        <span class="mr-4 text-gray-700 dark:text-white">
                            Welcome, <?= "{$name}:{$role}" ?>
                        </span>
                        <a href="/pages/logout/index.php" class="bg-red-600 hover:bg-red-700 mr-4 px-4 py-2 rounded text-white">
                            Log out
                        </a>
                        <a href="/pages/account/index.php"
                            class="bg-slate-600 hover:bg-slate-700 mr-4 px-4 py-2 rounded text-white">
                            Settings
                        </a>
                    <?php else: ?>
                        <a href="/pages/login/index.php"
                            class="bg-blue-600 hover:bg-blue-700 mr-4 px-4 py-2 rounded text-white">
                            Log in
                        </a>
                        <a href="/pages/signup/index.php"
                            class="bg-gray-200 hover:bg-gray-100 mr-4 px-4 py-2 rounded text-white">
                            Sign Up
                        </a>
                    <?php endif; ?>

                    <button data-collapse-toggle="mobile-menu-2" type="button"
                        class="lg:hidden inline-flex items-center hover:bg-gray-100 dark:hover:bg-gray-700 ml-1 p-2 rounded-lg focus:ring-2 focus:ring-gray-200"
                        aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5h14M3 10h14M3 15h14" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="hidden lg:flex justify-between items-center lg:order-1 w-full lg:w-auto" id="mobile-menu-2">
                    <ul class="flex lg:flex-row flex-col lg:space-x-8 mt-4 lg:mt-0 font-medium">
                        <?php foreach ($navList as $nav):
                            if ($nav["for"] == "all" || htmlspecialchars($user['role'] ?? '') == "team lead"):
                                ?>
                                <li>
                                    <a href="<?= htmlspecialchars($nav['link']) ?>"
                                        class="block hover:bg-gray-200 py-2 pr-4 pl-3 rounded text-gray-800 dark:text-white">
                                        <?= htmlspecialchars($nav['label']) ?>
                                    </a>
                                </li>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php
}
