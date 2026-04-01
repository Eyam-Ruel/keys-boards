<?php
class VueProfil extends VueBase {
    public function __construct() {
        parent::__construct("Profile - LinkUp");
        $this->actionActive = 'profile'; 
    }

    public function afficher() {
        global $trad; 

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/profile.css">

        <div class="content">
            <div style="width: 100%; margin-top: 0px;">
                
                <div class="profile-header">
                    <div class="profile-banner">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=300&fit=crop" alt="Banner">
                    </div>
                    
                    <div class="profile-info-container">
                        <div class="profile-avatar-section">
                            <img class="profile-avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop" alt="Alex Johnson">
                            <div class="profile-name-title">
                                <h1 class="profile-name">Alex Johnson</h1>
                                <p class="profile-title">@alexj</p>
                            </div>
                        </div>
                        <button class="btn-edit-profile" onclick="openEditModal()">Edit Profile</button>
                    </div>

                    <div class="profile-info-details">
                        <p class="profile-bio">Passionate pianist and music producer. Love sharing music with all generations!</p>
                        <div class="profile-meta">
                            <span>📍 Paris, France</span>
                            <span>📅 Joined March 2024</span>
                            <span>🌐 English, French, Spanish</span>
                        </div>
                        <div class="profile-stats">
                            <div class="stat">
                                <span class="stat-number">183</span>
                                <span class="stat-label">Following</span>
                            </div>
                            <div class="stat">
                                <span class="stat-number">247</span>
                                <span class="stat-label">Followers</span>
                            </div>
                        </div>
                        <div class="profile-tags">
                            <span class="tag">#Piano</span>
                            <span class="tag">#Jazz</span>
                            <span class="tag">#Composition</span>
                            <span class="tag">#IMAO</span>
                        </div>
                    </div>
                </div>

                <div class="profile-tabs">
                    <button class="tab-btn active" onclick="switchTab('posts')">Posts</button>
                    <button class="tab-btn" onclick="switchTab('events')">Events</button>
                    <button class="tab-btn" onclick="switchTab('media')">Media</button>
                </div>

                <div id="posts-section" class="tab-content active" style="padding: 28px;">
                    <div class="post-card">
                        <div class="post-header">
                            <img class="post-avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop" alt="User">
                            <div class="post-author-info">
                                <div class="post-author">Alex Johnson</div>
                                <div class="post-time">5 days ago</div>
                            </div>
                        </div>
                        <div class="post-content">
                            <p>Just finished composing a new jazz piece! Would love to collaborate with a vocalist. Who's interested? 🎵</p>
                        </div>
                        <div class="post-image">
                            <img src="https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=600&h=400&fit=crop" alt="Music studio">
                        </div>
                    </div>
                    <div class="post-card">
                        <div class="post-header">
                            <img class="post-avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop" alt="User">
                            <div class="post-author-info">
                                <div class="post-author">Alex Johnson</div>
                                <div class="post-time">8 days ago</div>
                            </div>
                        </div>
                        <div class="post-content">
                            <p>Had an amazing session teaching piano to beginners today. Cross-generational learning is so rewarding! 🎹</p>
                        </div>
                        <div class="post-image">
                            <img src="https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=600&h=400&fit=crop" alt="Piano teaching">
                        </div>
                    </div>
                </div>

                <div id="events-section" class="tab-content" style="padding: 28px;">
                    <div class="event-card">
                        <div class="event-date-badge">
                            <div class="day">26</div>
                            <div class="month">Mar</div>
                        </div>
                        <div class="event-info">
                            <div class="event-name">Jazz Piano Session</div>
                            <div class="event-time">15:00 - 21:00</div>
                            <div class="event-desc">Live jazz piano session open to all</div>
                            <span class="event-tag public">Public</span>
                        </div>
                        <button class="btn-edit">Edit</button>
                    </div>
                </div>

                <div id="media-section" class="tab-content" style="padding: 28px;">
                    <div class="media-grid">
                        <div class="media-item"><img src="https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=300&h=300&fit=crop" alt="Music"></div>
                        <div class="media-item"><img src="https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=300&h=300&fit=crop" alt="Piano"></div>
                        <div class="media-item"><img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=300&h=300&fit=crop" alt="Guitar"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="edit-profile-modal" class="modal-overlay" style="display:none;">
          <div class="modal-container">
            <button class="modal-close-btn" onclick="closeEditModal()">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div class="modal-content-wrapper">
              <div class="edit-header">
                <h1 class="edit-title">Edit Profile</h1>
                <p class="edit-subtitle">Update your profile information and settings</p>
              </div>
              <form class="edit-form" id="edit-profile-form">
                <section class="form-section">
                  <h2 class="section-title">Appearance</h2>
                  <div class="form-group">
                    <label class="form-label">Banner Photo</label>
                    <div class="file-upload">
                      <label class="file-upload-btn">Browse...<input type="file" hidden id="banner-upload"></label>
                      <span class="file-info">No file selected</span>
                    </div>
                  </div>
                </section>
                <section class="form-section">
                  <h2 class="section-title">Basic Information</h2>
                  <div class="form-group">
                    <label class="form-label">Display Name</label>
                    <input type="text" class="form-input" value="Alex Johnson">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea class="form-textarea" rows="4">Passionate pianist and music producer.</textarea>
                  </div>
                </section>
                <section class="form-section">
                  <h2 class="section-title">Musical Details</h2>
                  <div class="tags-container" id="tags-container">
                    <span class="tag">#Piano <button type="button" class="tag-remove">×</button></span>
                  </div>
                </section>
                <div class="form-actions">
                  <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                  <button type="submit" class="auth-btn" style="background: #ff0000 !important; color: white !important; width: auto; padding: 10px 30px;">Save Changes</button>
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