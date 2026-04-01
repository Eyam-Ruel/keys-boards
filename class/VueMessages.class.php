<?php
class VueMessages extends VueBase {
    public function __construct() {
        parent::__construct("Messages - LinkUp");
        // On définit l'onglet actif pour la sidebar
        $this->actionActive = 'messages'; 
    }

    public function afficher() {
        global $trad; 

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/messages.css">

        <main class="messages-container">
            <section class="conversation-sidebar">
                <div class="list-header">
                    <h2>Messages</h2>
                    <div class="search-box">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" placeholder="Search conversations...">
                    </div>
                </div>

                <div class="conv-list">
                    <div class="conv-item active" onclick="selectChat('sophie', this)">
                        <div class="conv-avatar"><img src="img/ppWoman.png" alt="Sophie Martin"></div>
                        <div class="conv-info">
                            <div class="conv-info-top">
                                <span class="conv-name">Sophie Martin</span>
                                <span class="conv-time">2h ago</span>
                            </div>
                            <p class="conv-preview">That sounds great! I'd love to collaborate...</p>
                            <span class="conv-badge new">2 new</span>
                        </div>
                    </div>

                    <div class="conv-item" onclick="selectChat('robert', this)">
                        <div class="conv-avatar"><img src="img/ppMan.png" alt="Robert Chen"></div>
                        <div class="conv-info">
                            <div class="conv-info-top">
                                <span class="conv-name">Robert Chen</span>
                                <span class="conv-time">1d ago</span>
                            </div>
                            <p class="conv-preview">Hey! I saw your post about vinyl collecting.</p>
                            <span class="conv-badge pending">Pending request</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="chat-area" id="chatArea">
                <div class="chat-header">
                    <div class="chat-user">
                        <div class="chat-user-avatar"><img id="headerAvatar" src="img/ppWoman.png" alt=""></div>
                        <div class="chat-user-info">
                            <h3 id="headerName">Sophie Martin</h3>
                            <p id="headerUsername">@sophiemartin</p>
                        </div>
                    </div>
                </div>

                <div class="chat-messages" id="messagesLog">
                    <div class="connection-notice">
                        <p class="notice-text">Robert Chen wants to connect with you. Accepting this request will allow them to send you messages.</p>
                        <div class="notice-actions">
                            <button class="btn-accept">Accept Request</button>
                            <button class="btn-decline">Decline</button>
                        </div>
                    </div>
                </div>

                <div class="chat-footer">
                    <div class="input-container">
                        <input type="text" id="messageInput" placeholder="Type a message...">
                        <button class="btn-send" onclick="sendMessage()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </div>
                </div>
            </section>
        </main>

        <script src="js/messages.js"></script>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}