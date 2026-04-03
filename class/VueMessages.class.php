<?php
class VueMessages extends VueBase {
    public function __construct() {
        global $trad;
        $title = $trad['msg_title'] ?? "Messages";
        parent::__construct($title . " - LinkUp");
        $this->actionActive = 'messages'; 
    }

    public function afficher() {
        global $trad; 
        $pdo = Database::getLink();
        $monId = $_SESSION['user_id'];

        
        $sql = "SELECT DISTINCT u.id, u.display_name, u.pseudo, u.profile_pic,
                (SELECT message_text 
                 FROM messages 
                 WHERE (sender_id = u.id AND receiver_id = :monId) 
                    OR (sender_id = :monId AND receiver_id = u.id)
                 ORDER BY created_at DESC LIMIT 1) as last_text,
                (SELECT MAX(created_at) 
                 FROM messages 
                 WHERE (sender_id = u.id AND receiver_id = :monId) 
                    OR (sender_id = :monId AND receiver_id = u.id)
                ) as last_msg_date
                FROM users u
                JOIN follows f1 ON (f1.followed_id = u.id AND f1.follower_id = :monId)
                JOIN follows f2 ON (f2.follower_id = u.id AND f2.followed_id = :monId)
                WHERE u.id != :monId
                ORDER BY last_msg_date DESC, u.display_name ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':monId' => $monId]);
        $contacts = $stmt->fetchAll();

        ob_start(); 
        ?>
        
        <link rel="stylesheet" href="css/messages.css">

        <main class="messages-container">
            <section class="conversation-sidebar">
                <div class="list-header">
                    <h2><?= $trad['msg_title'] ?? 'Messages' ?></h2>
                    <div class="search-box">
                        <input type="text" placeholder="<?= $trad['msg_search_placeholder'] ?? 'Search conversations...' ?>">
                    </div>
                </div>

                <div class="conv-list">
                    <?php if(empty($contacts)): ?>
                        <p style="padding: 20px; font-size: 14px; color: #888;"><?= $trad['msg_no_conversations'] ?? 'No conversations yet.' ?></p>
                    <?php else: ?>
                        <?php foreach($contacts as $c): ?>
                            <div class="conv-item" onclick="loadConversation(<?= $c['id'] ?>, '<?= htmlspecialchars($c['display_name']) ?>', '<?= htmlspecialchars($c['pseudo']) ?>', '<?= $c['profile_pic'] ?: 'img/photo_defaut.png' ?>', this)">
                                <div class="conv-avatar">
                                    <img src="<?= $c['profile_pic'] ?: 'img/photo_defaut.png' ?>" alt="">
                                </div>
                                <div class="conv-info">
                                    <div class="conv-info-top" style="display: flex; justify-content: space-between; align-items: baseline;">
                                        <span class="conv-name" style="font-weight: 600; font-size: 14px;"><?= htmlspecialchars($c['display_name']) ?></span>
                                        <?php if ($c['last_msg_date']): ?>
                                            <small style="color: #999; font-size: 11px;">
                                                <?= date('H:i', strtotime($c['last_msg_date'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="conv-preview" style="font-size: 12px; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; margin-top: 2px;">
                                        <?= $c['last_text'] ? htmlspecialchars($c['last_text']) : "<span style='font-style:italic;'>" . ($trad['msg_new_match'] ?? 'New match! Send a message...') . "</span>" ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <section class="chat-area" id="chatArea">
            <div class="chat-header" id="chatHeader" style="display:none;">
    <div class="chat-user">
        <button class="mobile-back-btn" onclick="closeChat()" style="display:none; background:none; border:none; font-size:20px; margin-right:10px; cursor:pointer;">
            ⬅️
        </button>
        
        <div class="chat-user-avatar">
            <img id="headerAvatar" src="" alt="">
        </div>
        <div class="chat-user-info">
            <h3 id="headerName"></h3>
            <p id="headerUsername"></p>
        </div>
    </div>
    </div>

                <div class="chat-messages" id="messagesLog">
                    <div class="empty-state">
                        <p><?= $trad['msg_select_chat'] ?? 'Select a musician to start chatting 🎵' ?></p>
                    </div>
                </div>


                <div class="chat-footer" id="chatFooter" style="display: none;">
                    <div class="input-container">
                        <input type="hidden" id="receiverId" value="">
                        <input type="text" id="messageInput" placeholder="<?= $trad['msg_type_placeholder'] ?? 'Type a message...' ?>">
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