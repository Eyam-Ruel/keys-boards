<?php

class VueExplore extends VueBase
{
    private $users;
    private $tab;

    public function __construct($users = [], $tab = 'all')
    {
        parent::__construct("Explore - LinkUp");
        $this->users = $users;
        $this->tab = $tab;
    }

    public function afficher()
    {
        $allMusicians = [
            [
                "name" => "Sophie Martin",
                "username" => "@sophiemusic",
                "location" => "Paris, France",
                "description" => "Classical pianist | conservatory teacher",
                "tags" => ["Piano", "Violin", "MusicTheory"],
                "followers" => "2 340 followers",
                "button" => "Follow",
                "category" => "suggestions"
            ],
            [
                "name" => "Jean Dupont",
                "username" => "@jeandupont",
                "location" => "Lyon, France",
                "description" => "Jazz & blues guitarist | session musician",
                "tags" => ["Guitar", "Bass", "Improvisation"],
                "followers" => "1 850 followers",
                "button" => "Following",
                "category" => "following"
            ],
            [
                "name" => "Marie Leclerc",
                "username" => "@marieleclerc",
                "location" => "Montreal, Canada",
                "description" => "Electronic music producer | MAO specialist",
                "tags" => ["MAO", "Synth", "Production"],
                "followers" => "3 120 followers",
                "button" => "Follow",
                "category" => "suggestions"
            ],
            [
                "name" => "Emma Rousseau",
                "username" => "@emmar",
                "location" => "Brussels, Belgium",
                "description" => "Soul & R&B singer | vocal coach",
                "tags" => ["Vocals", "Piano", "Performance"],
                "followers" => "5 670 followers",
                "button" => "Following",
                "category" => "following"
            ],
            [
                "name" => "Lucas Moreau",
                "username" => "@lucasmusic",
                "location" => "Geneva, Switzerland",
                "description" => "Sound engineer | live technician",
                "tags" => ["Mix", "Mastering", "Audio"],
                "followers" => "2 890 followers",
                "button" => "Follow",
                "category" => "suggestions"
            ],
            [
                "name" => "Antoine Leroy",
                "username" => "@antoineleroy",
                "location" => "Lille, France",
                "description" => "DJ & Producer | house / techno vibes",
                "tags" => ["DJ", "House", "Live"],
                "followers" => "4 450 followers",
                "button" => "Follow",
                "category" => "suggestions"
            ]
        ];

        $musicians = $allMusicians;

        if ($this->tab === 'following') {
            $musicians = array_filter($allMusicians, function ($musician) {
                return $musician['category'] === 'following';
            });
        } elseif ($this->tab === 'suggestions') {
            $musicians = array_filter($allMusicians, function ($musician) {
                return $musician['category'] === 'suggestions';
            });
        }

        $cardsHtml = '';

        foreach ($musicians as $musician) {
            $tagsHtml = '';
            foreach ($musician["tags"] as $tag) {
                $tagsHtml .= '<span class="kb-tag">#' . htmlspecialchars($tag) . '</span>';
            }

            $buttonClass = $musician["button"] === "Following"
                ? 'kb-follow-button kb-follow-button--following'
                : 'kb-follow-button';

            $cardsHtml .= '
                <article class="kb-musician-card">
                    <div class="kb-musician-card__top">
                        <div class="kb-musician-card__identity">
                            <div class="kb-musician-card__avatar"></div>
                            <div class="kb-musician-card__meta">
                                <h3 class="kb-musician-card__name">' . htmlspecialchars($musician["name"]) . '</h3>
                                <p class="kb-musician-card__username">' . htmlspecialchars($musician["username"]) . '</p>
                                <p class="kb-musician-card__location">' . htmlspecialchars($musician["location"]) . '</p>
                            </div>
                        </div>
                        <button class="' . $buttonClass . '">' . htmlspecialchars($musician["button"]) . '</button>
                    </div>

                    <p class="kb-musician-card__description">' . htmlspecialchars($musician["description"]) . '</p>

                    <div class="kb-musician-card__tags">
                        ' . $tagsHtml . '
                    </div>

                    <p class="kb-musician-card__followers">' . htmlspecialchars($musician["followers"]) . '</p>
                </article>
            ';
        }

        $allActive = $this->tab === 'all' ? 'kb-explore-tabs__item--active' : '';
        $followingActive = $this->tab === 'following' ? 'kb-explore-tabs__item--active' : '';
        $suggestionsActive = $this->tab === 'suggestions' ? 'kb-explore-tabs__item--active' : '';

        $usersJson = htmlspecialchars(json_encode($this->users), ENT_QUOTES, 'UTF-8');

        $this->contenu = '
        <section class="kb-explore-page">
            <div class="kb-page-heading">
                <h1 class="kb-page-heading__title">Discover Musicians</h1>
                <p class="kb-page-heading__subtitle">Find musicians, producers and collaborators near you.</p>
            </div>

            <div class="kb-explore-tabs">
                <a href="index.php?action=explore&tab=all" class="kb-explore-tabs__item ' . $allActive . '">All</a>
                <a href="index.php?action=explore&tab=following" class="kb-explore-tabs__item ' . $followingActive . '">Following</a>
                <a href="index.php?action=explore&tab=suggestions" class="kb-explore-tabs__item ' . $suggestionsActive . '">Suggestions</a>
            </div>

            <div class="kb-explore-layout">
                <div class="kb-explore-grid">
                    ' . $cardsHtml . '
                </div>

                <aside class="kb-map-panel">
                    <div class="kb-map-panel__header">
                        <h2 class="kb-map-panel__title">Nearby Musicians</h2>
                        <button class="kb-map-panel__button">Update Location</button>
                    </div>
                    <div id="map" class="kb-map-panel__map"></div>
                    <div id="map-users-data" data-users="' . $usersJson . '"></div>
                </aside>
            </div>
        </section>';

        parent::afficher();
    }
}