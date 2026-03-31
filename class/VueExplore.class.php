<?php
class VueExplore extends VueBase {
    public function __construct() {
        parent::__construct("Explore - LinkUp");
    }

    // Returns all users with GPS coordinates
    private function getMusiciansWithCoords() {
        $pdo = Database::getLink();
        $sql = "SELECT display_name, pseudo, city, country, lat, lng, profile_pic
                FROM users
                WHERE lat IS NOT NULL AND lng IS NOT NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // [BONUS] Returns musicians within a given radius (km) using Haversine formula
    private function getMusiciansNearby($lat0, $lng0, $radius = 10) {
        $pdo = Database::getLink();
        $sql = "SELECT display_name, pseudo, city, country, lat, lng, profile_pic,
                    (6371 * ACOS(
                        COS(RADIANS(:lat)) * COS(RADIANS(lat))
                        * COS(RADIANS(lng) - RADIANS(:lng))
                        + SIN(RADIANS(:lat)) * SIN(RADIANS(lat))
                    )) AS distance
                FROM users
                WHERE lat IS NOT NULL AND lng IS NOT NULL
                HAVING distance < :radius
                ORDER BY distance ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':lat'    => $lat0,
            ':lng'    => $lng0,
            ':radius' => $radius,
        ]);
        return $stmt->fetchAll();
    }

    public function afficher() {
        // Get all musicians with coordinates
        $musicians     = $this->getMusiciansWithCoords();
        $musiciansJson = json_encode($musicians, JSON_HEX_TAG | JSON_HEX_AMP);

        // Read logged-in user coordinates from session (set in User::connecter)
        $userLat     = $_SESSION['lat'] ?? null;
        $userLng     = $_SESSION['lng'] ?? null;
        $hasLocation = ($userLat !== null && $userLng !== null) ? 'true' : 'false';

        $this->contenu = '
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <main id="content-area">
            <div class="page-header">
                <h1 class="page-title">Explore Musicians</h1>
                <div class="explore-controls">
                    <!-- Proximity filter panel (hidden by default) -->
                    <div class="proximity-filter" id="proximity-filter" style="display:none;">
                        <label for="radius-slider">Radius: <span id="radius-value">10</span> km</label>
                        <input type="range" id="radius-slider" min="1" max="100" value="10" step="1">
                        <button class="btn-filter" id="btn-apply-filter">Apply</button>
                        <button class="btn-filter btn-secondary" id="btn-reset-filter">Show All</button>
                    </div>
                    <button class="btn-new" id="btn-location-filter">📍 Near Me</button>
                </div>
            </div>

            <div class="calendar-card explore-map-card">
                <div id="map"></div>
                <p class="map-counter" id="map-counter">Loading musicians...</p>
            </div>
        </main>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
        // PHP-to-JS bridge: inject server data as JS constants
        const MUSICIANS    = ' . $musiciansJson . ';
        const USER_HAS_LOC = ' . $hasLocation . ';
        const USER_LAT     = ' . ($userLat ?? 'null') . ';
        const USER_LNG     = ' . ($userLng ?? 'null') . ';

        // Init map — center on user if location exists, otherwise Paris
        const defaultCenter = USER_HAS_LOC ? [USER_LAT, USER_LNG] : [48.8566, 2.3522];
        const map = L.map("map", { zoomControl: true }).setView(defaultCenter, 5);

        // Fix: recalculate tile layout after container fully renders
        setTimeout(() => map.invalidateSize(), 300);

        // Fix: disable default Leaflet icon to avoid 404 on Icon.png
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({ iconUrl: "", shadowUrl: "" });

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "© OpenStreetMap contributors",
            maxZoom: 18
        }).addTo(map);

        // Custom emoji marker icon
        const musicIcon = L.divIcon({
            className: "",
            html: `<div class="custom-marker">🎵</div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -20]
        });

        let markers     = [];
        let markerGroup = L.layerGroup().addTo(map);

        // Render a list of musicians as markers on the map
        function renderMarkers(musicianList) {
            markerGroup.clearLayers();
            markers = [];

            musicianList.forEach(m => {
                const lat = parseFloat(m.lat);
                const lng = parseFloat(m.lng);
                if (isNaN(lat) || isNaN(lng)) return;

                // Popup uses display_name, pseudo, city, country from DB
                const city    = m.city    ?? "";
                const country = m.country ?? "";
                const sep     = city && country ? ", " : "";

                const popup = `
                    <div class="map-popup">
                        <strong>${m.display_name}</strong>
                        <span class="popup-pseudo">${m.pseudo}</span>
                        <span class="popup-location">📍 ${city}${sep}${country}</span>
                    </div>`;

                const marker = L.marker([lat, lng], { icon: musicIcon })
                    .bindPopup(popup, { maxWidth: 200 });

                markerGroup.addLayer(marker);
                markers.push(marker);
            });

            const count = markers.length;
            document.getElementById("map-counter").textContent =
                count === 0
                    ? "No musicians found in this area."
                    : `${count} musician${count > 1 ? "s" : ""} found`;

            // Auto-fit map bounds when multiple markers exist
            if (count > 1) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.2));
            }
        }

        // Render all musicians on page load
        renderMarkers(MUSICIANS);

        // Haversine formula — returns distance in km between two coordinates
        function haversine(lat1, lng1, lat2, lng2) {
            const R    = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a    = Math.sin(dLat / 2) ** 2
                       + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
                       * Math.sin(dLng / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        // DOM refs for proximity filter controls
        const btnLocationFilter = document.getElementById("btn-location-filter");
        const filterPanel       = document.getElementById("proximity-filter");
        const radiusSlider      = document.getElementById("radius-slider");
        const radiusValue       = document.getElementById("radius-value");
        const btnApplyFilter    = document.getElementById("btn-apply-filter");
        const btnResetFilter    = document.getElementById("btn-reset-filter");

        // Update displayed radius label on slider change
        radiusSlider.addEventListener("input", () => {
            radiusValue.textContent = radiusSlider.value;
        });

        // Toggle filter panel — requires user to have a saved location
        btnLocationFilter.addEventListener("click", () => {
            if (!USER_HAS_LOC) {
                alert("Please set your location in your Profile first.");
                return;
            }
            filterPanel.style.display = filterPanel.style.display === "none" ? "flex" : "none";
        });

        // Filter musicians by radius and recenter map on user
        btnApplyFilter.addEventListener("click", () => {
            const radius = parseInt(radiusSlider.value);
            const nearby = MUSICIANS.filter(m => {
                const d = haversine(USER_LAT, USER_LNG, parseFloat(m.lat), parseFloat(m.lng));
                return d <= radius;
            });
            renderMarkers(nearby);
            map.setView([USER_LAT, USER_LNG], 11);
        });

        // Reset filter — show all musicians
        btnResetFilter.addEventListener("click", () => {
            renderMarkers(MUSICIANS);
            filterPanel.style.display = "none";
        });

        // Show logged-in user position as a distinct circle marker
        if (USER_HAS_LOC) {
            L.circleMarker([USER_LAT, USER_LNG], {
                radius: 10, color: "#fff", fillColor: "#6c63ff",
                fillOpacity: 1, weight: 3
            }).addTo(map).bindPopup("<b>You are here</b>");
        }
        </script>

        <style>
        .explore-map-card { padding: 0; overflow: hidden; }

        #map { height: 520px; width: 100%; border-radius: 12px; }

        .explore-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .proximity-filter {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg-secondary, #f5f5f5);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .proximity-filter label { white-space: nowrap; }

        #radius-slider { width: 100px; accent-color: var(--color-primary, #6c63ff); }

        .btn-filter {
            padding: 6px 14px;
            border-radius: 6px;
            border: none;
            background: var(--color-primary, #6c63ff);
            color: #fff;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .btn-filter:hover { opacity: 0.85; }
        .btn-filter.btn-secondary {
            background: var(--bg-secondary, #ddd);
            color: var(--text-primary, #333);
        }

        .map-counter {
            text-align: center;
            padding: 8px;
            font-size: 0.85rem;
            color: var(--text-secondary, #888);
            margin: 0;
        }

        .map-popup { display: flex; flex-direction: column; gap: 3px; min-width: 130px; }
        .map-popup strong { font-size: 1rem; }
        .popup-pseudo   { font-size: 0.82rem; color: #888; }
        .popup-location { font-size: 0.82rem; }

        .custom-marker {
            font-size: 24px;
            line-height: 36px;
            text-align: center;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
            transition: transform 0.15s;
        }
        .custom-marker:hover { transform: scale(1.2); }
        </style>
        ';

        parent::afficher();
    }
}