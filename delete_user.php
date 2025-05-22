<?php
require 'db_config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(['error' => 'idが必要です']);
    exit;
}

$id = (int)$data['id'];

$sql = "DELETE FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([':id' => $id]);

if ($result && $stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => '削除できませんでした']);
}
