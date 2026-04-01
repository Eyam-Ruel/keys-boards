<?php

class VueNotif extends VueBase
{
    public function __construct()
    {
        parent::__construct("Notifications - LinkUp");
        // On définit l'action active pour que le menu bordeaux suive sur la sidebar
        $this->actionActive = 'notifications';
    }

    public function afficher()
    {
        // Données statiques pour le test
        $notifications = [
            [
                "name" => "Emma Rousseau",
                "username" => "@emmar",
                "text" => "started following you.",
                "time" => "2 min ago",
                "button" => "Follow back"
            ],
            [
                "name" => "Jean Dupont",
                "username" => "@jeandupont",
                "text" => "liked your post in Jazz Board.",
                "time" => "10 min ago",
                "button" => ""
            ],
            [
                "name" => "Marie Leclerc",
                "username" => "@marieleclerc",
                "text" => "commented on your tutorial: “Great explanation!”",
                "time" => "25 min ago",
                "button" => "Reply"
            ],
            [
                "name" => "Lucas Moreau",
                "username" => "@lucasmusic",
                "text" => "invited you to collaborate on a project.",
                "time" => "1 hour ago",
                "button" => "View"
            ],
            [
                "name" => "Sophie Martin",
                "username" => "@sophiemusic",
                "text" => "shared a new piano tutorial in Classical Board.",
                "time" => "3 hours ago",
                "button" => "Open"
            ]
        ];

        // On commence la capture du contenu
        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/notif.css">

        <section class="kb-notifications-page">
            <div class="kb-page-heading">
                <h1 class="kb-page-heading__title">Notifications</h1>
                <p class="kb-page-heading__subtitle">Stay updated with activity from your music community.</p>
            </div>

            <div class="kb-notifications-card">
                <?php foreach ($notifications as $notification): 
                    // Logique pour la classe "unread"
                    $isUnread = ($notification["time"] === "2 min ago" || $notification["time"] === "10 min ago");
                    $notificationClass = $isUnread ? 'kb-notification kb-notification--unread' : 'kb-notification';
                ?>
                    <article class="<?php echo $notificationClass; ?>">
                        <div class="kb-notification__left">
                            <div class="kb-notification__avatar"></div>
                            <div class="kb-notification__content">
                                <p class="kb-notification__text">
                                    <strong><?php echo htmlspecialchars($notification['name']); ?></strong>
                                    <span class="kb-notification__username"><?php echo htmlspecialchars($notification['username']); ?></span>
                                    <?php echo htmlspecialchars($notification['text']); ?>
                                </p>
                                <p class="kb-notification__time"><?php echo htmlspecialchars($notification['time']); ?></p>
                            </div>
                        </div>
                        <div class="kb-notification__right">
                            <?php if (!empty($notification['button'])): ?>
                                <button class="kb-notification__button">
                                    <?php echo htmlspecialchars($notification['button']); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <?php
        // On récupère tout ce qui est au-dessus et on le met dans $this->contenu
        $this->contenu = ob_get_clean();

        // On appelle le rendu final du parent (VueBase)
        parent::afficher();
    }
}