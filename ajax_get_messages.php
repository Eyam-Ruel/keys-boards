<?php
session_start();
require_once 'class/Database.class.php';
$pdo = Database::getLink();
$monId = $_SESSION['user_id'];
$contactId = $_GET['contact_id'];

$sql = "SELECT * FROM messages 
        WHERE (sender_id = :m AND receiver_id = :c) 
        OR (sender_id = :c AND receiver_id = :m) 
        ORDER BY created_at ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':m' => $monId, ':c' => $contactId]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));