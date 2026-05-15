@extends('layouts.admin')

@section('title', 'Inbox Chat PPDB')

@push('css')
<style>
    /* ===== PREMIUM PPDB CHAT INBOX ===== */
    .chat-wrapper {
        display: flex;
        height: calc(100vh - 180px);
        min-height: 500px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        background: #fff;
    }

    /* LEFT: Room List */
    .chat-sidebar {
        width: 320px;
        flex-shrink: 0;
        background: #f8fafc;
        border-right: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
    }
    .chat-sidebar-header {
        padding: 20px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
    }
    .chat-room-list { overflow-y: auto; flex: 1; }
    .chat-room-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .chat-room-item:hover { background: #f1f5f9; }
    .chat-room-item.active { background: #ede9fe; border-left: 3px solid #4f46e5; }
    .chat-room-avatar {
        width: 46px; height: 46px; border-radius: 14px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white; font-weight: 800; font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .chat-room-info { flex: 1; min-width: 0; }
    .chat-room-name { font-weight: 700; font-size: 0.88rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .chat-room-preview { font-size: 0.75rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
    .chat-room-time { font-size: 0.65rem; color: #94a3b8; }
    .unread-badge { background: #ef4444; color: white; border-radius: 10px; padding: 1px 7px; font-size: 0.65rem; font-weight: 800; flex-shrink: 0; }

    /* RIGHT: Chat Area */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fafafa;
    }
    .chat-header {
        padding: 18px 24px;
        background: white;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .chat-empty {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    /* Bubbles */
    .msg-row { display: flex; align-items: flex-end; gap: 8px; }
    .msg-row.from-admin { flex-direction: row-reverse; }
    .msg-bubble {
        max-width: 68%;
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 0.88rem;
        line-height: 1.5;
        position: relative;
    }
    .msg-bubble.student { background: white; color: #1e293b; border: 1px solid #f1f5f9; border-bottom-left-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    .msg-bubble.admin { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-bottom-right-radius: 4px; }
    .msg-bubble.system { background: #f8fafc; color: #64748b; font-style: italic; font-size: 0.8rem; border: 1px dashed #e2e8f0; margin: 0 auto; text-align: center; border-radius: 12px; }
    .msg-time { font-size: 0.65rem; margin-top: 4px; opacity: 0.6; }
    .msg-avatar-sm { width: 30px; height: 30px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; flex-shrink: 0; }

    /* Input */
    .chat-input-area {
        padding: 16px 24px;
        background: white;
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: 12px;
        align-items: flex-end;
    }
    .chat-input {
        flex: 1;
        border: 2px solid #f1f5f9;
        border-radius: 15px;
        padding: 12px 18px;
        font-size: 0.88rem;
        resize: none;
        outline: none;
        transition: border-color 0.2s;
        max-height: 120px;
    }
    .chat-input:focus { border-color: #4f46e5; }
    .chat-send-btn {
        width: 48px; height: 48px; border-radius: 14px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none; color: white; font-size: 1.1rem;
        cursor: pointer; transition: all 0.2s; flex-shrink: 0;
    }
    .chat-send-btn:hover { transform: scale(1.05); }

    .status-badge { font-size: 0.7rem; padding: 3px 10px; border-radius: 20px; font-weight: 700; }
    .status-open { background: #dcfce7; color: #16a34a; }
    .status-closed { background: #fef3c7; color: #d97706; }

    @media (max-width: 768px) {
        .chat-wrapper { flex-direction: column; height: auto; }
        .chat-sidebar { width: 100%; height: 250px; }
        .chat-main { height: 400px; }
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">
                    <i class="fas fa-comments mr-2 text-indigo"></i> Inbox Chat PPDB
                    @if($totalUnread > 0)
                        <span class="badge badge-danger ml-2">{{ $totalUnread }}</span>
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ppdb.index') }}">PPDB</a></li>
                    <li class="breadcrumb-item active">Chat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="chat-wrapper">
            {{-- SIDEBAR: Daftar Percakapan --}}
            <div class="chat-sidebar">
                <div class="chat-sidebar-header">
                    <h6 class="font-weight-bold mb-0"><i class="fas fa-inbox mr-2"></i> Percakapan Masuk</h6>
                    <small class="opacity-7">{{ $rooms->total() }} pendaftar</small>
                </div>

                <div class="chat-room-list" id="roomList">
                    @forelse($rooms as $room)
                        <div class="chat-room-item" data-room-id="{{ $room->id }}"
                             onclick="openRoom({{ $room->id }}, '{{ $room->registrant->nama_lengkap }}', '{{ $room->registrant->registration_number }}', '{{ $room->status }}')">
                            <div class="chat-room-avatar">{{ substr($room->registrant->nama_lengkap, 0, 1) }}</div>
                            <div class="chat-room-info">
                                <div class="chat-room-name">{{ $room->registrant->nama_lengkap }}</div>
                                <div class="chat-room-preview">
                                    {{ $room->latestMessage?->message ?? 'Belum ada pesan' }}
                                </div>
                                <div class="chat-room-time mt-1">
                                    {{ $room->registrant->registration_number }}
                                    @if($room->status === 'closed')
                                        &bull; <span class="text-warning">Ditutup</span>
                                    @endif
                                </div>
                            </div>
                            @if($room->unread_admin > 0)
                                <div class="unread-badge" id="badge_{{ $room->id }}">{{ $room->unread_admin }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-comment-slash fa-2x mb-3 d-block"></i>
                            Belum ada percakapan
                        </div>
                    @endforelse
                </div>

                {{ $rooms->links('vendor.pagination.simple-default') }}
            </div>

            {{-- MAIN: Area Chat --}}
            <div class="chat-main" id="chatMain">
                {{-- Empty State --}}
                <div class="chat-empty" id="chatEmptyState">
                    <div style="width:80px;height:80px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                        <i class="fas fa-comments text-muted" style="font-size:2rem;"></i>
                    </div>
                    <h6 class="font-weight-bold text-muted mb-2">Pilih Percakapan</h6>
                    <small class="text-muted">Klik salah satu pendaftar di sebelah kiri untuk memulai chat</small>
                </div>

                {{-- Active Chat --}}
                <div id="chatActiveArea" style="display:none; flex-direction:column; height:100%;">
                    <div class="chat-header" id="chatHeader">
                        <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;" id="chatAvatarLetter">A</div>
                        <div class="flex-1">
                            <div class="font-weight-bold text-dark" id="chatName">-</div>
                            <small class="text-muted" id="chatRegNo">-</small>
                        </div>
                        <div class="ml-auto d-flex align-items-center gap-3">
                            <span class="status-badge" id="chatStatusBadge">-</span>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btnToggleStatus" onclick="toggleRoomStatus()">
                                <i class="fas fa-lock-open mr-1"></i> <span id="toggleStatusLabel">Tutup Chat</span>
                            </button>
                        </div>
                    </div>

                    <div class="chat-messages" id="chatMessages"></div>

                    <div class="chat-input-area" id="chatInputArea">
                        <textarea class="chat-input" id="adminMessageInput" placeholder="Ketik balasan..." rows="1"
                            onkeydown="if(event.ctrlKey && event.key==='Enter') sendAdminReply()"></textarea>
                        <button class="chat-send-btn" onclick="sendAdminReply()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    let currentRoomId = null;
    let currentRoomStatus = 'open';
    let pollingInterval = null;

    function openRoom(roomId, name, regNo, status) {
        currentRoomId = roomId;
        currentRoomStatus = status;

        // UI update
        document.getElementById('chatEmptyState').style.display = 'none';
        const activeArea = document.getElementById('chatActiveArea');
        activeArea.style.display = 'flex';

        document.getElementById('chatAvatarLetter').textContent = name.charAt(0).toUpperCase();
        document.getElementById('chatName').textContent = name;
        document.getElementById('chatRegNo').textContent = regNo;
        updateStatusUI(status);

        // Highlight active room
        document.querySelectorAll('.chat-room-item').forEach(el => el.classList.remove('active'));
        document.querySelector(`[data-room-id="${roomId}"]`)?.classList.add('active');

        // Clear badge
        const badge = document.getElementById(`badge_${roomId}`);
        if (badge) badge.remove();

        // Load messages
        loadMessages();

        // Start polling
        clearInterval(pollingInterval);
        pollingInterval = setInterval(loadMessages, 5000);
    }

    function updateStatusUI(status) {
        const badge = document.getElementById('chatStatusBadge');
        const label = document.getElementById('toggleStatusLabel');
        const input = document.getElementById('chatInputArea');
        const icon = document.getElementById('btnToggleStatus').querySelector('i');

        if (status === 'open') {
            badge.className = 'status-badge status-open';
            badge.textContent = 'Aktif';
            label.textContent = 'Tutup Chat';
            icon.className = 'fas fa-lock mr-1';
            input.style.opacity = '1';
            input.style.pointerEvents = 'auto';
        } else {
            badge.className = 'status-badge status-closed';
            badge.textContent = 'Ditutup';
            label.textContent = 'Buka Kembali';
            icon.className = 'fas fa-lock-open mr-1';
            input.style.opacity = '0.5';
            input.style.pointerEvents = 'none';
        }
    }

    function loadMessages() {
        if (!currentRoomId) return;

        $.get(`{{ url('/admission/ppdb/chat') }}/${currentRoomId}/messages`)
            .done(res => {
                renderMessages(res.messages);
                updateStatusUI(res.room_status);
                currentRoomStatus = res.room_status;

                // Update global unread badge in sidebar
                const sidebarBadge = document.querySelector('.content-header h1 .badge-danger');
                if (sidebarBadge) {
                    if (res.unread_admin > 0) { sidebarBadge.textContent = res.unread_admin; sidebarBadge.style.display = ''; }
                    else sidebarBadge.style.display = 'none';
                }
            });
    }

    function renderMessages(messages) {
        const container = document.getElementById('chatMessages');
        const wasAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 100;

        container.innerHTML = messages.map(m => {
            if (m.sender_type === 'system') {
                return `<div class="msg-bubble system">${m.message}<div class="msg-time">${m.time}</div></div>`;
            }
            const isAdmin = m.sender_type === 'admin';
            const rowClass = isAdmin ? 'from-admin' : '';
            const bubbleClass = isAdmin ? 'admin' : 'student';
            const avatarBg = isAdmin ? 'background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;' : 'background:#e2e8f0;color:#64748b;';
            const avatarLetter = isAdmin ? (m.sender_name.charAt(0)) : '👤';

            return `
                <div class="msg-row ${rowClass}">
                    <div class="msg-avatar-sm" style="${avatarBg}">${avatarLetter}</div>
                    <div>
                        <div class="msg-bubble ${bubbleClass}">${m.message}</div>
                        <div class="msg-time text-muted" style="font-size:0.65rem;${isAdmin?'text-align:right':''}">${m.time}</div>
                    </div>
                </div>
            `;
        }).join('');

        if (wasAtBottom) container.scrollTop = container.scrollHeight;
    }

    function sendAdminReply() {
        const input = document.getElementById('adminMessageInput');
        const msg = input.value.trim();
        if (!msg || !currentRoomId || currentRoomStatus === 'closed') return;

        input.value = '';
        input.style.height = 'auto';

        $.ajax({
            url: `{{ url('/admission/ppdb/chat') }}/${currentRoomId}/send`,
            method: 'POST',
            data: { message: msg, _token: '{{ csrf_token() }}' },
            success: res => {
                if (res.success) loadMessages();
            },
            error: () => alert('Gagal mengirim pesan.')
        });
    }

    function toggleRoomStatus() {
        if (!currentRoomId) return;
        $.post(`{{ url('/admission/ppdb/chat') }}/${currentRoomId}/toggle`, { _token: '{{ csrf_token() }}' })
            .done(res => {
                currentRoomStatus = res.status;
                updateStatusUI(res.status);
            });
    }

    // Auto-resize textarea
    document.getElementById('adminMessageInput').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
</script>
@endpush
