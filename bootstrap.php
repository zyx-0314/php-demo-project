<?php
define('BASE_PATH', realpath(__DIR__));
define('HANDLERS_PATH', realpath(BASE_PATH . "/handlers"));
define('UTILS_PATH', realpath(BASE_PATH . "/utils"));
define('DATABASE_PATH', realpath(BASE_PATH . "/database"));
define('DUMMIES_PATH', realpath(BASE_PATH . "/staticData/dummies"));

chdir(BASE_PATH);
