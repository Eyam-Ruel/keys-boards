// Sample events data
const events = [
  {
    id: 1,
    name: 'Jazz Piano Session',
    date: '2026-03-26',
    startTime: '15:00',
    endTime: '21:00',
    type: 'public',
    description: 'Live jazz piano session open to all'
  },
  {
    id: 2,
    name: 'Guitar Practice',
    date: '2026-03-28',
    startTime: '14:00',
    endTime: '16:00',
    type: 'private',
    description: 'Personal practice session'
  },
  {
    id: 3,
    name: 'Jam Session with Sophie',
    date: '2026-03-30',
    startTime: '18:00',
    endTime: '20:00',
    type: 'shared',
    description: 'Collaborative jam session'
  }
];

// Initialize calendar
function initCalendar() {
  renderCalendarHeader();
  renderCalendarBody();
  renderUpcomingEvents();
}

// Render calendar header (days of week)
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

// Render calendar body (days)
function renderCalendarBody() {
  const calBody = document.getElementById('calBody');
  const currentDate = new Date(2026, 2, 1); // March 2026
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  
  // Update month title
  const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                      'July', 'August', 'September', 'October', 'November', 'December'];
  document.getElementById('monthTitle').textContent = `${monthNames[month]} ${year}`;
  
  // Get first day and number of days
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const daysInPrevMonth = new Date(year, month, 0).getDate();
  
  calBody.innerHTML = '';
  
  // Previous month days
  const startDay = firstDay === 0 ? 6 : firstDay - 1;
  for (let i = startDay - 1; i >= 0; i--) {
    const cell = document.createElement('div');
    cell.className = 'cal-cell other-month';
    cell.innerHTML = `<span class="day-num">${daysInPrevMonth - i}</span>`;
    calBody.appendChild(cell);
  }
  
  // Current month days
  for (let day = 1; day <= daysInMonth; day++) {
    const cell = document.createElement('div');
    cell.className = 'cal-cell';
    
    // Highlight today
    const today = new Date();
    if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
      cell.classList.add('today');
    }
    
    // Add day number
    cell.innerHTML = `<span class="day-num">${day}</span>`;
    
    // Add events for this day
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const dayEvents = events.filter(e => e.date === dateStr);
    
    dayEvents.forEach(event => {
      const eventEl = document.createElement('span');
      eventEl.className = `cal-event ${event.type}`;
      eventEl.textContent = event.name.substring(0, 12);
      cell.appendChild(eventEl);
    });
    
    calBody.appendChild(cell);
  }
  
  // Next month days
  const totalCells = calBody.children.length;
  const remainingCells = 42 - totalCells;
  for (let i = 1; i <= remainingCells; i++) {
    const cell = document.createElement('div');
    cell.className = 'cal-cell other-month';
    cell.innerHTML = `<span class="day-num">${i}</span>`;
    calBody.appendChild(cell);
  }
}

// Render upcoming events list
function renderUpcomingEvents() {
  const eventsList = document.getElementById('eventsList');
  eventsList.innerHTML = '';
  
  // Sort events by date
  const sortedEvents = [...events].sort((a, b) => new Date(a.date) - new Date(b.date));
  
  sortedEvents.forEach(event => {
    const date = new Date(event.date);
    const day = date.getDate();
    const monthShort = date.toLocaleDateString('en-US', { month: 'short' });
    const time = `${event.startTime} - ${event.endTime}`;
    
    const eventCard = document.createElement('div');
    eventCard.className = 'event-card';
    
    eventCard.innerHTML = `
      <div class="event-date-badge">
        <div class="day">${day}</div>
        <div class="month">${monthShort}</div>
      </div>
      <div class="event-info">
        <div class="event-name">${event.name}</div>
        <div class="event-time">${time}</div>
        <div class="event-desc">${event.description}</div>
        <span class="event-tag ${event.type}">${event.type}</span>
      </div>
      <button class="btn-edit">Edit</button>
    `;
    
    eventsList.appendChild(eventCard);
  });
}

// Modal functions
function openModal() {
  document.getElementById('modalOverlay').classList.add('open');
}

function closeModal() {
  document.getElementById('modalOverlay').classList.remove('open');
}

function saveEvent() {
  const name = document.getElementById('inp-name').value;
  const date = document.getElementById('inp-date').value;
  const type = document.getElementById('inp-type').value;
  const startTime = document.getElementById('inp-start').value;
  const endTime = document.getElementById('inp-end').value;
  const desc = document.getElementById('inp-desc').value;
  
  if (name && date && startTime && endTime) {
    const newEvent = {
      id: events.length + 1,
      name,
      date,
      startTime,
      endTime,
      type,
      description: desc
    };
    
    events.push(newEvent);
    
    // Clear form
    document.getElementById('inp-name').value = '';
    document.getElementById('inp-date').value = '';
    document.getElementById('inp-start').value = '';
    document.getElementById('inp-end').value = '';
    document.getElementById('inp-desc').value = '';
    
    closeModal();
    initCalendar();
  }
}

// Navigation
function navigate(element) {
  document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
  element.classList.add('active');
}

function changeMonth(direction) {
  // Implement month navigation
  console.log('Change month:', direction);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initCalendar);