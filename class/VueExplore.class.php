<?php
class VueExplore extends VueBase {
    public function __construct() {
        parent::__construct("Explore - LinkUp");
    }

    public function afficher() {
        $this->contenu = '
        <div class="page-header">
            <h1 class="page-title">Explore Musicians</h1>
            <button class="btn-new">Update Location</button>
        </div>
        
        <div class="calendar-card" style="padding:20px;">
            <p>Ici tu pourras mettre ta carte Leaflet !</p>
            <div id="map" style="height: 400px; background: #eee; border-radius: 8px;"></div>
        </div>';

        parent::afficher();
    }
}