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

        case 'updateProfile':
            if (isset($_SESSION['user_id'])) {
                $pdo = Database::getLink();
                $uid = $_SESSION['user_id']; // C'est ton "verrou" personnel
        
                // ⚠️ ATTENTION : Si tu oublies le WHERE, ça change TOUTE la table !
                $sql = "UPDATE users SET 
                        display_name = :name, 
                        bio = :bio, 
                        city = :city 
                        WHERE id = :id"; // <--- C'EST CETTE LIGNE LA PLUS IMPORTANTE
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $_POST['display_name'],
                    ':bio'  => $_POST['bio'],
                    ':city' => $_POST['city'],
                    ':id'   => $uid // On ne modifie QUE l'ID de la session
                ]);
        
                // GESTION DE L'AVATAR
                if (!empty($_FILES['profile_pic']['name'])) {
                    $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                    $path = "img/profiles/avatar_" . $uid . "." . $ext;
                    
                    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $path)) {
                        // SI LE FICHIER EST BIEN DEPLACÉ, ON UPDATE LA BDD
                        $pdo->prepare("UPDATE users SET profile_pic = :p WHERE id = :id")
                            ->execute([':p' => $path, ':id' => $uid]);
                    }
                }
        
                // GESTION DE LA BANNIÈRE
                if (!empty($_FILES['banner_pic']['name'])) {
                    $ext = pathinfo($_FILES['banner_pic']['name'], PATHINFO_EXTENSION);
                    $path = "img/banners/banner_" . $uid . "." . $ext;
                    
                    if (move_uploaded_file($_FILES['banner_pic']['tmp_name'], $path)) {
                        $pdo->prepare("UPDATE users SET banner_pic = :p WHERE id = :id")
                            ->execute([':p' => $path, ':id' => $uid]);
                    }
                }
            }
            header("Location: index.php?action=profile");
            exit();
    
    case 'notif':
        $vue = new VueNotif();
        $vue->afficher();
        break;

        case 'follow':
            $tab = $_GET['tab'] ?? 'all';
            $vue = new VueFollow([], $tab);
            $vue->afficher();
            break;

// --- ACTIONS AGENDA ---

case 'saveEvent':
    // C'est ici qu'on traite le formulaire (AJOUT ou UPDATE)
    if (isset($_SESSION['user_id'])) {
        $pdo = Database::getLink();
        
        // On concatène la date et l'heure pour le format BDD
        $start_date = $_POST['date'] . ' ' . $_POST['start_time'];
        $end_date = $_POST['date'] . ' ' . $_POST['end_time'];
        
        if (!empty($_POST['id'])) {
            // UPDATE (si l'id existe, on modifie)
            $sql = "UPDATE events SET title = :t, description = :d, start_date = :s, end_date = :e, visibility = :v 
                    WHERE id = :id AND creator_id = :uid";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':t'   => $_POST['title'],
                ':d'   => $_POST['description'],
                ':s'   => $start_date,
                ':e'   => $end_date,
                ':v'   => $_POST['visibility'],
                ':id'  => $_POST['id'],
                ':uid' => $_SESSION['user_id']
            ]);
        } else {
            // INSERT (si pas d'id, c'est un nouveau)
            $sql = "INSERT INTO events (creator_id, title, description, start_date, end_date, visibility) 
                    VALUES (:uid, :t, :d, :s, :e, :v)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':uid' => $_SESSION['user_id'],
                ':t'   => $_POST['title'],
                ':d'   => $_POST['description'],
                ':s'   => $start_date,
                ':e'   => $end_date,
                ':v'   => $_POST['visibility']
            ]);
        }
    }
    header("Location: index.php?action=agenda");
    exit();

case 'deleteEvent':
    // Suppression d'un événement
    if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
        $pdo = Database::getLink();
        $sql = "DELETE FROM events WHERE id = :id AND creator_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id'  => $_GET['id'],
            ':uid' => $_SESSION['user_id']
        ]);
    }
    header("Location: index.php?action=agenda");
    exit();

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