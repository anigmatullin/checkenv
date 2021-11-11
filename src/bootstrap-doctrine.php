<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$_ENV['DB_REQUIRED'] = $_ENV['DB_DATABASE'];
$_ENV['DB_DATABASE'] = "master";

$connectionParams = [
    'dbname' => $_ENV['DB_DATABASE'],
    'user' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'],
    'driver' => 'pdo_sqlsrv',
];

$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

$stmt = $conn->prepare('SELECT * FROM sys.databases');
// THIS WILL NOT WORK:
//$stmt->bindValue(1, array(1, 2, 3, 4, 5, 6));
$res = $stmt->executeQuery();

while ($row = $res->fetchAssociative()) {
    echo $row["name"], "\n";
}

echo "\n";

$sm = $conn->getSchemaManager();
$databases = $sm->listDatabases();

dd($databases);
