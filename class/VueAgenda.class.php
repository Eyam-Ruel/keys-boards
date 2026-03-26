<?php
class VueAgenda extends VueBase {

    public function __construct() {
        // On définit le titre via le constructeur du parent
        parent::__construct("LinkUp – My Agenda");
    }

    public function afficherAgenda() {
        // On stocke TOUT le HTML dans la variable $this->contenu
        $this->contenu = '
        <div class="page-header">
            <h1 class="page-title">My Agenda</h1>
            <button class="btn-new" onclick="openModal()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Event
            </button>
        </div>

        <div class="calendar-card">
            <div class="month-nav">
                <div class="arrow" onclick="changeMonth(-1)">&#8249;</div>
                <span class="month-title" id="monthTitle">March 2026</span>
                <div class="arrow" onclick="changeMonth(1)">&#8250;</div>
            </div>
            <div class="cal-grid" id="calHead"></div>
            <div class="cal-grid" id="calBody"></div>
        </div>

        <div class="section-title">Upcoming Events</div>
        <div id="eventsList"></div>';

        // Enfin, on appelle la méthode magique du parent qui va tout afficher d'un coup
        parent::afficher();
    }
}