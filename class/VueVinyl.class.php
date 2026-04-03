<?php

class VueVinyl extends VueBase
{
    public function __construct()
    {
        parent::__construct("Keys and Boards – #Vinyl");
        $this->actionActive = 'explore';
    }

    public function afficher()
    {
        ob_start();
        ?>

        <style>
            /* ---------- BIẾN GIAO DIỆN ---------- */
            :root {
                --burgundy: #8b3a54;
                --accent: #5b5ef4;
                --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                --font: 'Outfit', 'Inter', sans-serif;
            }

            /* ---------- KHU VỰC CHÍNH ---------- */
            .main-area {
                background: rgba(224, 242, 254, 0.3) !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                padding: 0 !important;
                overflow-x: hidden !important;
            }

            .board-container {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            /* ---------- PHẦN ĐẦU CỦA BOARD ---------- */
            .board-header {
                width: 100% !important;
                height: 250px !important;
                padding: 40px 40px 40px 100px;
                color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: center;
                background: linear-gradient(135deg, #fb923c, #f87171) !important;
                box-shadow: inset 0 0 100px rgba(0, 0, 0, 0.1);
            }

            .board-info-top {
                display: flex;
                align-items: center;
                gap: 20px;
                margin-bottom: 20px;
            }

            .board-icon {
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(5px);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .board-titles h1 {
                font-size: 2.5rem;
                margin: 0;
                font-weight: 600;
                text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            }

            .board-titles p {
                font-size: 1.2rem;
                margin: 0;
                font-weight: 500;
            }

            .board-description {
                max-width: 650px;
                font-size: 1rem;
                margin-bottom: 24px;
                line-height: 1.4;
            }

            /* ---------- KHU VỰC FEED ---------- */
            .feed-area {
                width: 100% !important;
                max-width: 2400px !important;
                margin: 32px auto !important;
                padding: 0 20px 100px;
                display: flex;
                flex-direction: column;
                gap: 24px;
            }

            .card {
                width: 100% !important;
                background: #fff;
                border: 1px solid rgba(139, 58, 84, 0.2);
                border-radius: 16px;
                padding: 24px;
                box-shadow: var(--card-shadow);
            }

            .topic-tag {
                background: #ebf4ff;
                color: #3182ce;
                padding: 6px 14px;
                border-radius: 20px;
                font-size: 0.9rem;
                font-weight: 500;
                margin-right: 8px;
            }

            /* ---------- BÀI VIẾT ---------- */
            .post-header {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 16px;
            }

            .avatar {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: #edf2f7;
            }

            .user-name {
                font-weight: 600;
                font-size: 1.05rem;
            }

            .post-time {
                font-size: 0.85rem;
                color: #a0aec0;
                margin-left: 8px;
            }

            .post-content {
                line-height: 1.6;
                margin-bottom: 20px;
                font-size: 0.95rem;
                color: #2d3748;
            }

            /* ---------- Ô SOẠN NỘI DUNG ---------- */
            .composer-input {
                width: 100%;
                border: 1px solid rgba(139, 58, 84, 0.2);
                background: rgba(224, 242, 254, 0.3);
                border-radius: 12px;
                padding: 16px;
                min-height: 120px;
                font-family: inherit;
                resize: none;
                outline: none;
                margin-bottom: 16px;
            }

            .btn-add-files {
                background: var(--burgundy);
                color: #fff;
                border: none;
                padding: 8px 20px;
                border-radius: 99px;
                cursor: pointer;
            }

            .btn-post {
                background: var(--accent);
                color: #fff;
                border: none;
                padding: 10px 28px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
            }
        </style>

        <div class="board-container">
            <section class="board-header">
                <div class="board-info-top">
                    <div class="board-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    <div class="board-titles">
                        <h1>#Vinyl</h1>
                        <p>Vinyl Collectors</p>
                    </div>
                </div>

                <p class="board-description">Dedicated to the art of vinyl collecting. Share your collections, discuss pressing quality, and discover rare records.</p>

                <div class="board-stats">
                    <div class="member-count"><span>15.3k members</span></div>
                    <button class="btn-join" style="background:#fff; border:none; padding:10px 20px; border-radius:8px; font-weight:600; cursor:pointer; margin-left:15px;">Join Board</button>
                </div>
            </section>

            <div class="feed-area">
                <div class="card">
                    <div style="font-weight:600; margin-bottom:15px;">Trending Topics</div>
                    <span class="topic-tag">#Improvisation</span>
                    <span class="topic-tag">#VirtualJams</span>
                    <span class="topic-tag">#MusicTheory</span>
                </div>

                <div class="card">
                    <div class="post-header">
                        <div class="avatar" style="background:#fbd38d"></div>
                        <div class="user-name">Miles Johnson <span class="post-time">2h ago</span></div>
                    </div>
                    <div class="post-content">Just discovered this amazing Miles Davis recording from 1959. The improvisation at 3:42 is pure genius! 🎷</div>
                    <div style="display:flex; gap:20px; color:#666; font-size:0.9rem;">
                        <span>❤️ 127</span>
                        <span>💬 23</span>
                        <span>🔗 Share</span>
                    </div>
                </div>

                <div class="card">
                    <textarea class="composer-input" placeholder="Share something with #Vinyl..."></textarea>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <button class="btn-add-files">+ Add Files</button>
                        <button class="btn-post">Post to Board</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $this->contenu = ob_get_clean();
        parent::afficher();
    }
}