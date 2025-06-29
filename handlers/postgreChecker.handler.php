<?php

require_once UTILS_PATH . '/envSetter.util.php';

$host = 'host.docker.internal';
$port = $databases['pgPort'];
$username = $databases['pgUser'];
$password = $databases['pgPassword'];
$dbname = $databases['pgDB'];

$postgresqlCheckerResult = "";

$conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";

$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    $postgresqlCheckerResult = "❌ Connection Failed: " . pg_last_error() . "  <br>";
    exit();
} else {
    $postgresqlCheckerResult = "✔️ PostgreSQL Connection  <br>";
    pg_close($dbconn);
}

return $postgresqlCheckerResult;