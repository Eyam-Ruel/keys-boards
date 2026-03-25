# Keys & Boards | Musician Networking Platform
> **A project designed and developed by Team LinkUp.**

**Keys & Boards** is an innovative social platform dedicated to connecting musicians from all walks of life. Our mission is to break down generational and geographical barriers to foster skill sharing (#Transmission), collective creation, and the organization of musical events.

---

## 🏗️ Architecture & Technical Stack

The project is built on a custom **PHP MVC** architecture, ensuring a strict separation between business logic, data management, and the user interface.

* **Backend:** PHP 8+ (Dynamic Routing, Session Management, Security).
* **Database:** MariaDB (Relational model optimized for social networking).
* **Frontend:** HTML5 / CSS3 (Extensive use of native variables) / Vanilla JavaScript.
* **Mapping:** Integration of **Leaflet.js** & **OpenStreetMap** (Geocoding via Nominatim API).
* **Internationalization (i18n):** Dynamic PHP dictionary system (FR, EN, VI, SQ).

---

## 🌟 Core Features

### Explore & Mapping
An interactive mapping interface for locating nearby musicians. Using a multi-criteria search engine, users can filter profiles by instruments, skills, or spoken languages.

### Music Boards
Thematic community spaces serving as hubs for resources and exchange. Each "Board" centralizes discussions, media, and specific announcements for a genre or expertise.

### Musical Agenda
An integrated planning tool to manage rehearsals, music lessons, or public events. The system handles different visibility levels (Private, Shared, Public).

### Networking & Messaging
A secure internal messaging system facilitating direct contact without exposing personal data. The "Follow" system allows users to build their own network of collaborators.

---

## 🎨 Design & User Experience

The site has been designed with a **Pixel Perfect** approach to ensure a seamless experience:
* **Adaptive Layout:** Fixed navigation sidebar (Sticky Sidebar) for quick access to tools.
* **Dynamic Theme:** Native support for Light and Dark modes with persistence via `localStorage`.
* **Multilingual:** Instant language switching without losing context.

---

## 🚀 Development Workflow

To ensure project stability, we apply a strict branching strategy:
1. **Isolation:** Modular development of components on feature branches.
2. **Integration:** Migration and testing of HTML/CSS views within the PHP MVC engine.
3. **Merge:** Code review and final merge into the main branch (`main`).

---
