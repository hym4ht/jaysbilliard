{{-- ═══════════════════════════════ CHAT POPUP ═══════════════════════════════ --}}
<div class="chat-overlay" id="chatOverlay"></div>
<div class="chat-popup" id="chatPopup">
    <div class="chat-popup-header">
        <div class="chat-popup-user">
            <div class="chat-popup-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="currentColor" fill-opacity="0.1"/>
                </svg>
            </div>
            <div class="chat-popup-info">
                <div class="chat-popup-title">
                    <span class="chat-popup-table" id="chatTableName">Table 03</span>
                    <span class="chat-popup-dot"> &bull; </span>
                    <span class="chat-popup-status" id="chatStatus">Dipesan</span>
                </div>
                <span class="chat-popup-name" id="chatUserName">Zaenal</span>
            </div>
        </div>
        <button class="chat-popup-close" id="chatClose">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>
    <div class="chat-popup-body" id="chatBody"></div>
    <div class="chat-popup-footer">
        <div class="chat-input-wrap">
            <input type="text" class="chat-input" placeholder="Type a message..." id="chatInput">
        </div>
        <button class="chat-send-btn" id="chatSend">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>
    </div>
</div>
