<?php
class VueProfil extends VueBase {
    public function __construct() {
        global $trad;
        $title = $trad['side_profile'] ?? "Profile";
        parent::__construct($title . " - LinkUp");
        $this->actionActive = 'profile'; 
    }

    private function getUserData() {
        $pdo = Database::getLink();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=connexion");
            exit();
        }
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        return $stmt->fetch();
    }

    private function getUserEvents() {
        $pdo = Database::getLink();
        $myId = $_SESSION['user_id'];
        $sql = "SELECT * FROM events WHERE creator_id = :id ORDER BY start_date ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $myId]);
        return $stmt->fetchAll();
    }

    private function getStats() {
        $pdo = Database::getLink();
        $myId = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = :id");
        $stmt->execute([':id' => $myId]);
        $following = $stmt->fetchColumn();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE followed_id = :id");
        $stmt->execute([':id' => $myId]);
        $followers = $stmt->fetchColumn();
        return ['following' => $following, 'followers' => $followers];
    }

    private function getUserHashtags() {
        $pdo = Database::getLink();
        $stmt = $pdo->prepare("SELECT h.name FROM hashtags h JOIN user_hashtags uh ON h.id = uh.hashtag_id WHERE uh.user_id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getUserPosts() {
        $pdo = Database::getLink();
        $stmt = $pdo->prepare("SELECT p.*, pm.media_path FROM posts p LEFT JOIN post_media pm ON p.id = pm.post_id WHERE p.user_id = :id ORDER BY p.created_at DESC");
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
                        <button class="btn-edit-profile" onclick="openEditModal()"><?= $trad['btn_edit_profile'] ?? 'Edit Profile' ?></button>
                    </div>

                    <div class="profile-info-details">
                        <p class="profile-bio"><?= htmlspecialchars($user['bio'] ?? ($trad['profile_no_bio'] ?? 'No bio yet.')) ?></p>
                        <div class="profile-meta">
                            <span>📍 <?= htmlspecialchars($user['city'] ?? ($trad['profile_earth'] ?? 'Earth')) ?></span>
                            <span>📅 <?= $trad['profile_joined'] ?? 'Joined' ?> <?= date('F Y', strtotime($user['created_at'])) ?></span>
                        </div>

                        <div class="profile-languages" style="margin-top: 15px; display: flex; gap: 8px; flex-wrap: wrap;">
                            <?php 
                            if (!empty($user['languages'])): 
                                $langs = explode(',', $user['languages']);
                                foreach ($langs as $l): if(empty(trim($l))) continue; ?>
                                    <span class="lang-badge" style="background: #FFF0F0; color: #810F29; border: 1px solid #FFDADA; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <?= htmlspecialchars(trim($l)) ?>
                                    </span>
                                <?php endforeach; 
                            else: ?>
                                <span style="font-size: 12px; color: #888; font-style: italic;"><?= $trad['profile_no_languages'] ?? 'No languages specified' ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="profile-stats">
                            <div class="stat">
                                <span class="stat-number"><?= $stats['following'] ?></span>
                                <span class="stat-label"><?= $trad['top_following'] ?? 'Following' ?></span>
                            </div>
                            <div class="stat">
                                <span class="stat-number"><?= $stats['followers'] ?></span>
                                <span class="stat-label"><?= $trad['profile_stats_followers'] ?? 'Followers' ?></span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="profile-tabs">
                    <button class="tab-btn active" onclick="switchTab('posts')"><?= $trad['tab_posts'] ?? 'Posts' ?></button>
                    <button class="tab-btn" onclick="switchTab('events')"><?= $trad['tab_events'] ?? 'Events' ?></button>
                    <button class="tab-btn" onclick="switchTab('media')"><?= $trad['tab_media'] ?? 'Media' ?></button>
                </div>

                <div id="posts-section" class="tab-content active" style="padding: 28px;">
                    <?php if(empty($posts)): ?>
                        <p style="text-align:center; color:#666;"><?= $trad['profile_no_posts'] ?? 'No posts yet.' ?></p>
                    <?php else: foreach($posts as $p): ?>
                        <div class="post-card" id="post-<?= $p['id'] ?>">
                            <div class="post-header" style="display:flex; justify-content:space-between; align-items:start;">
                                <div style="display:flex; gap:12px;">
                                    <img class="post-avatar" src="<?= !empty($user['profile_pic']) ? $user['profile_pic'] : 'img/ppMan.png' ?>" alt="User">
                                    <div class="post-author-info">
                                        <div class="post-author"><?= htmlspecialchars($user['display_name']) ?></div>
                                        <div class="post-time"><?= date('d M Y', strtotime($p['created_at'])) ?></div>
                                    </div>
                                </div>
                                <button onclick="deletePost(<?= $p['id'] ?>)" style="background:none; border:none; cursor:pointer; color:#888;" title="<?= $trad['profile_delete_post_title'] ?? 'Delete post' ?>">
                                    <img src="img/icons/Delete.png" style="width:18px; opacity:0.6;">
                                </button>
                            </div>
                            <div class="post-content" style="margin-top:15px;">
                                <p><?= nl2br(htmlspecialchars($p['content'])) ?></p>
                            </div>
                            <?php if(!empty($p['media_path'])): ?>
                                <div class="post-image" style="margin-top:15px; border-radius:12px; overflow:hidden;">
                                    <img src="<?= $p['media_path'] ?>" alt="Post Media" style="width:100%; object-fit:cover;">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; endif; ?>
                </div>

                <div id="events-section" class="tab-content" style="padding: 28px; display:none;">
    <?php if(empty($events)): ?>
        <p style="text-align:center; color:#666;">
            <?= $trad['profile_no_events'] ?? 'Aucun événement créé pour le moment.' ?>
        </p>
    <?php else: ?>
        <div class="events-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php foreach($events as $e): ?>
                <div class="event-card" style="border: 1px solid #eee; border-radius: 12px; padding: 20px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <span class="event-badge" style="background: <?= $e['visibility'] === 'public' ? '#E7FBFB' : '#FFF0F0' ?>; color: <?= $e['visibility'] === 'public' ? '#008489' : '#810F29' ?>; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; text-transform: uppercase;">
                            <?= $e['visibility'] ?>
                        </span>
                        <span style="font-size: 12px; color: #888;">📅 <?= date('d/m/Y', strtotime($e['start_date'])) ?></span>
                    </div>
                    
                    <h3 style="margin: 15px 0 10px 0; color: #810F29; font-family: 'Jost', sans-serif;"><?= htmlspecialchars($e['title']) ?></h3>
                    <p style="font-size: 14px; color: #666; line-height: 1.5; margin-bottom: 15px;">
                        <?= nl2br(htmlspecialchars($e['description'])) ?>
                    </p>
                    
                    <div style="border-top: 1px solid #f5f5f5; pt: 15px; font-size: 13px; color: #444;">
                        <img src="img/icons/Calendar.png" style="width: 14px; vertical-align: middle; margin-right: 5px;">
                        <?= date('H:i', strtotime($e['start_date'])) ?> - <?= date('H:i', strtotime($e['end_date'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div id="media-section" class="tab-content" style="padding: 28px; display:none;">
    <div class="media-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
        <?php 
        $hasMedia = false;
        foreach($posts as $p): 
            if(!empty($p['media_path'])): 
                $hasMedia = true;
        ?>
            <div class="media-item" style="aspect-ratio: 1/1; overflow: hidden; border-radius: 12px; cursor: pointer; border: 1px solid #eee;">
                <img src="<?= $p['media_path'] ?>" 
                     alt="Media post" 
                     style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                     onclick="window.location.hash = 'post-<?= $p['id'] ?>'; switchTab('posts');"
                     onmouseover="this.style.transform='scale(1.05)'"
                     onmouseout="this.style.transform='scale(1)'">
            </div>
        <?php 
            endif; 
        endforeach; 

        if(!$hasMedia): ?>
            <p style="grid-column: 1 / -1; text-align: center; color: #666; padding: 40px;">
                <?= $trad['profile_no_media'] ?? 'Aucune photo publiée pour le moment.' ?>
            </p>
        <?php endif; ?>
    </div>
</div>
            </div>
        </div>

        <div id="edit-profile-modal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
            <div class="modal-content" style="background:white; padding:30px; border-radius:15px; width:90%; max-width:500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <h2 style="color:#810F29; margin-bottom:20px; font-family:'Jost', sans-serif;"><?= $trad['edit_title'] ?? 'Edit Profile' ?></h2>
                
                <form action="index.php?action=doUpdateProfile" method="POST" enctype="multipart/form-data">
                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;"><?= $trad['label_display_name'] ?? 'Display Name' ?></label>
                        <input type="text" name="display_name" value="<?= htmlspecialchars($user['display_name']) ?>" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;"><?= $trad['label_bio'] ?? 'Bio' ?></label>
                        <textarea name="bio" rows="3" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd; box-sizing: border-box;"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    </div>

                    <div style="margin-bottom:15px; position: relative;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;"><?= $trad['label_languages'] ?? 'Languages spoken' ?></label>
                        <input type="text" id="lang_input_edit" placeholder="<?= $trad['placeholder_add_lang'] ?? 'Add a language...' ?>" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd; box-sizing: border-box;">
                        <div id="lang_suggestions_edit" class="suggestions-list" style="display:none; position:absolute; width:100%; z-index:3000; background:white; border:1px solid #ddd; max-height:150px; overflow-y:auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                        
                        <div id="selected_langs_container_edit" style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px;"></div>
                        
                        <input type="hidden" name="languages" id="final_langs_input_edit" value="<?= htmlspecialchars($user['languages'] ?? '') ?>">
                    </div>

                    <div style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;"><?= $trad['label_profile_photo'] ?? 'Profile Picture' ?></label>
                        <input type="file" name="profile_pic" accept="image/*" style="font-size:12px;">
                    </div>

                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;"><?= $trad['label_banner_photo'] ?? 'Banner Image' ?></label>
                        <input type="file" name="banner_pic" accept="image/*" style="font-size:12px;">
                    </div>

                    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                        <button type="button" onclick="closeEditModal()" style="padding:10px 20px; border-radius:8px; border:1px solid #ddd; background:white; cursor:pointer; font-weight:600;"><?= $trad['btn_cancel'] ?? 'Cancel' ?></button>
                        <button type="submit" style="padding:10px 20px; background:#810F29; color:white; border-radius:8px; border:none; cursor:pointer; font-weight:600;"><?= $trad['btn_save_changes'] ?? 'Save Changes' ?></button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        // Variables de trad pour JS
        const TEXT_CONFIRM_DELETE = "<?= $trad['profile_confirm_delete'] ?? 'Voulez-vous vraiment supprimer ce post ?' ?>";

        // --- GESTION DES ONGLETS ---
        function switchTab(tabName) {
            const allSections = document.querySelectorAll('.tab-content');
            allSections.forEach(sec => {
                sec.style.setProperty('display', 'none', 'important');
                sec.classList.remove('active');
            });
            const allButtons = document.querySelectorAll('.tab-btn');
            allButtons.forEach(btn => btn.classList.remove('active'));
            const target = document.getElementById(tabName + '-section');
            if (target) {
                target.style.setProperty('display', 'block', 'important');
                target.classList.add('active');
            }
            if (window.event && window.event.currentTarget) {
                window.event.currentTarget.classList.add('active');
            }
        }

// ===== begin of the code zone generated by Gemini ===

        // --- GESTION DE LA MODALE ---
        function openEditModal() {
            document.getElementById('edit-profile-modal').style.display = 'flex';
            // Initialiser les badges au chargement
            const val = document.getElementById('final_langs_input_edit').value;
            editLanguages = val ? val.split(',').map(s => s.trim()).filter(s => s !== "") : [];
            updateEditLangDisplay();
        }
        function closeEditModal() {
            document.getElementById('edit-profile-modal').style.display = 'none';
        }

        // ===== end of the code zone generated by Gemini ===


        // --- GESTION DES LANGUES DANS LA MODALE ---
        const languagesData = { 
            "French": "Français", "English": "English", "Albanian": "Shqip", 
            "Vietnamese": "Tiếng Việt", "Spanish": "Español", "German": "Deutsch", 
            "Italian": "Italiano", "Portuguese": "Português" 
        };

        let editLanguages = [];

        function updateEditLangDisplay() {
            const container = document.getElementById("selected_langs_container_edit");
            const hiddenInput = document.getElementById("final_langs_input_edit");
            container.innerHTML = "";
            editLanguages.forEach(lang => {
                const badge = document.createElement("span");
                badge.style = "background: #810F29; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: flex; align-items: center; gap: 8px;";
                badge.innerHTML = `${lang} <span style="cursor:pointer; font-weight:bold; font-size:14px;">×</span>`;
                badge.querySelector("span").onclick = () => {
                    editLanguages = editLanguages.filter(l => l !== lang);
                    updateEditLangDisplay();
                };
                container.appendChild(badge);
            });
            hiddenInput.value = editLanguages.join(",");
        }

        // ===== begin of the code zone generated by Gemini ===

        document.getElementById('lang_input_edit').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const suggestions = document.getElementById('lang_suggestions_edit');
            suggestions.innerHTML = "";
            if (query.length < 1) { suggestions.style.display = "none"; return; }

            let found = false;
            for (const [eng, local] of Object.entries(languagesData)) {
                if (eng.toLowerCase().includes(query) || local.toLowerCase().includes(query)) {
                    found = true;
                    const div = document.createElement("div");
                    div.style = "padding:10px; cursor:pointer; border-bottom:1px solid #eee;";
                    div.innerText = local;
                    div.onmouseover = () => div.style.background = "#f8f8f8";
                    div.onmouseout = () => div.style.background = "transparent";
                    div.onclick = () => {
                        if(!editLanguages.includes(local)) {
                            editLanguages.push(local);
                            updateEditLangDisplay();
                        }
                        this.value = "";
                        suggestions.style.display = "none";
                    };
                    suggestions.appendChild(div);
                }
            }
            suggestions.style.display = found ? "block" : "none";
        });

// ===== end of the code zone generated by Gemini ===

        // Fermer les suggestions si on clique ailleurs
        document.addEventListener('click', (e) => {
            if (e.target.id !== 'lang_input_edit') {
                document.getElementById('lang_suggestions_edit').style.display = 'none';
            }
        });

        // --- SUPPRESSION DES POSTS (AJAX) ---
        function deletePost(postId) {
            if (confirm(TEXT_CONFIRM_DELETE)) {
                fetch('ajax_delete_post.php?id=' + postId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('post-' + postId).remove();
                    }
                })
                .catch(error => console.error('Erreur:', error));
            }
        }
        </script>
        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}