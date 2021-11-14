<?php

$server = "192.168.35.129";
$db = "master";
$timeout = 1;
$user = "sa";
$pass = "Moscow@2017";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

try {
    $pdo = new \PDO("sqlsrv:Server=$server;Database=$db;LoginTimeout=$timeout", $user, $pass, $options);
}
catch (Exception $e) {
    echo "Connection failed:\n";
    echo $e->getMessage();
    echo "\n\n";
}
