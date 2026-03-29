<?php
class User {
    
    private function existeDeja($colonne, $valeur) {
        $pdo = Database::getLink();
        $sql = "SELECT COUNT(*) FROM users WHERE $colonne = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$valeur]);
        return $stmt->fetchColumn() > 0;
    }

    private function isPasswordSecure($password) {
        // 8 caractères, 1 Majuscule, 1 Caractère spécial
        return strlen($password) >= 8 
               && preg_match('/[A-Z]/', $password) 
               && preg_match('/[^a-zA-Z0-9]/', $password);
    }

    public function inscrire($data, $files) {
        // 1. Nettoyage et formatage du pseudo
        $pseudo = trim($data['pseudo']);
        if (empty($pseudo)) return "Pseudo is required.";
        if ($pseudo[0] !== '@') $pseudo = '@' . $pseudo;
        $pseudo = str_replace(' ', '_', $pseudo);

        // 2. Vérification des doublons (Email & pseudp)
        if ($this->existeDeja('email', $data['email'])) {
            return "This email address is already registered.";
        }
        if ($this->existeDeja('pseudo', $pseudo)) {
            return "This pseudo is already taken.";
        }

        // 3. Vérification de la sécurité du mot de passe
        if (!$this->isPasswordSecure($data['pass'])) {
            return "Password insecure: 8 chars min, 1 uppercase and 1 special char required.";
        }

        // 4. GESTION DE L'IMAGE (Ce qui manquait dans ton code)
        $profile_pic = "default_user.jpg"; // Valeur par défaut
        if (!empty($files['profile_pic']['name'])) {
            $ext = pathinfo($files['profile_pic']['name'], PATHINFO_EXTENSION);
            $filename = time() . '.' . $ext;
            // Assure-toi que le dossier img/profiles existe !
            if (move_uploaded_file($files['profile_pic']['tmp_name'], "img/profiles/" . $filename)) {
                $profile_pic = $filename;
            }
        }

        // 5. Insertion finale
        try {
            $pdo = Database::getLink();
            $hash = password_hash($data['pass'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password, display_name, pseudo, bio, city, country, profile_pic, banner_pic, created_at) 
                    VALUES (:email, :pass, :name, :pseudo, :bio, :city, :country, :p_pic, 'default_banner.jpg', NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':email'   => htmlspecialchars($data['email']),
                ':pass'    => $hash,
                ':name'    => htmlspecialchars($data['display_name']),
                ':pseudo'  => htmlspecialchars($pseudo),
                ':bio'     => htmlspecialchars($data['bio'] ?? ''),
                ':city'    => htmlspecialchars($data['city'] ?? ''),
                ':country' => htmlspecialchars($data['country'] ?? ''),
                ':p_pic'   => $profile_pic
            ]);
            return "success";
        } catch (PDOException $e) {
            return "A database error occurred. Please try again later.";
        }
    }

    public function connecter($email, $mdp) {
        $pdo = Database::getLink();
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($mdp, $user['password'])) {
            // On stocke tout en session pour l'utiliser partout
            $_SESSION['user_id']       = $user['id'];
            $_SESSION['user_email']    = $user['email'];
            $_SESSION['display_name']  = $user['display_name'];
            $_SESSION['pseudo']        = $user['pseudo'];
            $_SESSION['bio']           = $user['bio'];
            $_SESSION['city']          = $user['city'];
            $_SESSION['country']       = $user['country'];
            $_SESSION['lat']           = $user['lat'];
            $_SESSION['lng']           = $user['lng'];
            $_SESSION['profile_pic']   = $user['profile_pic'];
            $_SESSION['banner_pic']    = $user['banner_pic'];
            $_SESSION['created_at']    = $user['created_at'];
            return true; 
        }
        return false; 
    }
}