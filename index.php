<?php
// 1. Démarrage de la session (OBLIGATOIRE au tout début)
session_start();

// 2. L'autoloader (charge Database, User, VueBase, VueExplore, etc.)
// Assure-toi que ce fichier contient bien les "require_once" de tes classes
require_once 'inc/poo.inc.php';

// 3. On récupère l'action (par défaut 'explore')
$action = isset($_GET['action']) ? $_GET['action'] : 'explore';

// 4. On instancie le modèle User pour les futures vérifications/connexions
$userModel = new User();

// 5. LE SWITCH : C'est ici que tout se joue
switch ($action) {

    // --- VUES PRINCIPALES ---
    case 'explore':
        $vue = new VueExplore();
        $vue->setActionActive('explore'); // Allume le bouton Explore dans le menu
        $vue->afficher();
        break;

    case 'agenda':
        $vue = new VueAgenda();
        $vue->setActionActive('agenda'); // Allume le bouton Agenda dans le menu
        $vue->afficher();
        break;

    case 'profil':
        // Si tu as créé une VueProfil.class.php
        $vue = new VueProfil();
        $vue->setActionActive('profil');
        $vue->afficher();
        break;

    // --- AUTHENTIFICATION ---
    case 'connexion':
        $vue = new VueAuth();
        $vue->afficherConnexion();
        break;

        case 'inscription':
            $error = isset($_GET['error']) ? $_GET['error'] : null;
            $vue = new VueAuth();
            $vue->afficherInscription($error); // On passe l'erreur à la fonction
            break;
        
        case 'doRegister':
            $res = $userModel->inscrire($_POST, $_FILES);
            if ($res === "success") {
                header("Location: index.php?action=connexion&reg=ok");
            } else {
                // On renvoie vers le formulaire avec le texte de l'erreur dans l'URL
                header("Location: index.php?action=inscription&error=" . urlencode($res));
            }
            exit();

    case 'doConnect':
        if ($userModel->connecter($_POST['email'], $_POST['pass'])) {
            header("Location: index.php?action=explore");
        } else {
            header("Location: index.php?action=connexion&error=1");
        }
        exit();

    case 'logout':
        session_destroy();
        header("Location: index.php?action=connexion");
        exit();

    // --- PAR DÉFAUT ---
    default:
        $vue = new VueExplore();
        $vue->setActionActive('explore');
        $vue->afficher();
        break;
}