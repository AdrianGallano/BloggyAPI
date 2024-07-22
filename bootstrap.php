<?php
require "../vendor/autoload.php";

use Dotenv\Dotenv;
use Src\Config\DatabaseConnector;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

try {
    $pdo = (new DatabaseConnector)->getConnection();
    $sql = file_get_contents(__DIR__ . "\migrations\bloggy_v1.sql");
    $statements = explode(';', $sql);
    foreach ($statements as $statement) {
        if (!empty(trim($statement))) {
            $pdo->exec($statement);
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
    error_log($e->getMessage());
}
