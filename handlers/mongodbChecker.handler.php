<?php

require_once UTILS_PATH . "/envSetter.util.php";

$mongoCheckerResult = "";

try {
    $mongo = new MongoDB\Driver\Manager($databases["mURI"]);

    $command = new MongoDB\Driver\Command(["ping" => 1]);
    $mongo->executeCommand("admin", $command);

    $mongoCheckerResult = "✅ Connected to MongoDB successfully.  <br>";
} catch (MongoDB\Driver\Exception\Exception $e) {
    $mongoCheckerResult = "❌ MongoDB connection failed: " . $e->getMessage() . "  <br>";
}

return $mongoCheckerResult;