<?php

include_once UTILS_PATH . "/envSetter.util.php";

try {
    $mongo = new MongoDB\Driver\Manager($databases["mURI"]);

    $command = new MongoDB\Driver\Command(["ping" => 1]);
    $mongo->executeCommand("admin", $command);

    echo "✅ Connected to MongoDB successfully.  <br>";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "❌ MongoDB connection failed: " . $e->getMessage() . "  <br>";
}
