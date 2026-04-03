<?php
session_start();
// Inclusion de la base de données
require_once 'class/Database.class.php';
$pdo = Database::getLink();
$myId = $_SESSION['user_id'] ?? 0;
$view = $_GET['view'] ?? 'all';

// Construction de la requête principale pour récupérer les posts et les stats (likes, commentaires)
$sql = "SELECT p.*, u.display_name, u.pseudo, u.profile_pic, pm.media_path,
        (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as likes_count,
        (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id AND user_id = :myId) as is_liked,
        (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comments_count
        FROM posts p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN post_media pm ON p.id = pm.post_id";

// Si l'utilisateur veut voir uniquement les posts des personnes qu'il suit
if ($view === 'following' && $myId > 0) {
    $sql .= " INNER JOIN follows f ON p.user_id = f.followed_id WHERE f.follower_id = :myId";
}

$sql .= " GROUP BY p.id ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':myId' => $myId]);
$posts = $stmt->fetchAll();

// Affichage si aucun post n'est trouvé
if (empty($posts)) {
    echo '<p style="text-align:center; padding:50px; color:#666;">No posts yet in this category. 🎸</p>';
} else {
    // Boucle sur chaque post pour l'afficher
    foreach ($posts as $post) { 
        // On récupère uniquement les 3 derniers commentaires pour un affichage compact
        $stmtCom = $pdo->prepare("SELECT c.*, u.display_name 
                                 FROM comments c 
                                 JOIN users u ON c.user_id = u.id 
                                 WHERE c.post_id = ? 
                                 ORDER BY c.created_at DESC LIMIT 3");
        $stmtCom->execute([$post['id']]);
        $comments = array_reverse($stmtCom->fetchAll()); // On les remet dans l'ordre chronologique
        ?>

        <div class="post" id="post-<?= $post['id'] ?>">
            <div class="post--content">
                <div class="post--header">
                    <img src="<?= !empty($post['profile_pic']) ? $post['profile_pic'] : 'img/ppMan.png' ?>" class="post--header--img" />
                    <div class="post--header--text">
                        <span class="card--username"><?= htmlspecialchars($post['display_name']) ?></span>
                        <span class="card--userID">@<?= htmlspecialchars($post['pseudo']) ?></span>
                        <div class="post--text"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
                    </div>
                </div>
                <?php if ($post['media_path']): ?>
                    <div class="post--media"><img src="<?= $post['media_path'] ?>" class="post--img"></div>
                <?php endif; ?>
            </div>

            <div class="post--footer">
                <div class="post--actions">
                    <div class="post--actions--left" style="display:flex; gap:20px; align-items:center;">
                        
                        <div class="post--like" onclick="toggleLike(<?= $post['id'] ?>, this)" style="cursor:pointer; display:flex; align-items:center; gap:5px;">
                            <img src="img/icons/<?= $post['is_liked'] ? 'Heart Full.png' : 'Heart Hollow.png' ?>" alt="like" style="width:20px;">
                            <span><?= $post['likes_count'] ?></span>
                        </div>

                        <div class="post--comment" style="display:flex; align-items:center; gap:5px;">
                            <img src="img/icons/Comments.png" style="width:20px;">
                            <span id="comment-count-<?= $post['id'] ?>"><?= $post['comments_count'] ?></span>
                        </div>
                    </div>
                </div>

                <div id="comment-box-<?= $post['id'] ?>" class="comment-section" style="margin-top:15px; border-top:1px solid #eee; padding-top:10px;">
                    
                    <div style="display:flex; gap:10px; margin-bottom:15px;">
                        <input type="text" id="input-comment-<?= $post['id'] ?>" placeholder="Write a comment..." class="comment-input" style="flex:1; border-radius:20px; border:1px solid #ddd; padding:5px 15px; outline:none;">
                        <button onclick="sendComment(<?= $post['id'] ?>)" class="red--button" style="border-radius:20px; padding:5px 15px;">Send</button>
                    </div>

                    <div id="comments-area-<?= $post['id'] ?>">
                        <div id="comments-list-<?= $post['id'] ?>">
                            <?php foreach ($comments as $com): ?>
                                <div class="comment-item" style="font-size: 13px; margin-bottom: 5px;">
                                    <strong style="color: #810F29;"><?= htmlspecialchars($com['display_name']) ?> :</strong> 
                                    <?= htmlspecialchars($com['content']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($post['comments_count'] > 3): ?>
                            <button onclick="loadAllComments(<?= $post['id'] ?>, this)" 
                                    style="background:none; border:none; color:#888; font-size:12px; cursor:pointer; padding:0; margin-top:5px; font-style: italic;">
                                Show all <?= $post['comments_count'] ?> comments...
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }
} 
?>