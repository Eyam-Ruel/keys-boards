<?php
class VueBase {
    protected $titre;
    protected $contenu;
    protected $actionActive = 'explore'; // Variable pour gérer le bouton bordeaux

    public function __construct($titre = "LinkUp - Explore") {
        $this->titre = $titre;
    }

    // On garde afficher() SANS argument pour ne pas casser l'héritage
    public function afficher() {
        echo '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $this->titre . '</title>
            <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/leaflet.css">
            <script src="js/leaflet.js"></script>
         </head>
        <body>

        <header class="topbar">
            <div class="logo">LinkUp</div>
            <div class="topbar-right">
                <div class="topbar-icons">
                    <button class="circle-btn">
                        <img src="./img/icons/Icon.png" alt="Theme" class="topbar-icon-img">
                    </button>
                    <button class="circle-btn">
                        <img src="./img/icons/Icon.png" alt="Language" class="topbar-icon-img">
                    </button>
                </div>
                <button class="signout-btn">Sign out</button>
            </div>
        </header>

        <div class="layout">
            <aside class="sidebar">
                <nav class="nav-menu">
                    <a href="index.php" class="nav-link ' . ($this->actionActive == 'home' ? 'active' : '') . '">
                        <img src="./img/icons/Icon.png" alt="" class="nav-img">
                        <span class="nav-label">Home</span>
                    </a>
                    <a href="index.php?action=explore" class="nav-link ' . ($this->actionActive == 'explore' ? 'active' : '') . '">
                        <img src="./img/icons/Icon.png" alt="" class="nav-img">
                        <span class="nav-label">Explore</span>
                    </a>
                    <a href="index.php?action=notif" class="nav-link ' . ($this->actionActive == 'notif' ? 'active' : '') . '">
                        <img src="./img/icons/Icon.png" alt="" class="nav-img">
                        <span class="nav-label">Notifications</span>
                    </a>
                    <a href="#" class="nav-link ' . ($this->actionActive == 'follow' ? 'active' : '') . '">
                        <img src="./img/icons/Icon.png" alt="" class="nav-img">
                        <span class="nav-label">Follow</span>
                    </a>
                    <a href="#" class="nav-link ' . ($this->actionActive == 'msg' ? 'active' : '') . '">
                        <img src="./img/icons/Icon.png" alt="" class="nav-img">
                        <span class="nav-label">Messages</span>
                    </a>
                    <a href="index.php?action=agenda" class="nav-link ' . ($this->actionActive == 'agenda' ? 'active' : '') . '">
                        <img src="./img/icons/Icon.png" alt="" class="nav-img">
                        <span class="nav-label">Agenda</span>
                    </a>
                    <a href="index.php?action=profil" class="nav-link ' . ($this->actionActive == 'profil' ? 'active' : '') . '">
                        <img src="./img/icons/Icon.png" alt="" class="nav-img">
                        <span class="nav-label">Profile</span>
                    </a>
                </nav>
                
                <div class="sidebar-section">
                    <p class="section-title"># MUSIC BOARDS</p>
                    <div class="tag-item">
                        <div class="tag-left">
                            <img src="./img/icons/Icon.png" alt="" class="tag-img">
                            <span class="tag-name">#Jazz</span>
                        </div>
                        <span class="tag-count">12.5k</span>
                    </div>
                    <div class="tag-item">
                        <div class="tag-left">
                            <img src="./img/icons/Icon.png" alt="" class="tag-img">
                            <span class="tag-name">#MAO</span>
                        </div>
                        <span class="tag-count">8.2k</span>
                    </div>
                    <div class="tag-item">
                        <div class="tag-left">
                            <img src="./img/icons/Icon.png" alt="" class="tag-img">
                            <span class="tag-name">#Vinyl</span>
                        </div>
                        <span class="tag-count">15.3k</span>
                    </div>
                    <div class="tag-item">
                        <div class="tag-left">
                            <img src="./img/icons/Icon.png" alt="" class="tag-img">
                            <span class="tag-name">#Classical</span>
                        </div>
                        <span class="tag-count">6.7k</span>
                    </div>
                    <div class="tag-item">
                        <div class="tag-left">
                            <img src="./img/icons/Icon.png" alt="" class="tag-img">
                            <span class="tag-name">#Rock</span>
                        </div>
                        <span class="tag-count">18.9k</span>
                    </div>
                    <div class="tag-item">
                        <div class="tag-left">
                            <img src="./img/icons/Icon.png" alt="" class="tag-img">
                            <span class="tag-name">#Electro</span>
                        </div>
                        <span class="tag-count">11.4k</span>
                    </div>
                </div>
            </aside>

            <main class="main-area">
                <div class="white-panel">
                    ' . $this->contenu . '
                </div>
            </main>
        </div>
        <script>
window.onload = function () {
    var mapEl = document.getElementById("map");
    if (!mapEl) return;

    if (typeof L === "undefined") {
        alert("Leaflet not loaded");
        return;
    }

    var map = L.map("map").setView([41.3275, 19.8187], 10);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 18,
        attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map);

    var dataEl = document.getElementById("map-users-data");
    var users = [];

    if (dataEl) {
        try {
            users = JSON.parse(dataEl.dataset.users || "[]");
        } catch (e) {
            console.error("Invalid users JSON", e);
        }
    }

    if (users.length > 0) {
        var bounds = [];

        users.forEach(function(user) {
            var lat = parseFloat(user.latitude);
            var lng = parseFloat(user.longitude);

            if (!isNaN(lat) && !isNaN(lng)) {
                var marker = L.marker([lat, lng]).addTo(map);

                marker.bindPopup(
                    "<strong>" + (user.name || "Unknown") + "</strong><br>" +
                    (user.instruments || "Musician")
                );

                bounds.push([lat, lng]);
            }
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    } else {
        L.marker([41.3275, 19.8187]).addTo(map)
            .bindPopup("No users with coordinates yet")
            .openPopup();
    }

    setTimeout(function () {
        map.invalidateSize();
    }, 300);
};
</script>
<script>
window.addEventListener("DOMContentLoaded", function () {
    var addressInput = document.getElementById("address-input");
    var suggestionsBox = document.getElementById("address-suggestions");
    var latitudeInput = document.getElementById("latitude");
    var longitudeInput = document.getElementById("longitude");

    if (!addressInput || !suggestionsBox || !latitudeInput || !longitudeInput) {
        return;
    }

    addressInput.addEventListener("input", function () {
        var query = addressInput.value.trim();

        if (query.length < 3) {
            suggestionsBox.innerHTML = "";
            return;
        }

        fetch("https://nominatim.openstreetmap.org/search?format=json&limit=5&q=" + encodeURIComponent(query))
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                suggestionsBox.innerHTML = "";

                data.forEach(function (place) {
                    var item = document.createElement("div");
                    item.className = "kb-profile-form__suggestion-item";
                    item.textContent = place.display_name;

                    item.addEventListener("click", function () {
                        addressInput.value = place.display_name;
                        latitudeInput.value = place.lat;
                        longitudeInput.value = place.lon;
                        suggestionsBox.innerHTML = "";
                    });

                    suggestionsBox.appendChild(item);
                });
            })
            .catch(function (error) {
                console.error("Nominatim error:", error);
            });
    });
});
</script>
        </body>
        </html>';
    }

    // Petite fonction pour changer l\'onglet actif depuis l\'index
    public function setActionActive($action) {
        $this->actionActive = $action;
    }
}
