/**
 * 1. INITIALISATION & DONNÉES
 */
const rawData = window.dbEvents || [];
let currentDisplayDate = new Date(); // Date pivot pour la navigation (Mois en cours)

// On transforme les données SQL pour ton format JS
let events = rawData.map(e => {
  const dateTime = e.start_date.split(' '); 
  const endDateTime = e.end_date.split(' ');

  return {
    id: e.id,
    name: e.title,
    date: dateTime[0],
    startTime: dateTime[1] ? dateTime[1].substring(0, 5) : "00:00",
    endTime: endDateTime[1] ? endDateTime[1].substring(0, 5) : "00:00",
    type: e.visibility,
    description: e.description || ''
  };
});

// Au chargement de la page
document.addEventListener('DOMContentLoaded', initCalendar);

function initCalendar() {
  renderCalendarHeader();
  renderCalendarBody();
  renderUpcomingEvents();
}

/**
 * 2. NAVIGATION
 */
function changeMonth(direction) {
  // direction: -1 pour précédent, 1 pour suivant
  currentDisplayDate.setMonth(currentDisplayDate.getMonth() + direction);
  renderCalendarBody();
}

/**
 * 3. RENDU DU CALENDRIER
 */
function renderCalendarHeader() {
  const calHead = document.getElementById('calHead');
  const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  calHead.innerHTML = '';
  days.forEach(day => {
    const cell = document.createElement('div');
    cell.className = 'cal-head';
    cell.textContent = day;
    calHead.appendChild(cell);
  });
}

function renderCalendarBody() {
  const calBody = document.getElementById('calBody');
  if (!calBody) return;

  const year = currentDisplayDate.getFullYear();
  const month = currentDisplayDate.getMonth();
  
  // Mise à jour du titre du mois
  const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                      'July', 'August', 'September', 'October', 'November', 'December'];
  document.getElementById('monthTitle').textContent = `${monthNames[month]} ${year}`;
  
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const daysInPrevMonth = new Date(year, month, 0).getDate();
  
  calBody.innerHTML = '';

  // Jours du mois précédent pour remplir la grille
  const startDay = firstDay === 0 ? 6 : firstDay - 1;
  for (let i = startDay - 1; i >= 0; i--) {
    const cell = document.createElement('div');
    cell.className = 'cal-cell other-month';
    cell.innerHTML = `<span class="day-num">${daysInPrevMonth - i}</span>`;
    calBody.appendChild(cell);
  }
  
  // Jours du mois en cours
  for (let day = 1; day <= daysInMonth; day++) {
    const cell = document.createElement('div');
    cell.className = 'cal-cell';
    
    // Highlight "Today"
    const today = new Date();
    if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
      cell.classList.add('today');
    }
    
    cell.innerHTML = `<span class="day-num">${day}</span>`;
    
    // Filtrage des événements pour ce jour
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const dayEvents = events.filter(e => e.date === dateStr);
    
    dayEvents.forEach(event => {
      const eventEl = document.createElement('span');
      eventEl.className = `cal-event ${event.type}`;
      eventEl.textContent = event.name.substring(0, 12);
      eventEl.onclick = (e) => { e.stopPropagation(); openModal(event.id); };
      cell.appendChild(eventEl);
    });
    
    calBody.appendChild(cell);
  }
  
  // Remplissage de la fin de la grille (42 cases au total)
  const totalCells = calBody.children.length;
  const remainingCells = 42 - totalCells;
  for (let i = 1; i <= remainingCells; i++) {
    const cell = document.createElement('div');
    cell.className = 'cal-cell other-month';
    cell.innerHTML = `<span class="day-num">${i}</span>`;
    calBody.appendChild(cell);
  }
}

/**
 * 4. LISTE DES ÉVÉNEMENTS À VENIR
 */
function renderUpcomingEvents() {
  const eventsList = document.getElementById('eventsList');
  if(!eventsList) return;
  eventsList.innerHTML = '';
  
  // On ne garde que les événements à partir d'aujourd'hui et on trie
  const sortedEvents = [...events].sort((a, b) => new Date(a.date) - new Date(b.date));
  
  sortedEvents.forEach(event => {
    const dateObj = new Date(event.date);
    const day = dateObj.getDate();
    const monthShort = dateObj.toLocaleDateString('en-US', { month: 'short' });
    
    const eventCard = document.createElement('div');
    eventCard.className = 'event-card';
    eventCard.style.cursor = "pointer";
    eventCard.onclick = () => openModal(event.id);

    eventCard.innerHTML = `
      <div class="event-date-badge">
        <div class="day">${day}</div>
        <div class="month">${monthShort}</div>
      </div>
      <div class="event-info">
        <div class="event-name">${event.name}</div>
        <div class="event-time">${event.startTime} - ${event.endTime}</div>
        <div class="event-desc">${event.description}</div>
        <span class="event-tag ${event.type}">${event.type}</span>
      </div>
      <button class="btn-edit">Edit</button>
    `;
    eventsList.appendChild(eventCard);
  });
}

/**
 * 5. GESTION DE LA MODAL (EDIT / ADD / DELETE)
 */
function openModal(eventId = null) {
  const overlay = document.getElementById('modalOverlay');
  const title = document.getElementById('modalTitle');
  const btnDelete = document.getElementById('btn-delete');
  const form = document.getElementById('eventForm');

  overlay.style.display = 'flex';

  if (eventId) {
    const event = events.find(e => e.id == eventId);
    if (!event) return;

    title.textContent = "Edit Event";
    document.getElementById('inp-id').value = event.id;
    document.getElementById('inp-name').value = event.name;
    document.getElementById('inp-desc').value = event.description;
    document.getElementById('inp-date').value = event.date;
    document.getElementById('inp-type').value = event.type;
    document.getElementById('inp-start').value = event.startTime;
    document.getElementById('inp-end').value = event.endTime;
    
    if(btnDelete) btnDelete.style.display = 'block';
    form.action = "index.php?action=saveEvent"; // L'action PHP gère l'update si l'ID est présent
  } else {
    title.textContent = "New Event";
    form.reset();
    document.getElementById('inp-id').value = "";
    if(btnDelete) btnDelete.style.display = 'none';
    form.action = "index.php?action=saveEvent";
  }
}

function closeModal() {
  document.getElementById('modalOverlay').style.display = 'none';
}

function deleteEvent() {
  const id = document.getElementById('inp-id').value;
  if (confirm("Delete this event forever?")) {
    window.location.href = `index.php?action=deleteEvent&id=${id}`;
  }
}