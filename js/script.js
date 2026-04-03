// On attend que toute la page soit chargée avant d'exécuter le script
document.addEventListener('DOMContentLoaded', () => {
    // On récupère tous les liens de navigation
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        // On écoute le clic sur chaque lien
        link.addEventListener('click', function(e) {
            
            // 1. On nettoie en retirant la classe 'active' de TOUS les liens
            navLinks.forEach(l => l.classList.remove('active'));

            // 2. On applique la classe 'active' uniquement sur le lien qui vient d'être cliqué
            this.classList.add('active');
            
            // Note : Si tes liens ont un "href" qui fait changer de page (ex: index.php?action=profil), 
            // le navigateur va recharger la page, et c'est ton PHP qui devra redéfinir le bon lien actif au chargement.
        });
    });
});