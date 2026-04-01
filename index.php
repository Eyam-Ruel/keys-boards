<?php

session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$langue = $_SESSION['lang'] ?? 'en';
$chemin_fichier = __DIR__ . "/lang/" . $langue . ".php";

if (file_exists($chemin_fichier)) {
    $GLOBALS['trad'] = require $chemin_fichier;
} else {
    $GLOBALS['trad'] = require __DIR__ . "/lang/en.php";
}

$trad = $GLOBALS['trad']; 

require_once 'inc/poo.inc.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'home';

$userModel = new User();

switch ($action) {
    case 'explore':
        $vue = new VueExplore();
        $vue->setActionActive('explore'); 
        $vue->afficher();
        break;

    case 'agenda':
        $vue = new VueAgenda();
        $vue->setActionActive('agenda'); 
        $vue->afficher();
        break;

    case 'profil':
        $vue = new VueProfil();
        $vue->setActionActive('profil');
        $vue->afficher();
        break;

case 'feed':
    $vue = new VueFeed(); // On utilise notre nouvelle vue "Feed"
    $vue->afficher();
    break;

    case 'messages':
        $vue = new VueMessages();
        $vue->afficher();
        break;

    case 'connexion':
        $vue = new VueAuth();
        $vue->afficherConnexion();
        break;


        case 'profile':
            $vue = new VueProfil();
            $vue->afficher();
            break;

    case 'inscription':
        $error = isset($_GET['error']) ? $_GET['error'] : null;
        $vue = new VueAuth();
        $vue->afficherInscription($error); 
        break;

    case 'doRegister':
        $res = $userModel->inscrire($_POST, $_FILES);
        if ($res === "success") {
            header("Location: index.php?action=connexion&reg=ok");
        } else {
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
        
    case 'doGoogleLogin':
            // On récupère les données envoyées par le JavaScript (FormData)
            $data = [
                'email'   => $_POST['email'] ?? '',
                'name'    => $_POST['name'] ?? '',
                'picture' => $_POST['picture'] ?? ''
            ];
        
            if (!empty($data['email'])) {
                $userObj = new User();
                $userObj->connecterViaGoogle($data);
                // Pas besoin de redirection ici car le JavaScript s'en occupe déjà 
                // avec window.location.href après le fetch
                echo "success"; 
            }
            break;

    case 'logout':
        session_destroy();
        header("Location: index.php?action=connexion");
        exit();

    case 'legal':
        $vue = new VueLegal();
        $vue->afficher();
        break;

    case 'terms':
        $vue = new VueTerms();
        $vue->afficher();
        break;

    case 'privacy':
        $vue = new VuePrivacy();
        $vue->afficher();
        break;
            
    case 'home':
        $vue = new VueHome();
        $vue->afficher();
        break;

    case 'faq':
        $vue = new VueFAQ();
        $vue->afficher();
        break;
            
    case 'about':
        $vue = new VueAbout();
        $vue->afficher();
        break;
            
    default:
        $vue = new VueHome();
        $vue->afficher();
        break;
        
}