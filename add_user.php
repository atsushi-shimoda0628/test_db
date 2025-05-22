<?php
require 'db_config.php';

header('Content-Type: application/json');

// JSONでPOSTされた内容を取得
$data = json_decode(file_get_contents("php://input"), true);

// バリデーション（最低限）
if (!isset($data['name']) || !isset($data['email'])) {
    echo json_encode(['error' => 'nameとemailを指定してください']);
    exit;
}

$name = $data['name'];
$email = $data['email'];

$sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([':name' => $name, ':email' => $email]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => '登録に失敗しました']);
}
