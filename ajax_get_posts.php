<?php
session_start();
require_once 'class/Database.class.php';
$pdo = Database::getLink();
$myId = $_SESSION['user_id'] ?? 0;
$view = $_GET['view'] ?? 'all';

if ($view === 'following' && $myId > 0) {
    $sql = "SELECT p.*, u.display_name, u.pseudo, u.profile_pic, pm.media_path 
            FROM posts p
            JOIN users u ON p.user_id = u.id
            JOIN follows f ON p.user_id = f.followed_id
            LEFT JOIN post_media pm ON p.id = pm.post_id
            WHERE f.follower_id = ?
            ORDER BY p.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$myId]);
} else {
    $sql = "SELECT p.*, u.display_name, u.pseudo, u.profile_pic, pm.media_path 
            FROM posts p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN post_media pm ON p.id = pm.post_id
            ORDER BY p.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$posts = $stmt->fetchAll();

// On génère le HTML des posts uniquement
if (empty($posts)) {
    echo '<p style="text-align:center; padding:50px;">No posts found. 🎸</p>';
} else {
    foreach ($posts as $post) {
        ?>
        <div class="post">
            <div class="post--content">
                <div class="post--header">
                    <img src="<?= $post['profile_pic'] ?: 'img/ppMan.png' ?>" class="post--header--img" />
                    <div class="post--header--text">
                        <span class="card--username"><?= htmlspecialchars($post['display_name']) ?></span>
                        <span class="card--userID">@<?= htmlspecialchars($post['pseudo']) ?></span>
                        <div class="post--text"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
                    </div>
                </div>
                <?php if ($post['media_path']): ?>
                    <img src="<?= $post['media_path'] ?>" class="post--img">
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}