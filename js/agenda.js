// ── State ──
const today = new Date();
let current = new Date(today.getFullYear(), today.getMonth(), 1);

const MONTHS = ['January','February','March','April','May','June',
                'July','August','September','October','November','December'];
const DAYS   = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

let events = [
  { id: 1, name: 'Jazz Piano Session', date: '2026-03-26', start: '19:00', end: '21:00',
    desc: 'Live jazz piano session open to all', type: 'public' },
  { id: 2, name: 'Guitar Practice', date: '2026-03-28', start: '14:00', end: '16:00',
    desc: 'Personal practice session', type: 'private' },
  { id: 3, name: 'Jam Session with Sophie', date: '2026-03-30', start: '18:00', end: '20:00',
    desc: 'Collaborative jam session', type: 'shared' },
];

// ── Render calendar ──
function renderCalendar() {
  const year = current.getFullYear();
  const month = current.getMonth();
  document.getElementById('monthTitle').textContent = `${MONTHS[month]} ${year}`;

  // Heads
  const head = document.getElementById('calHead');
  head.innerHTML = DAYS.map(d => `<div class="cal-head">${d}</div>`).join('');

  // Cells
  const firstDay = new Date(year, month, 1);
  let startDow = firstDay.getDay(); // 0=Sun
  // Adjust so week starts Mon: Mon=0..Sun=6
  startDow = (startDow === 0) ? 6 : startDow - 1;

  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const prevDays    = new Date(year, month, 0).getDate();

  const body = document.getElementById('calBody');
  body.innerHTML = '';

  let cells = [];

  // Prev month fill
  for (let i = startDow - 1; i >= 0; i--) {
    cells.push({ day: prevDays - i, type: 'other' });
  }
  // Current month
  for (let d = 1; d <= daysInMonth; d++) {
    cells.push({ day: d, type: 'current' });
  }
  // Next month fill
  const total = Math.ceil(cells.length / 7) * 7;
  let nd = 1;
  while (cells.length < total) {
    cells.push({ day: nd++, type: 'other' });
  }

  cells.forEach(c => {
    const cell = document.createElement('div');
    cell.className = 'cal-cell' + (c.type === 'other' ? ' other-month' : '');

    const isToday = c.type === 'current' &&
      c.day === today.getDate() &&
      month === today.getMonth() &&
      year === today.getFullYear();
    if (isToday) cell.classList.add('today');

    let html = `<span class="day-num">${c.day}</span>`;

    if (c.type === 'current') {
      const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(c.day).padStart(2,'0')}`;
      const dayEvents = events.filter(e => e.date === dateStr);
      dayEvents.forEach(ev => {
        html += `<div class="cal-event ${ev.type}" title="${ev.name}">${ev.start} ${ev.name}</div>`;
      });
    }

    cell.innerHTML = html;
    body.appendChild(cell);
  });
}

// ── Render upcoming events ──
function renderEvents() {
  const todayStr = today.toISOString().split('T')[0];
  const upcoming = events
    .filter(e => e.date >= todayStr)
    .sort((a, b) => a.date.localeCompare(b.date));

  const list = document.getElementById('eventsList');
  if (!upcoming.length) {
    list.innerHTML = `<p style="color:var(--muted);font-size:.85rem;">No upcoming events.</p>`;
    return;
  }

  list.innerHTML = upcoming.map(ev => {
    const d = new Date(ev.date + 'T00:00:00');
    const dayNum = d.getDate();
    const mon = MONTHS[d.getMonth()].slice(0,3);
    const labelMap = { public:'Public', shared:'Shared', private:'Private' };
    return `
      <div class="event-card">
        <div class="event-date-badge">
          <div class="day">${dayNum}</div>
          <div class="month">${mon}</div>
        </div>
        <div class="event-info">
          <div class="event-name">${ev.name}</div>
          <div class="event-time">${ev.start} – ${ev.end}</div>
          <div class="event-desc">${ev.desc}</div>
          <span class="event-tag ${ev.type}">${labelMap[ev.type]}</span>
        </div>
        <button class="btn-edit" onclick="editEvent(${ev.id})">Edit</button>
      </div>`;
  }).join('');
}

// ── Navigation ──
function changeMonth(dir) {
  current.setMonth(current.getMonth() + dir);
  renderCalendar();
}

function navigate(el) {
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  el.classList.add('active');
}

// ── Modal ──
function openModal(ev = null) {
  document.getElementById('inp-name').value  = ev ? ev.name  : '';
  document.getElementById('inp-date').value  = ev ? ev.date  : '';
  document.getElementById('inp-start').value = ev ? ev.start : '';
  document.getElementById('inp-end').value   = ev ? ev.end   : '';
  document.getElementById('inp-desc').value  = ev ? ev.desc  : '';
  document.getElementById('inp-type').value  = ev ? ev.type  : 'public';
  document.getElementById('modalOverlay').dataset.editId = ev ? ev.id : '';
  document.getElementById('modalOverlay').classList.add('open');
}

function closeModal() {
  document.getElementById('modalOverlay').classList.remove('open');
}

function saveEvent() {
  const name  = document.getElementById('inp-name').value.trim();
  const date  = document.getElementById('inp-date').value;
  const start = document.getElementById('inp-start').value;
  const end   = document.getElementById('inp-end').value;
  const desc  = document.getElementById('inp-desc').value.trim();
  const type  = document.getElementById('inp-type').value;

  if (!name || !date) { alert('Name and date are required.'); return; }

  const editId = document.getElementById('modalOverlay').dataset.editId;
  if (editId) {
    const idx = events.findIndex(e => e.id === parseInt(editId));
    if (idx > -1) events[idx] = { ...events[idx], name, date, start, end, desc, type };
  } else {
    events.push({ id: Date.now(), name, date, start, end, desc, type });
  }

  closeModal();
  renderCalendar();
  renderEvents();
}

function editEvent(id) {
  const ev = events.find(e => e.id === id);
  if (ev) openModal(ev);
}

// Click outside modal closes it
document.getElementById('modalOverlay').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// ── Init ──
renderCalendar();
renderEvents();