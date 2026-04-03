<?php
class VueFeed extends VueBase {
    public function __construct() {
        parent::__construct("Home - LinkUp");
        $this->actionActive = 'feed';
    }

    private function getPosts($filter = 'all') {
        $pdo = Database::getLink();
        $myId = $_SESSION['user_id'] ?? 0;
    
        $sql = "SELECT p.*, u.display_name, u.pseudo, u.profile_pic, MAX(pm.media_path) as media_path,
                (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as likes_count,
                (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id AND user_id = :myId) as is_liked,
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comments_count
                FROM posts p
                JOIN users u ON p.user_id = u.id
                LEFT JOIN post_media pm ON p.id = pm.post_id";
    
        if ($filter === 'following' && $myId > 0) {
            $sql .= " INNER JOIN follows f ON p.user_id = f.followed_id WHERE f.follower_id = :myId";
        }
    
        $sql .= " GROUP BY p.id ORDER BY p.created_at DESC";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':myId' => $myId]);
        return $stmt->fetchAll();
    }

    public function afficher() {
        global $trad; 
        $pdo = Database::getLink();
        $currentView = $_GET['view'] ?? 'all';
        $posts = $this->getPosts($currentView);

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/feed.css"> 
        
        <div class="page-feed">
            <div class="explore--content">
                <div class="feed">
                    
                    <div class="feed--nav">
                        <input type="button" value="For you" id="btn-all" 
                               class="feed--nav--button <?= ($currentView === 'all') ? 'active-tab' : '' ?>" 
                               onclick="switchFeed('all')">
                               
                        <input type="button" value="Following" id="id-btn-following" 
                               class="feed--nav--button <?= ($currentView === 'following') ? 'active-tab' : '' ?>" 
                               onclick="switchFeed('following')">
                    </div>
                    
                    <div class="posts--container" id="posts-container">
                        <?php if (empty($posts)): ?>
                            <p style="text-align:center; padding:50px; color:#666;">No posts yet in this category. 🎸</p>
                        <?php else: ?>
                            <?php foreach ($posts as $post): 
                                $stmtCom = $pdo->prepare("SELECT c.*, u.display_name 
                                                         FROM comments c 
                                                         JOIN users u ON c.user_id = u.id 
                                                         WHERE c.post_id = ? 
                                                         ORDER BY c.created_at DESC LIMIT 3");
                                $stmtCom->execute([$post['id']]);
                                $comments = array_reverse($stmtCom->fetchAll());
                            ?>
                                <div class="post" id="post-<?= $post['id'] ?>">
                                    <div class="post--header">
                                        <img src="<?= !empty($post['profile_pic']) ? $post['profile_pic'] : 'img/ppMan.png' ?>" class="post--header--img" />
                                        <div class="post--header--text">
                                            <span class="card--username Inter"><?= htmlspecialchars($post['display_name']) ?></span>
                                            <span class="card--userID">@<?= htmlspecialchars($post['pseudo']) ?> • <?= date('H:i', strtotime($post['created_at'])) ?></span>
                                        </div>
                                    </div>

                                    <div class="post--text Inter">
                                        <?= nl2br(htmlspecialchars($post['content'])) ?>
                                    </div>
                                    
                                    <?php if (!empty($post['media_path'])): ?>
                                    <div class="post--media">
                                        <img src="<?= $post['media_path'] ?>" class="post--img">
                                    </div>
                                    <?php endif; ?>

                                    <div class="post--footer">
                                        <div class="post--actions">
                                            <div class="post--actions--left" style="display:flex; gap:20px; align-items:center; margin-bottom:10px;">
                                                <div class="post--like" onclick="toggleLike(<?= $post['id'] ?>, this)" style="cursor:pointer; display:flex; align-items:center; gap:5px;">
                                                    <img src="img/icons/<?= $post['is_liked'] ? 'Heart Full.png' : 'Heart Hollow.png' ?>" style="width:20px;">
                                                    <span class="Inter"><?= $post['likes_count'] ?></span>
                                                </div>

                                                <div class="post--comment" style="display:flex; align-items:center; gap:5px;">
                                                    <img src="img/icons/Comments.png" style="width:20px;">
                                                    <span id="comment-count-<?= $post['id'] ?>"><?= $post['comments_count'] ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="comment-box-<?= $post['id'] ?>" class="comment-section" style="margin-top:10px; border-top:1px solid #eee; padding-top:10px;">
                                            <div style="display:flex; gap:10px; margin-bottom:10px;">
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
                                                    <button onclick="loadAllComments(<?= $post['id'] ?>, this)" style="background:none; border:none; color:#888; font-size:12px; cursor:pointer; padding:0; margin-top:5px; font-style:italic;">
                                                        Show all <?= $post['comments_count'] ?> comments...
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div> </div> <?php endforeach; ?>
                        <?php endif; ?>
                    </div> </div> </div> </div> <script src="js/feed.js"></script>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}