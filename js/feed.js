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

function openPostModal() {
    const modal = document.getElementById('postModal');
    if (!modal) return;
    modal.classList.remove('hide');
    modal.classList.add('show');
}

function closePostModal() {
    const modal = document.getElementById('postModal');
    if (!modal) return;
    modal.classList.remove('show');
    modal.classList.add('hide');
}

function resetCreatePostForm() {
    const form = document.getElementById('createPostForm');
    const message = document.getElementById('postMessage');
    if (form) form.reset();
    if (message) {
        message.textContent = '';
        message.className = 'post-message';
    }
}

function displayPostMessage(text, error = false) {
    const message = document.getElementById('postMessage');
    if (!message) return;
    message.textContent = text;
    message.className = error ? 'post-message error' : 'post-message success';
}

document.addEventListener('DOMContentLoaded', function() {
    const openButton = document.getElementById('openPostModal');
    const closeButton = document.getElementById('closePostModal');
    const cancelButton = document.getElementById('cancelPost');
    const form = document.getElementById('createPostForm');

    if (openButton) openButton.addEventListener('click', openPostModal);
    if (closeButton) closeButton.addEventListener('click', closePostModal);
    if (cancelButton) {
        cancelButton.addEventListener('click', function() { closePostModal(); resetCreatePostForm(); });
    }

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const content = document.getElementById('postContent').value.trim();
            if (!content) {
                displayPostMessage('Le contenu est requis pour publier.', true);
                return;
            }

            const formData = new FormData(form);
            fetch('ajax_create_post.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayPostMessage('Publication réussie !', false);
                    resetCreatePostForm();
                    closePostModal();
                    switchFeed('all');
                } else {
                    displayPostMessage(data.error || 'Erreur lors de la publication.', true);
                }
            })
            .catch(err => {
                console.error('Erreur création post :', err);
                displayPostMessage('Erreur serveur, réessayez plus tard.', true);
            });
        });
    }
});