/**
 * ===== TABS & NAVIGATION =====
 * Gère l'affichage des onglets (Posts, Events, Media)
 */
function switchTab(tabName) {
  // 1. Cache tous les contenus
  document.querySelectorAll('.tab-content').forEach(content => 
    content.classList.remove('active')
  );

  // 2. Désactive tous les boutons
  document.querySelectorAll('.tab-btn').forEach(button => 
    button.classList.remove('active')
  );

  // 3. Affiche le contenu cliqué et active le bouton
  const selectedContent = document.getElementById(`${tabName}-section`);
  if (selectedContent) {
    selectedContent.classList.add('active');
    // On utilise event.currentTarget pour être sûr de cibler le bouton
    if (window.event) window.event.currentTarget.classList.add('active');
  }
}

/**
 * ===== GESTION DE LA MODAL ÉDITION =====
 */
function openEditModal() {
  const modal = document.getElementById('edit-profile-modal');
  if (!modal) return;
  
  modal.style.display = 'flex';
  // Petit délai pour laisser l'animation CSS se déclencher
  setTimeout(() => modal.classList.add('active'), 10);
  document.body.style.overflow = 'hidden'; 
}

function closeEditModal() {
  const modal = document.getElementById('edit-profile-modal');
  if (!modal) return;
  
  modal.classList.remove('active');
  setTimeout(() => { modal.style.display = 'none'; }, 200);
  document.body.style.overflow = '';
}

// Fermer si on clique à côté de la modal
document.getElementById('edit-profile-modal')?.addEventListener('click', (e) => {
  if (e.target.id === 'edit-profile-modal') closeEditModal();
});

/**
 * ===== PREVIEW DES FICHIERS (AVATAR/BANNER) =====
 */
function setupFilePreview(inputId, infoId) {
  const input = document.getElementById(inputId);
  const info = document.getElementById(infoId);
  if (!input || !info) return;
  
  input.addEventListener('change', function() {
    if (this.files?.[0]) {
      info.textContent = `✓ ${this.files[0].name}`;
      info.style.color = '#27ae60'; // Vert succès
    }
  });
}

/**
 * ===== SOUMISSION DU FORMULAIRE =====
 * On laisse le PHP gérer la sauvegarde, mais on affiche un Toast
 */
function handleProfileUpdate(e) {
  // On ne fait PAS e.preventDefault() ! 
  // On veut que le formulaire parte vers index.php?action=updateProfile
  
  showToast('Saving changes to server...', 'success');
  
  // La page va se recharger toute seule après le traitement PHP
}

/**
 * ===== TOAST NOTIFICATIONS =====
 */
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.style.cssText = `
    position: fixed;
    bottom: 24px;
    right: 24px;
    padding: 14px 24px;
    background: ${type === 'success' ? '#810F29' : '#e74c3c'};
    color: white;
    border-radius: 8px;
    font-weight: 500;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  `;
  toast.textContent = message;
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = '0.5s';
    setTimeout(() => toast.remove(), 500);
  }, 3000);
}

/**
 * ===== INITIALISATION =====
 */
document.addEventListener('DOMContentLoaded', () => {
  // Initialisation des previews d'images
  setupFilePreview('banner-upload', 'banner-info');
  setupFilePreview('avatar-upload', 'avatar-info');
  
  // On lie la fonction de soumission au formulaire
  const editForm = document.getElementById('edit-profile-form');
  if (editForm) {
    editForm.addEventListener('submit', handleProfileUpdate);
  }
});