<?php

class VueFollow extends VueBase
{
    private $tab;

    public function __construct($users = [], $tab = 'all')
    {
        parent::__construct("Connect - LinkUp");
        $this->tab = $tab;
        $this->actionActive = 'follow'; 
    }

    private function getRealMusicians()
    {
        $pdo = Database::getLink();
        // On récupère les infos des utilisateurs pour remplir les cartes
        // Tu pourras affiner la requête plus tard pour filtrer par "following"
        $sql = "SELECT id, display_name, pseudo, bio, city, country, profile_pic FROM users";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function afficher()
    {
        // Récupération des vraies données
        $musiciansFromDb = $this->getRealMusicians();

        $allActive = $this->tab === 'all' ? 'kb-explore-tabs__item--active' : '';
        $followingActive = $this->tab === 'following' ? 'kb-explore-tabs__item--active' : '';
        $suggestionsActive = $this->tab === 'suggestions' ? 'kb-explore-tabs__item--active' : '';

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/follow.css">

        <section class="kb-explore-page">
            <div class="kb-page-heading">
                <h1 class="kb-page-heading__title">Discover Musicians</h1>
                <p class="kb-page-heading__subtitle">Find musicians, producers and collaborators near you.</p>
            </div>

            <div class="kb-explore-tabs">
                <a href="index.php?action=follow&tab=all" class="kb-explore-tabs__item <?php echo $allActive; ?>">All</a>
                <a href="index.php?action=follow&tab=following" class="kb-explore-tabs__item <?php echo $followingActive; ?>">Following</a>
                <a href="index.php?action=follow&tab=suggestions" class="kb-explore-tabs__item <?php echo $suggestionsActive; ?>">Suggestions</a>
            </div>

            <div class="kb-explore-layout">
                <div class="kb-explore-grid">
                    <?php foreach ($musiciansFromDb as $m): 
                        // On définit quelques valeurs par défaut si les champs sont vides
                        $name = htmlspecialchars($m['display_name']);
                        $pseudo = "@" . htmlspecialchars($m['pseudo']);
                        $location = htmlspecialchars($m['city'] . ", " . $m['country']);
                        $bio = htmlspecialchars($m['bio'] ?? "No bio available yet.");
                        $avatar = !empty($m['profile_pic']) ? $m['profile_pic'] : 'img/ppMan.png';
                        
                        // Pour l'instant, on laisse le bouton en "Follow" par défaut
                        $buttonText = "Follow";
                        $buttonClass = 'kb-follow-button';
                    ?>
                        <article class="kb-musician-card">
                            <div class="kb-musician-card__top">
                                <div class="kb-musician-card__identity">
                                    <div class="kb-musician-card__avatar" style="background-image: url('<?php echo $avatar; ?>'); background-size: cover;"></div>
                                    <div class="kb-musician-card__meta">
                                        <h3 class="kb-musician-card__name"><?php echo $name; ?></h3>
                                        <p class="kb-musician-card__username"><?php echo $pseudo; ?></p>
                                        <p class="kb-musician-card__location"><?php echo $location; ?></p>
                                    </div>
                                </div>
                                <button class="<?php echo $buttonClass; ?>"><?php echo $buttonText; ?></button>
                            </div>

                            <p class="kb-musician-card__description"><?php echo $bio; ?></p>

                            <div class="kb-musician-card__tags">
                                <span class="kb-tag">#Music</span>
                                <span class="kb-tag">#LinkUp</span>
                            </div>

                            <p class="kb-musician-card__followers">0 followers</p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}