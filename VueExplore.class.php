<?php
class VueExplore extends VueBase {
    public function __construct() {
        global $trad;
        $titre = $trad['side_explore'] ?? "Explore";
        parent::__construct($titre . " - LinkUp");
        $this->actionActive = 'explore';
    }

    private function getMusiciansWithCoords() {
        $pdo = Database::getLink();
        $myId = $_SESSION['user_id'] ?? 0;
    
        $sql = "SELECT u.*, f.follower_id AS is_followed
                FROM users u 
                LEFT JOIN follows f ON (u.id = f.followed_id AND f.follower_id = :my_id)
                WHERE u.id != :my_id
                GROUP BY u.id 
                ORDER BY u.created_at DESC";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':my_id' => $myId]);
        
        return $stmt->fetchAll();
    }

    public function afficher() {
        global $trad; 

        $musicians = $this->getMusiciansWithCoords();
        $musiciansJson = json_encode($musicians);

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/explore.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <div class="content">
            <main style="flex: 1; padding: 0 20px; margin-top: 20px;">
                <h2><?= $trad['explore_title'] ?? 'Explore Musicians' ?></h2>
                
                <header class="main--header">
                    <div class="main--header--search">
                        <img src="img/search.png" alt="searchingIcon">
                        <input type="text" placeholder="<?= $trad['explore_search_placeholder'] ?? 'Search musicians...' ?>" style="border: none;">
                    </div>
                </header>

                <div class="switchBtn--content">
                    <div id="btn-list" class="switchBtn switchBtn--active" onclick="toggleExploreView('list')" style="cursor:pointer;">
                        <?= $trad['explore_view_list'] ?? 'List View' ?>
                    </div>
                    <div id="btn-map" class="switchBtn" onclick="toggleExploreView('map')" style="cursor:pointer;">
                        <?= $trad['explore_view_map'] ?? 'Map View' ?>
                    </div>
                </div>

                <section id="view-list" class="cards" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
                    <?php if(empty($musicians)): ?>
                        <p><?= $trad['explore_no_results'] ?? 'No musicians found.' ?></p>
                    <?php else: ?>
                        <?php foreach($musicians as $m): ?>
                            <div class="card card--profile" id="card-musician-<?= $m['id'] ?>">
                                <img src="<?= !empty($m['profile_pic']) ? $m['profile_pic'] : 'img/ppMan.png' ?>" class="card--pp">
                                <span class="card--username"><?= htmlspecialchars($m['display_name']) ?></span>
                                <span class="card--userID">@<?= htmlspecialchars($m['pseudo']) ?></span>
                                <span class="card--desc"><?= htmlspecialchars($m['bio'] ?? ($trad['explore_default_bio'] ?? 'Ready to jam!')) ?></span>
                                
                                <div class="card--languages" style="display: flex; gap: 5px; flex-wrap: wrap; margin-bottom: 10px; justify-content: center;">
                                    <?php 
                                    if (!empty($m['languages'])): 
                                        $langs = explode(',', $m['languages']);
                                        foreach ($langs as $l): ?>
                                            <span style="background: #f0f0f0; color: #810F29; font-size: 10px; padding: 2px 8px; border-radius: 10px; border: 1px solid #ddd; font-weight: bold;">
                                                <?= htmlspecialchars(trim($l)) ?>
                                            </span>
                                        <?php endforeach; 
                                    endif; ?>
                                </div>
                                <span class="card--location">
                                    <img src="img/pin.png" alt="pin"> 
                                    <?php 
                                        $locRaw = $m['city'] ?? ($trad['explore_unknown_location'] ?? 'Unknown');
                                        $parts = explode(',', $locRaw);
                                        echo (count($parts) > 1) ? htmlspecialchars(trim($parts[0]) . ', ' . trim(end($parts))) : htmlspecialchars($locRaw);
                                    ?>
                                </span>

                                <?php 
                                $isFollowed = !empty($m['is_followed']); 
                                $btnText = $isFollowed ? ($trad['explore_btn_followed'] ?? "Followed") : ($trad['explore_btn_connect'] ?? "Connect");
                                $btnClass = $isFollowed ? "followed-style" : "";
                                ?>

                                <button class="card--button red--button <?= $btnClass ?>" 
                                        onclick="handleToggleFollow(<?= $m['id'] ?>, this)">
                                    <?= $btnText ?>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>

                <section id="view-map" style="display: none; margin-top: 20px;">
                    <div style="background: white; border-radius: 12px; border: 1px solid #eee; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                        <div id="map" style="height: 600px; width: 100%;"></div>
                    </div>
                </section>
            </main>
        </div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            // On passe les variables de trad au JS pour les popups de la carte
            const TRAD_CONNECT = <?= json_encode($trad['explore_btn_connect'] ?? 'Connect') ?>;
            const TRAD_FOLLOWED = <?= json_encode($trad['explore_btn_followed'] ?? 'Followed') ?>;

            const MUSICIANS_DATA = <?= $musiciansJson ?>;
            let leafletMap = null;

            function handleToggleFollow(userId, btnElement) {
                // Utilisation des variables trad pour la logique de détection
                const isFollowed = btnElement.innerText.trim() === TRAD_FOLLOWED;
                const action = isFollowed ? "doUnfollow" : "doFollow";

                fetch(`index.php?action=${action}&id=${userId}`)
                    .then(() => {
                        if (isFollowed) {
                            btnElement.innerText = TRAD_CONNECT;
                            btnElement.classList.remove("followed-style");
                            btnElement.style.backgroundColor = ""; 
                        } else {
                            btnElement.innerText = TRAD_FOLLOWED;
                            btnElement.classList.add("followed-style");
                            btnElement.style.backgroundColor = "#28a745"; 
                        }
                    })
                    .catch(err => console.error("Erreur :", err));
            }

            function initExploreMap() {
                leafletMap = L.map("map").setView([46.6033, 1.8883], 5);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(leafletMap);

                MUSICIANS_DATA.forEach(m => {
                    if (m.lat && m.lng && m.lat !== "0.00000000") {
                        const isFollowed = m.is_followed !== null;
                        const btnTxt = isFollowed ? TRAD_FOLLOWED : TRAD_CONNECT;
                        const btnCol = isFollowed ? "#28a745" : "#810F29";

                        const popupContent = `
                            <div style="text-align:center; font-family: sans-serif; min-width: 120px;">
                                <img src="${m.profile_pic || 'img/ppMan.png'}" style="width:50px; height:50px; border-radius:50%; object-fit:cover; margin-bottom:5px;">
                                <h4 style="margin:2px 0;">${m.display_name}</h4>
                                <p style="font-size:11px; color:#666; margin-bottom:8px;">@${m.pseudo}</p>
                                <button class="map-connect-btn" 
                                        onclick="handleToggleFollow(${m.id}, this)" 
                                        style="padding:5px 12px; font-size:11px; border:none; border-radius:15px; color:white; background:${btnCol}; cursor:pointer; font-weight:bold;">
                                    ${btnTxt}
                                </button>
                            </div>
                        `;
                        L.marker([parseFloat(m.lat), parseFloat(m.lng)]).addTo(leafletMap).bindPopup(popupContent);
                    }
                });
            }

            function toggleExploreView(mode) {
                const list = document.getElementById('view-list');
                const mapDiv = document.getElementById('view-map');
                const btnList = document.getElementById('btn-list');
                const btnMap = document.getElementById('btn-map');
                if (mode === 'list') {
                    list.style.display = 'flex';
                    mapDiv.style.display = 'none';
                    btnList.classList.add('switchBtn--active');
                    btnMap.classList.remove('switchBtn--active');
                } else {
                    list.style.display = 'none';
                    mapDiv.style.display = 'block';
                    btnList.classList.remove('switchBtn--active');
                    btnMap.classList.add('switchBtn--active');
                    if (!leafletMap) initExploreMap();
                    else setTimeout(() => leafletMap.invalidateSize(), 200);
                }
            }
        </script>

        <style>
            .followed-style {
                background-color: #28a745 !important;
                border: none !important;
            }
        </style>
        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}