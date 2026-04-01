<?php
class VueExplore extends VueBase {
    public function __construct() {
        parent::__construct("Explore - LinkUp");
        $this->actionActive = 'explore';
    }

    private function getMusiciansWithCoords() {
        $pdo = Database::getLink();
        $myId = $_SESSION['user_id'] ?? 0;
    
        // On ajoute une colonne "is_followed" qui vaut l'ID si on suit, sinon NULL
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
                <h2>Explore Musicians</h2>
                
                <header class="main--header">
                    <div class="main--header--search">
                        <img src="img/search.png" alt="searchingIcon">
                        <input type="text" placeholder="Search musicians..." style="border: none;">
                    </div>
                </header>

                <div class="switchBtn--content">
                    <div id="btn-list" class="switchBtn switchBtn--active" onclick="toggleExploreView('list')" style="cursor:pointer;">List View</div>
                    <div id="btn-map" class="switchBtn" onclick="toggleExploreView('map')" style="cursor:pointer;">Map View</div>
                </div>

                <section id="view-list" class="cards" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
                        <?php if(empty($musicians)): ?>
                        <p>No musicians found.</p>
                    <?php else: ?>
                        <?php foreach($musicians as $m): ?>
                            <div class="card card--profile">
                                <img src="<?= !empty($m['profile_pic']) ? $m['profile_pic'] : 'img/ppMan.png' ?>" alt="profile picture" class="card--pp">
                                <span class="card--username"><?= htmlspecialchars($m['display_name']) ?></span>
                                <span class="card--userID">@<?= htmlspecialchars($m['pseudo']) ?></span>
                                <span class="card--desc"><?= htmlspecialchars($m['bio'] ?? 'Ready to jam!') ?></span>
                                
                                <span class="card--location">
                                    <img src="img/pin.png" alt="pin"> <?= htmlspecialchars($m['city'] ?? 'Unknown') ?>, <?= htmlspecialchars($m['country'] ?? 'FR') ?>
                                </span>
                                <?php 
// Si is_followed n'est pas vide, ça veut dire qu'on le suit déjà
$isFollowed = !empty($m['is_followed']); 
$btnText = $isFollowed ? "Followed" : "Connect";
$btnStyle = $isFollowed ? "background-color: #28a745; border: none;" : "";
?>

<button class="card--button red--button" 
        style="<?= $btnStyle ?>" 
        onclick="toggleFollow(<?= $m['id'] ?>, this)">
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
            // On récupère les données PHP transformées en JSON
            const MUSICIANS_DATA = <?= $musiciansJson ?>;
            let leafletMap = null;

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

                    if (!leafletMap) {
                        initExploreMap();
                    } else {
                        setTimeout(() => leafletMap.invalidateSize(), 200);
                    }
                }
            }

            function initExploreMap() {
                // Centre sur la France par défaut
                leafletMap = L.map("map").setView([46.6033, 1.8883], 5);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(leafletMap);

                MUSICIANS_DATA.forEach(m => {
                    // On ne place un marqueur que si les coordonnées existent
                    if (m.lat && m.lng && m.lat !== "0.00000000") {
                        const popupContent = `
                            <div style="text-align:center; font-family: sans-serif;">
                                <img src="${m.profile_pic || 'img/ppMan.png'}" style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
                                <h4 style="margin:5px 0;">${m.display_name}</h4>
                                <p style="font-size:12px; color:#666;">@${m.pseudo}</p>
                                <button class="red--button" style="padding:5px 10px; font-size:11px; border:none; border-radius:5px; color:white; background:#810F29; cursor:pointer;">Connect</button>
                            </div>
                        `;
                        L.marker([parseFloat(m.lat), parseFloat(m.lng)]).addTo(leafletMap).bindPopup(popupContent);
                    }
                });
            }
        </script>
        <script src="js/messages.js"></script>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}