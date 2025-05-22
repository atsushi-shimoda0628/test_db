<?php
require 'db_config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'], $data['name'], $data['email'])) {
    echo json_encode(['success' => false, 'error' => '必要なデータがありません']);
    exit;
}

$id = $data['id'];
$name = $data['name'];
$email = $data['email'];

$stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
$success = $stmt->execute([$name, $email, $id]);

echo json_encode(['success' => $success]);
    