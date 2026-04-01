<?php
class VueMessages extends VueBase {
    public function __construct() {
        parent::__construct("Messages - LinkUp");
        $this->actionActive = 'messages'; 
    }

    public function afficher() {
        global $trad; 
        $pdo = Database::getLink();
        $monId = $_SESSION['user_id'];

        // ✅ REQUÊTE : On récupère les gens avec qui on a un historique de messages
        $sql = "SELECT DISTINCT u.id, u.display_name, u.pseudo, u.profile_pic 
                FROM users u
                JOIN messages m ON (u.id = m.sender_id OR u.id = m.receiver_id)
                WHERE (m.sender_id = :monId OR m.receiver_id = :monId)
                AND u.id != :monId";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':monId' => $monId]);
        $contacts = $stmt->fetchAll();

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/messages.css">

        <main class="messages-container">
            <section class="conversation-sidebar">
                <div class="list-header">
                    <h2>Messages</h2>
                    <div class="search-box">
                        <input type="text" placeholder="Search conversations...">
                    </div>
                </div>

                <div class="conv-list">
                    <?php if(empty($contacts)): ?>
                        <p style="padding: 20px; font-size: 14px; color: #888;">No conversations yet.</p>
                    <?php else: ?>
                        <?php foreach($contacts as $c): ?>
                            <div class="conv-item" onclick="loadConversation(<?= $c['id'] ?>, '<?= htmlspecialchars($c['display_name']) ?>', '<?= htmlspecialchars($c['pseudo']) ?>', '<?= $c['profile_pic'] ?: 'img/ppMan.png' ?>', this)">
                                <div class="conv-avatar">
                                    <img src="<?= $c['profile_pic'] ?: 'img/ppMan.png' ?>" alt="">
                                </div>
                                <div class="conv-info">
                                    <div class="conv-info-top">
                                        <span class="conv-name"><?= htmlspecialchars($c['display_name']) ?></span>
                                    </div>
                                    <p class="conv-preview">@<?= htmlspecialchars($c['pseudo']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <section class="chat-area" id="chatArea">
                <div class="chat-header" id="chatHeader" style="display: none;">
                    <div class="chat-user">
                        <div class="chat-user-avatar"><img id="headerAvatar" src="" alt=""></div>
                        <div class="chat-user-info">
                            <h3 id="headerName"></h3>
                            <p id="headerUsername"></p>
                        </div>
                    </div>
                </div>

                <div class="chat-messages" id="messagesLog">
                    <div class="empty-state">
                        <p>Select a musician to start chatting 🎵</p>
                    </div>
                </div>

                <div class="chat-footer" id="chatFooter" style="display: none;">
                    <div class="input-container">
                        <input type="hidden" id="receiverId" value="">
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

        <script> const MON_ID = <?= $_SESSION['user_id'] ?>; </script>
        <script src="js/messages.js"></script>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}