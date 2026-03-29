document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // 1. On retire la classe 'active' de TOUS les liens
            navLinks.forEach(l => l.classList.remove('active'));

            // 2. On ajoute la classe 'active' au lien cliqué
            this.classList.add('active');
            
            // Note : Si tes liens ont un "href" réel (ex: index.php?action=profil), 
            // la page va se recharger et c'est le PHP qui devra gérer l'état 'active'.
        });
    });
});