<?php
class VueAgenda extends VueBase {

    public function __construct() {
        parent::__construct("LinkUp – My Agenda");
        $this->actionActive = 'agenda';
    }

    private function getEvents() {
        $pdo = Database::getLink();
        // On récupère tous les événements triés par date
        $sql = "SELECT * FROM events ORDER BY start_date ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function afficher() {
        global $trad;
        $events = $this->getEvents();
        $eventsJson = json_encode($events);

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/agenda.css">

        <div class="content">
            <div style="width: 100%; margin-top: 20px;">
                
                <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; padding: 0 40px 20px 40px;">
                    <h1 class="page-title" style="font-size: 1.5rem; font-weight: 700;">My Agenda</h1>
                    <button class="btn-new" onclick="openModal()" style="background: #810F29; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        + New Event
                    </button>
                </div>

                <div class="legend" style="padding: 0 40px 20px 40px; display: flex; gap: 20px; font-size: 0.85rem;">
                    <div class="legend-item"><span class="legend-dot dot-public"></span> Public Events</div>
                    <div class="legend-item"><span class="legend-dot dot-shared"></span> Shared Events</div>
                    <div class="legend-item"><span class="legend-dot dot-private"></span> Private Events</div>
                </div>

                <div class="calendar-card" style="margin: 0 40px; background: white; border-radius: 12px; border: 1px solid #eee; padding: 20px;">
                    <div class="month-nav" style="display: flex; justify-content: center; align-items: center; gap: 30px; margin-bottom: 20px;">
                        <div class="arrow" onclick="changeMonth(-1)" style="cursor: pointer; font-size: 1.5rem;">&#8249;</div>
                        <span class="month-title" id="monthTitle" style="font-weight: 700; font-size: 1.1rem;">March 2026</span>
                        <div class="arrow" onclick="changeMonth(1)" style="cursor: pointer; font-size: 1.5rem;">&#8250;</div>
                    </div>
                    <div class="cal-grid" id="calHead"></div>
                    <div class="cal-grid" id="calBody"></div>
                </div>

                <div class="section-title" style="padding: 30px 40px 10px 40px; font-weight: 700;">Upcoming Events</div>
                
                <div id="eventsList" style="padding: 0 40px 40px 40px;">
                    <?php if (empty($events)): ?>
                        <p style="color: #666;">No upcoming events.</p>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <div class="event-item" style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #eee; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="openModal(<?= $event['id'] ?>)">
                                <div>
                                    <strong style="display: block;"><?= htmlspecialchars($event['title']) ?></strong>
                                    <small style="color: #888;"><?= date('d M Y, H:i', strtotime($event['start_date'])) ?></small>
                                </div>
                                <span class="legend-dot dot-<?= $event['visibility'] ?? 'public' ?>"></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="modal-overlay" id="modalOverlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center;">
            <div class="modal" style="background: white; padding: 30px; border-radius: 16px; width: 90%; max-width: 500px;">
                <h3 id="modalTitle">New Event</h3>
                
                <form id="eventForm" action="index.php?action=saveEvent" method="POST">
                    <input type="hidden" name="id" id="inp-id">
                    
                    <div class="form-group" style="margin-top: 15px;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600;">Event Name</label>
                        <input type="text" name="title" id="inp-name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" />
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600;">Description</label>
                        <textarea name="description" id="inp-desc" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"></textarea>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <div style="flex: 1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600;">Date</label>
                            <input type="date" name="date" id="inp-date" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" />
                        </div>
                        <div style="flex: 1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600;">Visibility</label>
                            <select name="visibility" id="inp-type" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                <option value="public">Public</option>
                                <option value="shared">Shared</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <div style="flex: 1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600;">Start Time</label>
                            <input type="time" name="start_time" id="inp-start" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" />
                        </div>
                        <div style="flex: 1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600;">End Time</label>
                            <input type="time" name="end_time" id="inp-end" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" />
                        </div>
                    </div>

                    <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                        <button type="button" id="btn-delete" onclick="deleteEvent()" style="display:none; padding: 8px 16px; background: #ff4d4d; color: white; border: none; border-radius: 8px; cursor: pointer; margin-right: auto;">Delete</button>
                        
                        <button type="button" class="btn-cancel" onclick="closeModal()" style="padding: 8px 16px; border: 1px solid #ddd; background: none; border-radius: 8px; cursor: pointer;">Cancel</button>
                        <button type="submit" class="btn-save" style="padding: 8px 16px; background: #810F29; color: white; border: none; border-radius: 8px; cursor: pointer;">Save Event</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Le pont entre PHP et JS
            window.dbEvents = <?php echo $eventsJson; ?>;
            console.log("Données reçues de la BDD :", window.dbEvents);
        </script>
        <script src="js/agenda.js"></script>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}