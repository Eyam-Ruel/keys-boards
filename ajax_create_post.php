<?php
// On essaie de pousser les limites du serveur si possible (pour accepter des images plus lourdes)
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

session_start();
// Inclusion de la connexion à la base de données
require_once 'class/Database.class.php';

// On indique que la page renvoie du JSON pour faciliter le traitement côté JavaScript
header('Content-Type: application/json; charset=UTF-8');

// 1. Sécurité : Utilisateur connecté (on bloque directement si pas de session)
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Session expirée.']);
    exit;
}

// 2. Validation texte (on empêche de publier un post totalement vide)
$content = trim($_POST['content'] ?? '');
if ($content === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Le texte est vide.']);
    exit;
}

try {
    $pdo = Database::getLink();
    // Début de la transaction SQL : si l'upload d'image plante, le post ne sera pas enregistré non plus
    $pdo->beginTransaction();

    // 3. Création du post dans la table principale
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (:uid, :content, NOW())");
    $stmt->execute([':uid' => $_SESSION['user_id'], ':content' => $content]);
    $postId = $pdo->lastInsertId();

    // 4. Traitement de l'image (si l'utilisateur a bien envoyé un fichier)
    if (isset($_FILES['media']) && $_FILES['media']['error'] !== UPLOAD_ERR_NO_FILE) {
        
        // Gestion spécifique de l'erreur de poids (Code 1 ou 2 renvoyé par PHP)
        if ($_FILES['media']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['media']['error'] === UPLOAD_ERR_FORM_SIZE) {
            throw new Exception("L'image est trop lourde pour le serveur (Max 2Mo env).");
        }
        
        // Capture des autres types d'erreurs d'upload potentielles
        if ($_FILES['media']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors du transfert de l'image.");
        }

        $file = $_FILES['media'];
        // Liste blanche des extensions autorisées pour plus de sécurité
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExt)) {
            throw new Exception("Format d'image non supporté.");
        }

        // Dossier de destination pour stocker physiquement l'image sur le serveur
        $uploadDir = __DIR__ . '/img/posts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Création d'un nom de fichier unique pour éviter d'écraser des images existantes
        $filename = 'post_' . $postId . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        // Déplacement du fichier temporaire vers son dossier final
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $mediaPath = 'img/posts/' . $filename;
            
            // Enregistrement du chemin de l'image dans la base de données
            $stmtMedia = $pdo->prepare("INSERT INTO post_media (post_id, media_path, media_type) VALUES (:id, :path, 'image')");
            $stmtMedia->execute([':id' => $postId, ':path' => $mediaPath]);
        } else {
            throw new Exception("Erreur de stockage sur le serveur.");
        }
    }

    // Si tout s'est bien passé (post + image), on valide la transaction et on sauvegarde
    $pdo->commit();
    echo json_encode(['success' => true, 'post_id' => $postId]);

} catch (Exception $e) {
    // En cas d'erreur (format invalide, image trop lourde...), on annule toute l'opération SQL
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit;