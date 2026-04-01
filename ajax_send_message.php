<?php
session_start();
require_once 'class/Database.class.php';
$pdo = Database::getLink();

$sql = "INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id'], $_POST['to_id'], $_POST['text']]);
echo json_encode(['status' => 'success']);