<?php
class VueBase {
    protected $titre;
    protected $contenu;
    protected $actionActive = 'explore';
    protected $afficherSidebar = true; // Par défaut, on l'affiche partout

    public function __construct($titre = "LinkUp") {
        $this->titre = $titre;
    }

    public function setAfficherSidebar($bool) {
        $this->afficherSidebar = $bool;
    }

    public function afficher() {
        echo '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $this->titre . '</title>
            <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="css/style.css">
            <style>
                /* Si la sidebar est masquée, on fait prendre toute la largeur au main */
                .layout-no-sidebar { display: block !important; }
                .layout-no-sidebar .main-area { width: 100% !important; margin: 0 !important; }
            </style>
        </head>
        <body>

        <header class="topbar">
            <div class="logo">LinkUp</div>
            <div class="topbar-right">
                <div class="topbar-icons">
                    <button class="circle-btn"><img src="./img/icons/Light Mode.png" alt="Theme" class="topbar-icon-img"></button>
                    <button class="circle-btn"><img src="./img/icons/Language.png" alt="Language" class="topbar-icon-img"></button>
                </div>
                <button class="signout-btn">Sign out</button>
            </div>
        </header>

        <div class="layout ' . ($this->afficherSidebar ? '' : 'layout-no-sidebar') . '">';

        // ON AFFICHE LA SIDEBAR SEULEMENT SI $afficherSidebar EST TRUE
        if ($this->afficherSidebar) {
            echo '<aside class="sidebar">
                <nav class="nav-menu">
                    <a href="index.php?action=feed" class="nav-link ' . ($this->actionActive == 'feed' ? 'active' : '') . '">
                        <img src="./img/icons/Home.png" alt="" class="nav-img">
                        <span class="nav-label">Home</span>
                    </a>

                    <a href="index.php?action=explore" class="nav-link ' . ($this->actionActive == 'explore' ? 'active' : '') . '">
                        <img src="./img/icons/Explore.png" alt="" class="nav-img">
                        <span class="nav-label">Explore</span>
                    </a>

                    <a href="index.php?action=notifications" class="nav-link ' . ($this->actionActive == 'notifications' ? 'active' : '') . '">
                        <img src="./img/icons/Notifications.png" alt="" class="nav-img">
                        <span class="nav-label">Notifications</span>
                    </a>

                    <a href="index.php?action=follow" class="nav-link ' . ($this->actionActive == 'follow' ? 'active' : '') . '">
                        <img src="./img/icons/Community.png" alt="" class="nav-img">
                        <span class="nav-label">Follow</span>
                    </a>

                    <a href="index.php?action=messages" class="nav-link ' . ($this->actionActive == 'messages' ? 'active' : '') . '">
                        <img src="./img/icons/Messages.png" alt="" class="nav-img">
                        <span class="nav-label">Messages</span>
                    </a>

                    <a href="index.php?action=agenda" class="nav-link ' . ($this->actionActive == 'agenda' ? 'active' : '') . '">
                        <img src="./img/icons/Agenda.png" alt="" class="nav-img">
                        <span class="nav-label">Agenda</span>
                    </a>

                    <a href="index.php?action=profile" class="nav-link ' . ($this->actionActive == 'profile' ? 'active' : '') . '">
                        <img src="./img/icons/Profile.png" alt="" class="nav-img">
                        <span class="nav-label">Profile</span>
                    </a>
                </nav>

                <div class="sidebar-section">
                    <h3 class="section-title"># Music Boards</h3>
                    
                    <div class="tag-item">
                        <div class="tag-left">
                            <span class="tag-name">#Jazz</span>
                        </div>
                        <span class="tag-count">12.5k</span>
                    </div>

                    <div class="tag-item">
                        <div class="tag-left">
                            <span class="tag-name">#MAO</span>
                        </div>
                        <span class="tag-count">8.2k</span>
                    </div>

                    <div class="tag-item">
                        <div class="tag-left">
                            <span class="tag-name">#Vinyl</span>
                        </div>
                        <span class="tag-count">15.3k</span>
                    </div>
                    
                    </div>
            </aside>';
        }

        echo '<main class="main-area">
                ' . $this->contenu . '
            </main>
        </div>
        </body>
        </html>';
    }

    public function setActionActive($action) {
        $this->actionActive = $action;
    }
}