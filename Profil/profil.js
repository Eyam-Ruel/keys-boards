// ===== TABS & NAVIGATION =====

// Switch between tabs
function switchTab(tabName) {
  // Hide all tab contents
  document.querySelectorAll('.tab-content').forEach(content => 
    content.classList.remove('active')
  );

  // Remove active class from all buttons
  document.querySelectorAll('.tab-btn').forEach(button => 
    button.classList.remove('active')
  );

  // Show selected tab content & activate button
  const selectedContent = document.getElementById(`${tabName}-section`);
  if (selectedContent) {
    selectedContent.classList.add('active');
    event.target.classList.add('active');
  }
}

// Sidebar navigation
function navigate(element) {
  document.querySelectorAll('.nav-item').forEach(el => 
    el.classList.remove('active')
  );
  element.classList.add('active');
}

// ===== EDIT PROFILE MODAL =====

function openEditModal() {
  const modal = document.getElementById('edit-profile-modal');
  if (!modal) return;
  
  modal.style.display = 'flex';
  // Trigger animation after display is set
  setTimeout(() => modal.classList.add('active'), 10);
  document.body.style.overflow = 'hidden'; // Prevent background scroll
  updateCharCount();
}

function closeEditModal() {
  const modal = document.getElementById('edit-profile-modal');
  if (!modal) return;
  
  modal.classList.remove('active');
  // Wait for animation to finish before hiding
  setTimeout(() => { modal.style.display = 'none'; }, 200);
  document.body.style.overflow = '';
}

// Modal: Close on overlay click
document.getElementById('edit-profile-modal')?.addEventListener('click', (e) => {
  if (e.target.id === 'edit-profile-modal') closeEditModal();
});

// Modal: Close on Escape key
document.addEventListener('keydown', (e) => {
  const modal = document.getElementById('edit-profile-modal');
  if (e.key === 'Escape' && modal?.style.display === 'flex') {
    closeEditModal();
  }
});

// ===== FORM UTILITIES =====

// Character counter for bio textarea
function updateCharCount() {
  const bio = document.getElementById('edit-bio');
  const counter = document.getElementById('char-count');
  if (!bio || !counter) return;
  
  const count = bio.value.length;
  counter.textContent = `${count}/500 characters`;
  counter.style.color = count > 450 ? 'var(--error)' : 'var(--muted)';
}

// File upload preview handler
function setupFilePreview(inputId, infoId) {
  const input = document.getElementById(inputId);
  const info = document.getElementById(infoId);
  if (!input || !info) return;
  
  input.addEventListener('change', function() {
    if (this.files?.[0]) {
      info.textContent = `✓ ${this.files[0].name}`;
      info.style.color = 'var(--success)';
    }
  });
}

// ===== TAGS MANAGEMENT =====

function removeTag(btn) {
  btn.parentElement?.remove();
}

function addTag() {
  const input = document.getElementById('tag-input');
  const container = document.getElementById('tags-container');
  if (!input || !container) return;
  
  let value = input.value.trim();
  if (!value) return;
  
  // Auto-add # if missing
  if (!value.startsWith('#')) value = '#' + value;
  
  const tag = document.createElement('span');
  tag.className = 'tag';
  tag.innerHTML = `${value} <button type="button" class="tag-remove" onclick="removeTag(this)">×</button>`;
  container.appendChild(tag);
  input.value = '';
  input.focus();
}

// ===== TOAST NOTIFICATIONS =====

function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.style.cssText = `
    position: fixed;
    bottom: 24px;
    right: 24px;
    padding: 14px 24px;
    background: ${type === 'success' ? 'var(--success)' : 'var(--error)'};
    color: white;
    border-radius: var(--radius-sm);
    font-weight: 500;
    font-family: var(--font);
    box-shadow: var(--shadow-lg);
    z-index: 2000;
    animation: toastSlide 0.3s ease;
  `;
  toast.textContent = message;
  document.body.appendChild(toast);
  
  // Inject animation keyframes if not already present
  if (!document.getElementById('toast-anim')) {
    const style = document.createElement('style');
    style.id = 'toast-anim';
    style.textContent = `
      @keyframes toastSlide {
        from { transform: translateX(100px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
      }
    `;
    document.head.appendChild(style);
  }
  
  // Auto-dismiss with fade out
  setTimeout(() => {
    toast.style.animation = 'toastSlide 0.3s ease reverse';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// ===== FORM SUBMISSION =====

function handleProfileUpdate(e) {
  e.preventDefault();
  
  // Collect form data safely
  const getData = (id) => document.getElementById(id)?.value || '';
  
  const data = {
    displayName: getData('edit-display-name'),
    username: getData('edit-username'),
    bio: getData('edit-bio'),
    city: getData('edit-city'),
    country: getData('edit-country'),
  };
  
  // Update profile UI in real-time
  if (data.displayName) document.querySelector('.profile-name').textContent = data.displayName;
  if (data.username) document.querySelector('.profile-title').textContent = `@${data.username}`;
  if (data.bio) document.querySelector('.profile-bio').textContent = data.bio;
  if (data.city && data.country) {
    const locationSpan = document.querySelector('.profile-meta span:first-child');
    if (locationSpan) locationSpan.innerHTML = `📍 ${data.city}, ${data.country}`;
  }
  
  // Close modal & show feedback
  closeEditModal();
  showToast('✅ Profile updated successfully!', 'success');
  
  // 🔥 TODO: Send to backend
  // fetch('/api/profile', {
  //   method: 'PUT',
  //   headers: { 'Content-Type': 'application/json' },
  //   body: JSON.stringify(data)
  // });
}

// ===== INITIALIZATION =====

document.addEventListener('DOMContentLoaded', () => {
  // Activate first tab by default
  document.querySelector('.tab-btn')?.classList.add('active');
  
  // Initialize file previews
  setupFilePreview('banner-upload', 'banner-info');
  setupFilePreview('avatar-upload', 'avatar-info');
  
  // Bind bio character counter
  document.getElementById('edit-bio')?.addEventListener('input', updateCharCount);
  
  // Bind tag input: Enter key to add
  document.getElementById('tag-input')?.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      addTag();
    }
  });
  
  // Bind form submission
  document.getElementById('edit-profile-form')?.addEventListener('submit', handleProfileUpdate);
});