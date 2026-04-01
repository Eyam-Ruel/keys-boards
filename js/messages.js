let chatInterval = null; // Le minuteur global

/**
 * ✅ CHARGER UNE CONVERSATION
 * Appelé quand on clique sur un musicien à gauche
 */
function loadConversation(contactId, name, pseudo, avatar, element) {
    // 1. On arrête le rafraîchissement de la conversation précédente
    if (chatInterval) clearInterval(chatInterval);

    // 2. Mise à jour visuelle de la liste (active state)
    document.querySelectorAll('.conv-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');

    // 3. Afficher l'interface de chat
    document.getElementById('chatHeader').style.display = 'flex';
    document.getElementById('chatFooter').style.display = 'block';

    // 4. Remplir les infos du header
    document.getElementById('headerAvatar').src = avatar;
    document.getElementById('headerName').innerText = name;
    document.getElementById('headerUsername').innerText = "@" + pseudo.replace(/^@/, '');
    document.getElementById('receiverId').value = contactId;

    // 5. Premier chargement immédiat
    fetchMessages(contactId);

    // 6. Lancer le robot qui vérifie les nouveaux messages toutes les 3 secondes
    chatInterval = setInterval(() => {
        fetchMessages(contactId);
    }, 3000);
}

/**
 * ✅ RÉCUPÉRER LES MESSAGES (AJAX)
 */
function fetchMessages(contactId) {
    const messagesLog = document.getElementById('messagesLog');

    fetch(`ajax_get_messages.php?contact_id=${contactId}`)
        .then(response => response.json())
        .then(data => {
            // Sécurité : On ne met à jour le HTML que si le nombre de messages a changé
            // Ça évite que l'écran saute ou clignote inutilement
            const currentDisplayCount = messagesLog.querySelectorAll('.message-node').length;
            
            if (data.length !== currentDisplayCount) {
                messagesLog.innerHTML = ""; // On vide
                
                if (data.length === 0) {
                    messagesLog.innerHTML = "<p class='notice-text'>No messages yet. Say hi! 👋</p>";
                } else {
                    data.forEach(msg => {
                        const type = (msg.sender_id == MON_ID) ? 'outgoing' : 'incoming';
                        // Formatage de l'heure (HH:MM)
                        const date = new Date(msg.created_at);
                        const timeStr = date.getHours() + ":" + date.getMinutes().toString().padStart(2, '0');
                        
                        renderMessage(msg.message_text, timeStr, type);
                    });
                }
                // Scroll automatique vers le bas
                messagesLog.scrollTop = messagesLog.scrollHeight;
            }
        })
        .catch(err => console.error("Erreur de récupération :", err));
}

/**
 * ✅ ENVOYER UN MESSAGE
 */
function sendMessage() {
    const input = document.getElementById('messageInput');
    const text = input.value.trim();
    const toId = document.getElementById('receiverId').value;

    if (!text || !toId) return;

    const formData = new FormData();
    formData.append('text', text);
    formData.append('to_id', toId);

    fetch('ajax_send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // On vide l'input
            input.value = "";
            // On force un rafraîchissement immédiat pour voir notre message
            fetchMessages(toId);
        }
    });
}

/**
 * ✅ AFFICHER UNE BULLE DANS LE LOG
 */
function renderMessage(text, time, type) {
    const messagesLog = document.getElementById('messagesLog');
    const msgNode = document.createElement('div');
    msgNode.className = `message-node ${type}`;
    msgNode.innerHTML = `
      <div class="message-content">
        <div class="bubble">${text}</div>
        <div class="message-time">${time}</div>
      </div>
    `;
    messagesLog.appendChild(msgNode);
}

// Gestion de la touche Entrée
document.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && document.activeElement.id === 'messageInput') {
        sendMessage();
    }
});

function toggleFollow(userId, btn) {
  const formData = new FormData();
  formData.append('followed_id', userId);

  fetch('ajax_follow.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.status === 'followed') {
          btn.innerText = "Followed";
          btn.style.backgroundColor = "#28a745";
          btn.style.border = "none";
      } else if (data.status === 'removed') {
          btn.innerText = "Connect";
          btn.style.backgroundColor = ""; // Reprend la couleur du CSS (rouge)
          btn.style.border = "";
      }
  })
  .catch(err => console.error("Erreur Follow:", err));
}

