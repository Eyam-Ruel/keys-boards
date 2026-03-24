function navigate(el) {
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  el.classList.add('active');
}

function switchTab(name, el) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('tab-' + name).classList.add('active');
}

function openEditModal() {
  document.getElementById('editModal').classList.add('open');
}

function closeEditModal() {
  document.getElementById('editModal').classList.remove('open');
}

function saveProfile() {
  const name  = document.getElementById('edit-name').value.trim();
  const handle = document.getElementById('edit-handle').value.trim();
  const bio   = document.getElementById('edit-bio').value.trim();
  const loc   = document.getElementById('edit-location').value.trim();
  const langs = document.getElementById('edit-langs').value.trim();

  if (name) document.querySelector('.profile-name').textContent = name;
  if (handle) document.getElementById('profileHandle').textContent = handle;
  if (bio) document.getElementById('profileBio').textContent = bio;
  if (loc) document.getElementById('metaLocation').lastChild.textContent = ' ' + loc;
  if (langs) document.getElementById('metaLangs').lastChild.textContent = ' ' + langs;

  closeEditModal();
}

document.getElementById('editModal').addEventListener('click', function(e) {
  if (e.target === this) closeEditModal();
});