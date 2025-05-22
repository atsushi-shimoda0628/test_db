<?php
// db_config.php
$host = 'localhost';
$dbname = 'test_db';
$user = 'root';
$pass = 'root'; // MAMPのデフォルトパスワード

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die(json_encode(['error' => 'DB接続失敗: ' . $e->getMessage()]));
}
