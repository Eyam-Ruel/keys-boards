document.addEventListener("DOMContentLoaded", function () {
  console.log("script.js loaded");

  const navLinks = document.querySelectorAll(".nav-link");
  navLinks.forEach(link => {
    link.addEventListener("click", function () {
      navLinks.forEach(l => l.classList.remove("active"));
      this.classList.add("active");
    });
  });

  const mapContainer = document.getElementById("map");
  console.log("map container:", mapContainer);

  if (mapContainer) {
    const map = L.map("map").setView([41.3275, 19.8187], 10);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      maxZoom: 18,
      attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map);

    L.marker([41.3275, 19.8187]).addTo(map)
      .bindPopup("Tirana - Test Marker")
      .openPopup();
  }
});