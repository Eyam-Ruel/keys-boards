<?php
class VueProfil extends VueBase {
    public function __construct() {
        parent::__construct("My Profile - LinkUp");
    }

    public function afficher() {
        // Read current user data from session (set in User::connecter)
        $displayName = $_SESSION['display_name'] ?? 'Anonymous';
        $pseudo      = $_SESSION['pseudo']       ?? '@user';
        $bio         = $_SESSION['bio']          ?? '';
        $city        = $_SESSION['city']         ?? '';
        $country     = $_SESSION['country']      ?? '';
        $lat         = $_SESSION['lat']          ?? '';
        $lng         = $_SESSION['lng']          ?? '';
        $location    = trim($city . ($city && $country ? ', ' : '') . $country);

        // Handle profile update POST
        $successMsg = isset($_GET['saved']) ? '<p class="save-success">✅ Profile saved!</p>' : '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['display_name'])) {
            $pdo = Database::getLink();
            $stmt = $pdo->prepare("
                UPDATE users
                SET display_name = :display_name,
                    pseudo       = :pseudo,
                    bio          = :bio,
                    city         = :city,
                    lat          = :lat,
                    lng          = :lng
                WHERE id = :id
            ");
            $stmt->execute([
                ':display_name' => htmlspecialchars($_POST['display_name']),
                ':pseudo'       => htmlspecialchars($_POST['pseudo']),
                ':bio'          => htmlspecialchars($_POST['bio']),
                ':city'         => htmlspecialchars($_POST['city']),
                ':lat'          => !empty($_POST['lat']) ? (float)$_POST['lat'] : null,
                ':lng'          => !empty($_POST['lng']) ? (float)$_POST['lng'] : null,
                ':id'           => $_SESSION['user_id'],
            ]);

            // Refresh session with updated values
            $_SESSION['display_name'] = htmlspecialchars($_POST['display_name']);
            $_SESSION['pseudo']       = htmlspecialchars($_POST['pseudo']);
            $_SESSION['bio']          = htmlspecialchars($_POST['bio']);
            $_SESSION['city']         = htmlspecialchars($_POST['city']);
            $_SESSION['lat']          = !empty($_POST['lat']) ? (float)$_POST['lat'] : null;
            $_SESSION['lng']          = !empty($_POST['lng']) ? (float)$_POST['lng'] : null;

            // Reload updated values for display
            $displayName = $_SESSION['display_name'];
            $pseudo      = $_SESSION['pseudo'];
            $bio         = $_SESSION['bio'];
            $city        = $_SESSION['city'];
            $lat         = $_SESSION['lat'];
            $lng         = $_SESSION['lng'];
            $location    = $city;
            $successMsg  = '<p class="save-success">✅ Profile saved!</p>';
            header("Location: index.php?action=profil&saved=1");
            exit();
      }

        $this->contenu = '
        <link rel="stylesheet" href="Profil/profile.css" />

        <main id="content-area">

            ' . $successMsg . '

            <!-- COVER -->
            <div class="cover">
                <svg viewBox="0 0 400 170" xmlns="http://www.w3.org/2000/svg">
                    <rect width="400" height="170" fill="#1a1a1a"/>
                    <rect x="0" y="0" width="400" height="120" fill="#2a2520"/>
                    <rect x="0" y="120" width="400" height="50" fill="#111"/>
                    <rect x="50" y="87" width="220" height="8" fill="#5a4030" rx="2"/>
                    <rect x="55" y="70" width="210" height="22" fill="#1a1a1a" rx="3"/>
                </svg>
                <svg viewBox="0 0 400 170" xmlns="http://www.w3.org/2000/svg">
                    <rect width="400" height="170" fill="#e8e0d0"/>
                </svg>
            </div>

            <!-- PROFILE SECTION -->
            <div class="profile-section">
                <div class="avatar-wrap">
                    <svg width="72" height="72" viewBox="0 0 72 72" style="border-radius:50%;border:3px solid white;flex-shrink:0;">
                        <rect width="72" height="72" fill="#6c63ff" rx="36"/>
                        <text x="36" y="44" text-anchor="middle" fill="white" font-size="28" font-family="DM Sans">'
                            . strtoupper(mb_substr($displayName, 0, 1)) . '
                        </text>
                    </svg>
                    <div class="profile-name-row">
                        <div>
                            <div class="profile-name" id="profileName">' . htmlspecialchars($displayName) . '</div>
                            <div class="profile-handle" id="profileHandle">' . htmlspecialchars($pseudo) . '</div>
                        </div>
                        <button class="btn-edit" onclick="openEditModal()">Edit Profile</button>
                    </div>
                </div>

                <p class="profile-bio" id="profileBio">' . htmlspecialchars($bio) . '</p>

                <div class="profile-meta">
                    <span id="metaLocation">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        ' . htmlspecialchars($location ?: 'No location set') . '
                    </span>
                </div>

                <div class="profile-stats">
                    <div><strong>0</strong> <span>Following</span></div>
                    <div><strong>0</strong> <span>Followers</span></div>
                </div>
            </div>

            <!-- TABS -->
            <div class="tabs">
                <div class="tab active" onclick="switchTab(\'posts\', this)">Posts</div>
                <div class="tab" onclick="switchTab(\'events\', this)">Events</div>
                <div class="tab" onclick="switchTab(\'media\', this)">Media</div>
            </div>

            <div class="feed">
                <div class="tab-content active" id="tab-posts"></div>
                <div class="tab-content" id="tab-events"></div>
                <div class="tab-content" id="tab-media"></div>
            </div>

        </main>

        <!-- EDIT PROFILE MODAL -->
        <div class="modal-overlay" id="editModal">
            <div class="modal">
                <h3>Edit Profile</h3>

                <!-- POST to same page — fields match DB columns -->
                <form method="POST" action="index.php?action=profil" id="edit-profile-form">

                    <div class="form-group">
                        <label for="edit-name">Display Name</label>
                        <input type="text" id="edit-name" name="display_name"
                               value="' . htmlspecialchars($displayName) . '" />
                    </div>

                    <div class="form-group">
                        <label for="edit-handle">Handle</label>
                        <input type="text" id="edit-handle" name="pseudo"
                               value="' . htmlspecialchars($pseudo) . '" />
                    </div>

                    <div class="form-group">
                        <label for="edit-bio">Bio</label>
                        <textarea id="edit-bio" name="bio">' . htmlspecialchars($bio) . '</textarea>
                    </div>

                    <!-- Location with Nominatim autocomplete (profil.js) -->
                    <div class="form-group address-group">
                        <label for="address-search">Location</label>
                        <div class="address-wrapper">
                            <input
                                type="text"
                                id="address-search"
                                name="city"
                                placeholder="Search your city or address..."
                                autocomplete="off"
                                value="' . htmlspecialchars($location) . '"
                            />
                            <ul id="suggestions" class="suggestions-list"></ul>
                        </div>
                    </div>

                    <!-- Hidden: lat/lng captured by Nominatim, saved to DB -->
                    <input type="hidden" name="lat" id="lat" value="' . htmlspecialchars($lat) . '" />
                    <input type="hidden" name="lng" id="lng" value="' . htmlspecialchars($lng) . '" />

                    <div class="modal-actions">
                        <button type="button" class="btn-cancel-m" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="btn-save-m">Save</button>
                    </div>

                </form>
            </div>
        </div>

        <script src="Profil/profil.js"></script>

        <style>
        .save-success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 10px 16px;
            border-radius: 8px;
            margin: 12px 0;
            font-size: 0.9rem;
        }
        .address-wrapper { position: relative; width: 100%; }
        .suggestions-list {
            display: none;
            position: absolute;
            top: calc(100% + 4px);
            left: 0; right: 0;
            background: var(--bg-card, #fff);
            border: 1px solid var(--border-color, #ddd);
            border-radius: 8px;
            list-style: none;
            margin: 0; padding: 4px 0;
            z-index: 1000;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            max-height: 220px;
            overflow-y: auto;
        }
        .suggestion-item {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-primary, #222);
            transition: background 0.15s;
            border-bottom: 1px solid var(--border-color, #f0f0f0);
        }
        .suggestion-item:last-child { border-bottom: none; }
        .suggestion-item:hover { background: var(--bg-hover, #f5f5f5); }
        .suggestion-empty {
            padding: 10px 14px;
            font-size: 0.88rem;
            color: var(--text-secondary, #888);
            font-style: italic;
        }
        .address-confirmed {
            border-color: #4caf50 !important;
            box-shadow: 0 0 0 2px rgba(76,175,80,0.2) !important;
        }
        </style>
        ';

        parent::afficher();
    }
}