<?php

class VueNotif extends VueBase
{
    public function __construct()
    {
        parent::__construct("Notifications - LinkUp");
    }

    public function afficher()
    {
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

        $itemsHtml = '';

        foreach ($notifications as $notification) {
            $buttonHtml = '';
            if (!empty($notification['button'])) {
                $buttonHtml = '<button class="kb-notification__button">' . htmlspecialchars($notification['button']) . '</button>';
            }

            $notificationClass = ($notification["time"] === "2 min ago" || $notification["time"] === "10 min ago")
    ? 'kb-notification kb-notification--unread'
    : 'kb-notification';

         $itemsHtml .= '
         <article class="' . $notificationClass . '">
            <div class="kb-notification__left">
            <div class="kb-notification__avatar"></div>
                <div class="kb-notification__content">
                  <p class="kb-notification__text">
                    <strong>' . htmlspecialchars($notification['name']) . '</strong>
                    <span class="kb-notification__username">' . htmlspecialchars($notification['username']) . '</span>
                    ' . htmlspecialchars($notification['text']) . '
                  </p>
                  <p class="kb-notification__time">' . htmlspecialchars($notification['time']) . '</p>
                </div>
            </div>
            <div class="kb-notification__right">
            ' . $buttonHtml . '
            </div>
    </article>
';
        }

        $this->contenu = '
        <section class="kb-notifications-page">
            <div class="kb-page-heading">
                <h1 class="kb-page-heading__title">Notifications</h1>
                <p class="kb-page-heading__subtitle">Stay updated with activity from your music community.</p>
            </div>

            <div class="kb-notifications-card">
                ' . $itemsHtml . '
            </div>
        </section>';

        parent::afficher();
    }
}