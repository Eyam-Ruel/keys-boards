<?php
class VueFeed extends VueBase {
    public function __construct() {
        parent::__construct("Home - LinkUp");
        $this->actionActive = 'feed';
    }

    private function getPosts($filter = 'all') {
        $pdo = Database::getLink();
        $myId = $_SESSION['user_id'] ?? 0;
    
        if ($filter === 'following' && $myId > 0) {
            $sql = "SELECT p.*, u.display_name, u.pseudo, u.profile_pic, pm.media_path 
                    FROM posts p
                    JOIN users u ON p.user_id = u.id
                    JOIN follows f ON p.user_id = f.followed_id
                    LEFT JOIN post_media pm ON p.id = pm.post_id
                    WHERE f.follower_id = :myId
                    ORDER BY p.created_at DESC";
            $params = [':myId' => $myId];
        } else {
            $sql = "SELECT p.*, u.display_name, u.pseudo, u.profile_pic, pm.media_path 
                    FROM posts p
                    JOIN users u ON p.user_id = u.id
                    LEFT JOIN post_media pm ON p.id = pm.post_id
                    ORDER BY p.created_at DESC";
            $params = [];
        }
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function afficher() {
        global $trad; 
        $currentView = $_GET['view'] ?? 'all';
        $posts = $this->getPosts($currentView);

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/feed.css"> 
        
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

                <div class="feed--create-post">
                    <button id="openPostModal" class="create-post-btn"><?= $trad['btn_post'] ?? 'Publier' ?></button>
                </div>

                <div id="postModal" class="create-post-modal hide">
                    <div class="create-post-card">
                        <div class="create-post-header">
                            <h3><?= $trad['create_post_title'] ?? 'Créer une publication' ?></h3>
                            <button id="closePostModal" class="close-post-btn">&times;</button>
                        </div>
                        <form id="createPostForm" enctype="multipart/form-data">
                            <textarea name="content" id="postContent" placeholder="<?= $trad['pulse_new_post_placeholder'] ?? 'Quoi de neuf aujourd\'hui ?' ?>" required></textarea>
                            <input type="file" name="media" id="postMedia" accept="image/png,image/jpeg,image/jpg,image/gif" />
                            <div class="create-post-actions">
                                <button type="button" id="cancelPost" class="cancel-post-btn"><?= $trad['btn_cancel'] ?? 'Annuler' ?></button>
                                <button type="submit" class="create-post-submit"><?= $trad['btn_post'] ?? 'Publier' ?></button>
                            </div>
                        </form>
                        <div id="postMessage" class="post-message"></div>
                    </div>
                </div>

                <div class="posts--container" id="posts-container">
                    <?php if (empty($posts)): ?>
                        <p style="text-align:center; padding:50px; color:#666;">No posts yet in this category. 🎸</p>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="post">
                                <div class="post--content">
                                    <div class="post--header">
                                        <img src="<?= !empty($post['profile_pic']) ? $post['profile_pic'] : 'img/ppMan.png' ?>" alt="pp" class="post--header--img" />
                                        <div class="post--header--text">
                                            <span class="card--username Inter"><?= htmlspecialchars($post['display_name']) ?></span>
                                            <span class="card--userID">@<?= htmlspecialchars($post['pseudo']) ?> • <?= date('H:i', strtotime($post['created_at'])) ?></span>
                                            <div class="post--text Inter">
                                                <?= nl2br(htmlspecialchars($post['content'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($post['media_path'])): ?>
                                    <div class="post--media">
                                        <img src="<?= $post['media_path'] ?>" alt="postImg" class="post--img">
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="post--footer">
                                    <ul class="tag--list">
                                        <li class="post--tag Inter">#Music</li> 
                                        <li class="post--tag Inter">#LinkUp</li>
                                    </ul>
                                    <div class="post--actions">
                                        <div class="post--actions--left">
                                            <div class="post--comment"><img src="img/commentIcon.png" alt="comment" id="post--comment--icon">0</div>
                                            <div class="post--event"><img src="img/calendarIcon.png" alt="calendar" id="post--calendar--icon">Join event</div>
                                        </div>
                                        <div class="post--actions--right">
                                            <img src="img/dots.png" alt="dots" class="post--dots">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            </div>

        <script src="js/feed.js"></script>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}