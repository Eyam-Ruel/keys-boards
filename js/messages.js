const chats = {
  sophie: {
    name: "Sophie Martin",
    username: "@sophiemartin",
    avatar: "assets/avatar_sophie.png",
    status: "active",
    messages: [
      {
        type: "incoming",
        text: "Hi Alex! I loved your recent composition post.",
        time: "10:30 AM"
      },
      {
        type: "outgoing",
        text: "Thank you so much! Have you been working on anything new?",
        time: "10:32 AM"
      },
      {
        type: "incoming",
        text: "That sounds great! I'd love to collaborate on that jazz piece.",
        time: "10:35 AM"
      }
    ]
  },
  robert: {
    name: "Robert Chen",
    username: "@robertc",
    avatar: "assets/avatar_robert.png",
    status: "pending",
    messages: [
      {
        type: "incoming",
        text: "Hey! I saw your post about vinyl collecting.",
        time: "Yesterday"
      }
    ]
  },
  emma: {
    name: "Emma Williams",
    username: "@emmaw",
    avatar: "assets/avatar_emma.png",
    status: "active",
    messages: [
      {
        type: "incoming",
        text: "Thanks for the guitar tips yesterday!",
        time: "2 days ago"
      }
    ]
  }
};

function selectChat(chatKey, element) {
  // Update UI active state in list
  document.querySelectorAll('.conv-item').forEach(item => item.classList.remove('active'));
  element.classList.add('active');

  const chat = chats[chatKey];
  const chatArea = document.getElementById('chatArea');
  
  // Update Header
  document.getElementById('headerAvatar').src = chat.avatar;
  document.getElementById('headerName').innerText = chat.name;
  document.getElementById('headerUsername').innerText = chat.username;

  // Clear and update Messages log
  const messagesLog = document.getElementById('messagesLog');
  messagesLog.innerHTML = "";

  // If pending, show connection notice
  if (chat.status === 'pending') {
    const notice = document.createElement('div');
    notice.className = 'connection-notice';
    notice.innerHTML = `
      <div class="notice-text">
        <strong>${chat.name}</strong> wants to connect with you. Accept to start chatting.
      </div>
      <div class="notice-actions">
        <button class="btn-accept" onclick="acceptRequest('${chatKey}')">Accept</button>
        <button class="btn-decline" onclick="declineRequest('${chatKey}')">Decline</button>
      </div>
    `;
    messagesLog.appendChild(notice);
  }

  // Render messages
  chat.messages.forEach(msg => {
    const msgNode = document.createElement('div');
    msgNode.className = `message-node ${msg.type}`;
    msgNode.innerHTML = `
      <div class="message-content">
        <div class="bubble">${msg.text}</div>
        <div class="message-time">${msg.time}</div>
      </div>
    `;
    messagesLog.appendChild(msgNode);
  });

  // Scroll to bottom
  messagesLog.scrollTop = messagesLog.scrollHeight;
}

function acceptRequest(chatKey) {
  chats[chatKey].status = 'active';
  // Re-render
  const activeItem = document.querySelector('.conv-item.active');
  selectChat(chatKey, activeItem);
  
  // Update badge in list
  activeItem.querySelector('.conv-badge')?.remove();
}

function declineRequest(chatKey) {
  alert("Request declined");
}

function sendMessage() {
  const input = document.getElementById('messageInput');
  const text = input.value.trim();
  if (!text) return;

  const msgNode = document.createElement('div');
  msgNode.className = `message-node outgoing`;
  const now = new Date();
  const timeStr = now.getHours() + ":" + now.getMinutes().toString().padStart(2, '0') + " AM";
  
  msgNode.innerHTML = `
    <div class="message-content">
      <div class="bubble">${text}</div>
      <div class="message-time">${timeStr}</div>
    </div>
  `;
  
  document.getElementById('messagesLog').appendChild(msgNode);
  input.value = "";
  document.getElementById('messagesLog').scrollTop = document.getElementById('messagesLog').scrollHeight;
}

// Handle Enter key for sending
document.getElementById('messageInput')?.addEventListener('keypress', function (e) {
  if (e.key === 'Enter') {
    sendMessage();
  }
});

// Initialize first chat
window.onload = () => {
    const firstItem = document.querySelector('.conv-item');
    if (firstItem) selectChat('sophie', firstItem);
};
