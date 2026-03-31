<?php
class VueExplore extends VueBase {
    public function __construct() {
        parent::__construct("Explore - LinkUp");
        $this->actionActive = 'explore';
    }

    private function getMusiciansWithCoords() {
        $pdo = Database::getLink();
        $sql = "SELECT display_name, pseudo, bio, city, country, lat, lng, profile_pic FROM users WHERE lat IS NOT NULL AND lng IS NOT NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
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

        <style>
            /* Le style pour ta popup propre qu'on a fait tout à l'heure */
            .leaflet-popup-content-wrapper { padding: 0 !important; border-radius: 15px !important; overflow: hidden; }
            .leaflet-popup-content { margin: 0 !important; width: 320px !important; display: flex !important; flex-direction: column !important; align-items: center !important; text-align: center !important; padding: 25px 15px !important; }
            .leaflet-popup-content .card--profile { margin: 0 !important; border: none !important; box-shadow: none !important; width: 100% !important; }
            .leaflet-popup-tip-container { display: none; }
        </style>

        <div class="content">
            <main style="width: 100%; margin-top: 20px;">
                <h2>Explore Musicians</h2>
                
                <header class="main--header">
                    <div class="main--header--search">
                        <img src="img/search.png" alt="searchingIcon">
                        <input type="text" placeholder="Search languages, skills, loc..." style="border: none;">
                    </div>
                    <div class="main--header--filters">
                        <span>All languages <img src="img/downArrow.png" alt="DownArrow"></span>
                        <span>All skills <img src="img/downArrow.png" alt="DownArrow"></span>
                    </div>
                </header>

                <div class="switchBtn--content">
                    <div id="btn-list" class="switchBtn switchBtn--active" onclick="toggleExploreView('list')" style="cursor:pointer;">List View</div>
                    <div id="btn-map" class="switchBtn" onclick="toggleExploreView('map')" style="cursor:pointer;">Map View</div>
                </div>

                <section id="view-list" class="cards" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: flex-start;">
                    
                    <div class="card card--profile">
                        <img src="img/ppWoman.png" alt="profile picture" class="card--pp">
                        <span class="card--username">Sophie Martin</span>
                        <span class="card--userID">@sophiemartin</span>
                        <span class="card--desc">Professional jazz pianist. Love teaching and sharing music with all ages.</span>
                        <ul class="tag--list">
                            <li class="card--tag">#jazz</li>
                            <li class="card--tag">#piano</li>
                        </ul>
                        <span class="card--location"><img src="img/pin.png" alt="pin image"> France, lyon</span>
                        <input type="button" value="Connect" class="card--button red--button">
                    </div>

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
            const MUSICIANS_DATA = <?php echo $musiciansJson; ?>;
            let leafletMap = null;

            // ✅ ÉTAPE 4 : LA FONCTION DE SWITCH
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
                        setTimeout(() => leafletMap.invalidateSize(), 150);
                    }
                }
            }

            function initExploreMap() {
                leafletMap = L.map("map").setView([46.6033, 1.8883], 5);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(leafletMap);

                const customIcon = L.divIcon({
                    className: "music-marker",
                    html: `<div style="font-size: 26px;">🎵</div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                });

                MUSICIANS_DATA.forEach(m => {
                    if (m.lat && m.lng) {
                        const cardHtml = `
                            <div class="card card--profile">
                                <img src="${m.profile_pic || 'img/ppMan.png'}" class="card--pp">
                                <span class="card--username">${m.display_name}</span>
                                <span class="card--userID">${m.pseudo}</span>
                                <span class="card--desc">${m.bio || 'Musician on LinkUp ready to jam!'}</span>
                                <span class="card--location" style="margin: 10px 0;">
                                    <img src="img/pin.png" alt="pin" style="width:14px;"> ${m.city}
                                </span>
                                <input type="button" value="Connect" class="card--button red--button">
                            </div>
                        `;
                        L.marker([parseFloat(m.lat), parseFloat(m.lng)], { icon: customIcon })
                         .addTo(leafletMap)
                         .bindPopup(cardHtml);
                    }
                });
            }
        </script>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}