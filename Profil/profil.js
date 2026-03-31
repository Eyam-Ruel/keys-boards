// profil.js — Profile page interactions + Nominatim address autocomplete

// ── Tab switching ─────────────────────────────────────────────────────────────
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

// ── Edit modal ────────────────────────────────────────────────────────────────
function openEditModal() {
  document.getElementById('editModal').classList.add('open');
}

function closeEditModal() {
  document.getElementById('editModal').classList.remove('open');
}

// Close modal on backdrop click
document.getElementById('editModal').addEventListener('click', function (e) {
  if (e.target === this) closeEditModal();
});

// Update visible profile info on form submit (optimistic UI update)
document.getElementById('edit-profile-form').addEventListener('submit', function () {
  const name     = document.getElementById('edit-name').value.trim();
  const handle   = document.getElementById('edit-handle').value.trim();
  const bio      = document.getElementById('edit-bio').value.trim();
  const location = document.getElementById('address-search').value.trim();
  const langs    = document.getElementById('edit-langs').value.trim();

  if (name)   document.getElementById('profileName').textContent   = name;
  if (handle) document.getElementById('profileHandle').textContent = handle;
  if (bio)    document.getElementById('profileBio').textContent    = bio;

  if (location) {
    document.getElementById('metaLocation').innerHTML =
      `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
        <circle cx="12" cy="10" r="3"/>
      </svg> ${location}`;
  }

  if (langs) {
    document.getElementById('metaLangs').innerHTML =
      `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="2" y1="12" x2="22" y2="12"/>
        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
      </svg> ${langs}`;
  }
});

// ── Nominatim address autocomplete ────────────────────────────────────────────
// Run directly (no DOMContentLoaded) — script loads after PHP injects the modal HTML

(function initNominatim() {
  const addressInput  = document.getElementById("address-search");
  const suggestionBox = document.getElementById("suggestions");
  const latInput      = document.getElementById("lat");
  const lngInput      = document.getElementById("lng");

  if (!addressInput) return; // exit if field not present

  let debounceTimer = null;

  // Trigger search after 300ms pause in typing
  addressInput.addEventListener("input", () => {
    clearTimeout(debounceTimer);
    const query = addressInput.value.trim();

    if (query.length < 3) {
      clearSuggestions();
      return;
    }

    debounceTimer = setTimeout(() => fetchSuggestions(query), 300);
  });

  // Fetch suggestions from Nominatim API
  async function fetchSuggestions(query) {
    try {
      const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=5&addressdetails=1`;
      const response = await fetch(url, {
        headers: { "Accept-Language": "fr,en" }
      });

      if (!response.ok) throw new Error("Nominatim request failed");

      const results = await response.json();
      displaySuggestions(results);

    } catch (err) {
      console.error("Nominatim fetch failed:", err);
      clearSuggestions();
    }
  }

  // Render suggestion dropdown
  function displaySuggestions(results) {
    clearSuggestions();

    if (results.length === 0) {
      const li = document.createElement("li");
      li.textContent = "No results found.";
      li.className = "suggestion-empty";
      suggestionBox.appendChild(li);
      suggestionBox.style.display = "block";
      return;
    }

    results.forEach(place => {
      const li = document.createElement("li");
      li.textContent = place.display_name;
      li.className = "suggestion-item";

      li.addEventListener("click", () => {
        // Fill address and save lat/lng into hidden inputs
        // DB columns: lat, lng — Nominatim returns "lon", mapped to "lng"
        addressInput.value = place.display_name;
        latInput.value     = place.lat;
        lngInput.value     = place.lon;
        clearSuggestions();

        // Green border confirms selection
        addressInput.classList.add("address-confirmed");
        setTimeout(() => addressInput.classList.remove("address-confirmed"), 1500);
      });

      suggestionBox.appendChild(li);
    });

    suggestionBox.style.display = "block";
  }

  // Close dropdown on outside click
  document.addEventListener("click", (e) => {
    if (!addressInput.contains(e.target) && !suggestionBox.contains(e.target)) {
      clearSuggestions();
    }
  });

  function clearSuggestions() {
    suggestionBox.innerHTML = "";
    suggestionBox.style.display = "none";
  }

})(); // IIFE — runs immediately, no need to wait for DOMContentLoaded