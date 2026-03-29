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
                    <a href="#" class="nav-link ' . ($this->actionActive == 'notif' ? 'active' : '') . '">
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
            // JS pour changer la couleur au clic instantanément
            document.querySelectorAll(".nav-link").forEach(link => {
                link.addEventListener("click", function() {
                    document.querySelectorAll(".nav-link").forEach(l => l.classList.remove("active"));
                    this.classList.add("active");
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