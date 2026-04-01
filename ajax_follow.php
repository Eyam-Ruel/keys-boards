<?php
session_start();
require_once 'class/Database.class.php';

if (isset($_SESSION['user_id']) && isset($_POST['followed_id'])) {
    $pdo = Database::getLink();
    $followerId = $_SESSION['user_id'];
    $followedId = $_POST['followed_id'];

    // On vérifie si tu le suis déjà
    $check = $pdo->prepare("SELECT * FROM follows WHERE follower_id = ? AND followed_id = ?");
    $check->execute([$followerId, $followedId]);

    if ($check->fetch()) {
        // Déjà suivi -> On supprime (Unfollow)
        $stmt = $pdo->prepare("DELETE FROM follows WHERE follower_id = ? AND followed_id = ?");
        $stmt->execute([$followerId, $followedId]);
        echo json_encode(['status' => 'removed']);
    } else {
        // Pas encore suivi -> On insère
        $stmt = $pdo->prepare("INSERT INTO follows (follower_id, followed_id, status) VALUES (?, ?, 'followed')");
        $stmt->execute([$followerId, $followedId]);
        echo json_encode(['status' => 'followed']);
    }
}