<?php
class VueProfil extends VueBase {
    public function __construct() {
        parent::__construct("Profile - LinkUp");
        $this->actionActive = 'profile'; 
    }

    private function getUserData() {
        $pdo = Database::getLink();
        
        // Sécurité : Si l'utilisateur n'est pas connecté, on le redirige
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=connexion");
            exit();
        }
    
        // ON FILTRE PAR L'ID DE LA SESSION
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        return $stmt->fetch();
    }

    private function getStats() {
        $pdo = Database::getLink();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $following = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE followed_id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $followers = $stmt->fetchColumn();

        return ['following' => $following, 'followers' => $followers];
    }

    private function getUserHashtags() {
        $pdo = Database::getLink();
        $stmt = $pdo->prepare("SELECT h.name FROM hashtags h 
                               JOIN user_hashtags uh ON h.id = uh.hashtag_id 
                               WHERE uh.user_id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getUserPosts() {
        $pdo = Database::getLink();
        $stmt = $pdo->prepare("SELECT p.*, pm.media_path FROM posts p 
                               LEFT JOIN post_media pm ON p.id = pm.post_id 
                               WHERE p.user_id = :id ORDER BY p.created_at DESC");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        return $stmt->fetchAll();
    }

    private function getUserEvents() {
        $pdo = Database::getLink();
        $stmt = $pdo->prepare("SELECT * FROM events WHERE creator_id = :id ORDER BY start_date ASC");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        return $stmt->fetchAll();
    }

    public function afficher() {
        global $trad; 
        $user = $this->getUserData();
        $stats = $this->getStats();
        $tags = $this->getUserHashtags();
        $posts = $this->getUserPosts();
        $events = $this->getUserEvents();

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/profile.css">

        <div class="content">
            <div style="width: 100%; margin-top: 0px;">
                
                <div class="profile-header">
                    <div class="profile-banner">
                        <img src="<?= !empty($user['banner_pic']) ? $user['banner_pic'].'?t='.time() : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=1200' ?>" alt="Banner">
                    </div>
                    
                    <div class="profile-info-container">
                        <div class="profile-avatar-section">
                            <img class="profile-avatar" src="<?= !empty($user['profile_pic']) ? $user['profile_pic'].'?t='.time() : 'img/ppMan.png' ?>" alt="<?= htmlspecialchars($user['display_name']) ?>">
                            <div class="profile-name-title">
                                <h1 class="profile-name"><?= htmlspecialchars($user['display_name']) ?></h1>
                                <p class="profile-title">@<?= htmlspecialchars($user['pseudo']) ?></p>
                            </div>
                        </div>
                        <button class="btn-edit-profile" onclick="openEditModal()">Edit Profile</button>
                    </div>

                    <div class="profile-info-details">
                        <p class="profile-bio"><?= htmlspecialchars($user['bio'] ?? 'No bio yet.') ?></p>
                        <div class="profile-meta">
                            <span>📍 <?= htmlspecialchars($user['city'] ?? 'Earth') ?></span>
                            <span>📅 Joined <?= date('F Y', strtotime($user['created_at'])) ?></span>
                        </div>
                        <div class="profile-stats">
                            <div class="stat">
                                <span class="stat-number"><?= $stats['following'] ?></span>
                                <span class="stat-label">Following</span>
                            </div>
                            <div class="stat">
                                <span class="stat-number"><?= $stats['followers'] ?></span>
                                <span class="stat-label">Followers</span>
                            </div>
                        </div>
                        <div class="profile-tags" style="margin-top: 15px;">
                            <?php foreach($tags as $tag): ?>
                                <span class="tag">#<?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="profile-tabs">
                    <button class="tab-btn active" onclick="switchTab('posts')">Posts</button>
                    <button class="tab-btn" onclick="switchTab('events')">Events</button>
                    <button class="tab-btn" onclick="switchTab('media')">Media</button>
                </div>

                <div id="posts-section" class="tab-content active" style="padding: 28px;">
                    <?php if(empty($posts)): ?>
                        <p style="text-align:center; color:#666;">No posts yet.</p>
                    <?php else: foreach($posts as $p): ?>
                        <div class="post-card">
                            <div class="post-header">
                                <img class="post-avatar" src="<?= !empty($user['profile_pic']) ? $user['profile_pic'] : 'img/ppMan.png' ?>" alt="User">
                                <div class="post-author-info">
                                    <div class="post-author"><?= htmlspecialchars($user['display_name']) ?></div>
                                    <div class="post-time"><?= date('d M Y', strtotime($p['created_at'])) ?></div>
                                </div>
                            </div>
                            <div class="post-content">
                                <p><?= nl2br(htmlspecialchars($p['content'])) ?></p>
                            </div>
                            <?php if(!empty($p['media_path'])): ?>
                                <div class="post-image">
                                    <img src="<?= $p['media_path'] ?>" alt="Post Media">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; endif; ?>
                </div>

                <div id="events-section" class="tab-content" style="padding: 28px;">
                    </div>

                <div id="media-section" class="tab-content" style="padding: 28px;">
                    </div>
            </div>
        </div>

        <div id="edit-profile-modal" class="modal-overlay" style="display:none;">
          <div class="modal-container">
            <div class="modal-content-wrapper">
              <div class="edit-header">
                <h1 class="edit-title">Edit Profile</h1>
                <p class="edit-subtitle">Update your profile information</p>
              </div>
              <form class="edit-form" id="edit-profile-form" action="index.php?action=updateProfile" method="POST" enctype="multipart/form-data">
                
                <section class="form-section">
                  <h2 class="section-title">Appearance</h2>
                  <div class="form-group">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-input">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Banner Photo</label>
                    <input type="file" name="banner_pic" class="form-input">
                  </div>
                </section>

                <section class="form-section">
                  <h2 class="section-title">Basic Information</h2>
                  <div class="form-group">
                    <label class="form-label">Display Name</label>
                    <input type="text" name="display_name" class="form-input" value="<?= htmlspecialchars($user['display_name']) ?>">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-textarea" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>
                  </div>
                  <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-input" value="<?= htmlspecialchars($user['city']) ?>">
                  </div>
                </section>

                <div class="form-actions">
                  <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                  <button type="submit" class="auth-btn" style="background: #810F29 !important; color: white !important; width: auto; padding: 10px 30px;">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <script src="js/profil.js"></script>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}