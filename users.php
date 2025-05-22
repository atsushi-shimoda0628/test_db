<?php
require 'db_config.php';

header('Content-Type: application/json');

// データ取得クエリ
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);

// 結果を配列として取得
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSONとして出力
echo json_encode($users);
