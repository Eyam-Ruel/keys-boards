<?php
session_start();
require_once 'class/Database.class.php';

header('Content-Type: application/json; charset=UTF-8');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté.']);
    exit;
}

$content = trim($_POST['content'] ?? '');
if ($content === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Le contenu du post est requis.']);
    exit;
}

try {
    $pdo = Database::getLink();
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (:uid, :content, NOW())");
    $stmt->execute([
        ':uid' => $_SESSION['user_id'],
        ':content' => $content
    ]);

    $postId = $pdo->lastInsertId();

    if (!empty($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['media'];
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($extension, $allowedExt)) {
            $uploadDir = __DIR__ . '/img/posts';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filename = 'post_' . $postId . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . '/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $mediaPath = 'img/posts/' . $filename;
                $stmtMedia = $pdo->prepare("INSERT INTO post_media (post_id, media_path, media_type) VALUES (:post_id, :path, :type)");
                $stmtMedia->execute([
                    ':post_id' => $postId,
                    ':path' => $mediaPath,
                    ':type' => in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : 'image'
                ]);
            }
        }
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'post_id' => $postId]);
    exit;
} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Une erreur est survenue lors de la création du post.']);
    exit;
}
