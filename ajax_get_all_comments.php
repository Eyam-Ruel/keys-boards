<?php
// Inclusion du fichier de connexion à la base de données
require_once 'class/Database.class.php';
$pdo = Database::getLink();

// Vérifie qu'un identifiant de post a bien été transmis dans l'URL
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];

    // On récupère TOUS les commentaires liés à ce post (triés du plus ancien au plus récent)
    $stmt = $pdo->prepare("SELECT c.*, u.display_name 
                           FROM comments c 
                           JOIN users u ON c.user_id = u.id 
                           WHERE c.post_id = ? 
                           ORDER BY c.created_at ASC");
    $stmt->execute([$postId]);
    $comments = $stmt->fetchAll();

    // Boucle pour générer et afficher l'HTML de chaque commentaire
    foreach ($comments as $com) {
        ?>
        <div class="comment-item" style="font-size: 13px; margin-bottom: 5px;">
            <strong style="color: #810F29;"><?= htmlspecialchars($com['display_name']) ?> :</strong> 
            <?= htmlspecialchars($com['content']) ?>
        </div>
        <?php
    }
}