function switchFeed(view) {
    // 1. On récupère les éléments par leur ID
    const container = document.getElementById('posts-container');
    const btnAll = document.getElementById('btn-all');
    const btnFollowing = document.getElementById('id-btn-following'); // <--- Vérifie cet ID !

    if (!container) return; // Sécurité si la div n'existe pas

    // 2. Gestion visuelle des boutons
    if (view === 'all') {
        btnAll.classList.add('active-tab');
        btnFollowing.classList.remove('active-tab');
    } else {
        btnFollowing.classList.add('active-tab');
        btnAll.classList.remove('active-tab');
    }

    // 3. Appel AJAX
    container.style.opacity = "0.3"; // Petit effet visuel

    fetch(`ajax_get_posts.php?view=${view}`)
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html; // On remplace le contenu
            container.style.opacity = "1";
        })
        .catch(err => {
            console.error("Erreur AJAX :", err);
            container.style.opacity = "1";
        });
}