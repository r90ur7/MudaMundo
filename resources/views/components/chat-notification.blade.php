<div x-data="{ showChatNotification: false, notificationChatId: null, notificationChatData: null }
     "
     x-show="showChatNotification"
     class="fixed bottom-6 right-6 z-50"
     style="display: none;"
     @chat-notification.window="showChatNotification = true; notificationChatId = $event.detail.chatId; notificationChatData = $event.detail.chatData; window.hasNewChatNotification = true; window.notificationChatId = $event.detail.chatId; window.notificationChatData = $event.detail.chatData;"
     @chat-notification-clear.window="showChatNotification = false; window.hasNewChatNotification = false;"
>
    <div class="bg-emerald-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-4 cursor-pointer animate-bounce"
         @click="showChatNotification = false; window.hasNewChatNotification = false; window.dispatchEvent(new Event('chat-notification-clear')); activeTab = 'chats'; $nextTick(() => { if (notificationChatId) { window.openChatFromNotification(notificationChatId, notificationChatData); } })">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2M15 3h-6a2 2 0 00-2 2v2a2 2 0 002 2h6a2 2 0 002-2V5a2 2 0 00-2-2z"/></svg>
        <div>
            <div class="font-bold">Nova mensagem recebida!</div>
            <div class="text-sm" x-text="notificationChatData ? notificationChatData.otherUserName + ': ' + notificationChatData.preview : ''"></div>
        </div>
    </div>
</div>
<script>
// Notificação global de chat
window.addEventListener('chat-notification', function(e) {
    window.hasNewChatNotification = true;
    window.notificationChatId = e.detail.chatId;
    window.notificationChatData = e.detail.chatData;
    const notification = document.querySelector('[x-data*="showChatNotification"]');
    if (notification && notification.__x) {
        notification.__x.$data.showChatNotification = true;
        notification.__x.$data.notificationChatId = e.detail.chatId;
        notification.__x.$data.notificationChatData = e.detail.chatData;
    }
});
window.addEventListener('chat-notification-clear', function() {
    window.hasNewChatNotification = false;
    const notification = document.querySelector('[x-data*="showChatNotification"]');
    if (notification && notification.__x) {
        notification.__x.$data.showChatNotification = false;
    }
});
</script>
