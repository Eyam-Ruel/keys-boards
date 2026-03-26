<?php
class VueAuth {

    public function afficherConnexion() {
        echo $this->getEntete("Sign In - LinkUp");
        echo '
        <div class="auth-container">
            <div class="auth-box">
                <a href="index.php" class="back-home">< Back to home</a>
                <h1 class="auth-logo">LinkUp</h1>
                <p class="auth-subtitle">Sign in to your account</p>
                
                <form action="index.php?action=doConnect" method="post" class="auth-form">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="yourname@example.com" required>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="pass" placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="auth-btn">Sign In</button>
                </form>
                
                <p class="auth-switch">Don\'t have an account? <a href="index.php?action=inscription">Create account</a></p>
            </div>
        </div>';
        echo $this->getPied();
    }

    public function afficherInscription($error = null) {
        echo $this->getEntete("Join LinkUp");
        echo '
        <div class="auth-container">
            <div class="auth-box" style="max-width: 600px;">
                <a href="index.php" class="back-home">< Back to home</a>
                <h1 class="auth-logo">LinkUp</h1>
                
                ' . ($error ? '<p style="color: #810F29; background: #FFF0F0; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; border: 1px solid #FFDADA;">⚠️ ' . htmlspecialchars($error) . '</p>' : '') . '

                <p class="auth-subtitle">Join the intergenerational music community</p>
                
                <form action="index.php?action=doRegister" method="post" enctype="multipart/form-data" class="auth-form">
                    
                    <h3 class="form-section-title">Credentials</h3>
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="yourname@example.com" required>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="pass" placeholder="••••••••" required>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div class="input-group" style="flex: 1;">
                            <label>Display name</label>
                            <input type="text" name="display_name" placeholder="e.g., Jean Dupont" required>
                        </div>
                        <div class="input-group" style="flex: 1;">
                            <label>Handle</label>
                            <input type="text" name="pseudo" placeholder="e.g., jeandupont" required>
                        </div>
                    </div>

                    <h3 class="form-section-title">Profile & Bio</h3>
                    <div class="input-group">
                        <label>Tell us about yourself</label>
                        <textarea name="bio" rows="3" placeholder="Share your musical journey, favorite genres, instruments you play..."></textarea>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div class="input-group" style="flex: 1;">
                            <label>City</label>
                            <input type="text" name="city" placeholder="Paris">
                        </div>
                        <div class="input-group" style="flex: 1;">
                            <label>Country</label>
                            <input type="text" name="country" placeholder="France">
                        </div>
                    </div>

                    <h3 class="form-section-title">Appearance</h3>
                    <div class="input-group">
                        <label>Profile photo</label>
                        <input type="file" name="profile_pic" accept="image/*">
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="terms" id="terms" required> 
                        <label for="terms">I accept the terms and conditions and the privacy policy.</label>
                    </div>
                    
                    <button type="submit" class="auth-btn">Create my profile</button>
                </form>
                
                <p class="auth-switch">Already have an account? <a href="index.php?action=connexion">Sign in</a></p>
            </div>
        </div>';
        echo $this->getPied();
    }

    private function getEntete($titre) {
        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>' . $titre . '</title>
            <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="css/style.css">
        </head>
        <body class="auth-body">';
    }

    private function getPied() {
        return '</body></html>';
    }
}