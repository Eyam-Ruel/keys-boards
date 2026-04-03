<?php
session_start();
// Inclusion de la connexion à la base de données
require_once 'class/Database.class.php';
// Indique que la page va renvoyer du JSON (idéal pour les requêtes AJAX/JS)
header('Content-Type: application/json');

// Vérification de sécurité : on stoppe tout si l'utilisateur n'est pas connecté ou si l'ID manque
if (empty($_SESSION['user_id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$pdo = Database::getLink();
$postId = (int)$_GET['id'];
$userId = $_SESSION['user_id'];

// On vérifie que le post appartient bien à l'utilisateur avant d'autoriser la suppression
$stmt = $pdo->prepare("SELECT pm.media_path FROM posts p LEFT JOIN post_media pm ON p.id = pm.post_id WHERE p.id = ? AND p.user_id = ?");
$stmt->execute([$postId, $userId]);
$post = $stmt->fetch();

if ($post) {
    // 1. On supprime physiquement le fichier image du serveur s'il y en a un
    if (!empty($post['media_path']) && file_exists($post['media_path'])) {
        unlink($post['media_path']);
    }
    
    // 2. On supprime le post de la base de données 
    // (Note : la suppression en cascade s'occupe des likes/médias si c'est configuré dans ton SQL)
    $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$postId]);
    
    // Retourne un succès pour que ton JavaScript puisse cacher le post à l'écran
    echo json_encode(['success' => true]);
} else {
    // Erreur si le post n'existe pas ou s'il appartient à quelqu'un d'autre
    echo json_encode(['success' => false, 'error' => 'Post not found']);
}