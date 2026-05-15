@extends('layouts.app')

@section('title', 'Inbox Chat PPDB')
@section('subtitle', 'Penerimaan Peserta Didik Baru')

@section('content')
{{-- PREMIUM HEADER BANNER --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 overflow-hidden position-relative" style="border-radius:15px;background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);">
            <div class="card-body p-4 position-relative" style="z-index:1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-comments mr-2 animate__animated animate__fadeInLeft"></i>
                            Inbox Chat PPDB
                            @if($totalUnread > 0)
                                <span class="badge badge-danger ml-2" style="font-size:0.75rem;border-radius:20px;">{{ $totalUnread }}</span>
                            @endif
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola percakapan antara panitia dan calon peserta didik baru secara real-time.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-headset fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-chat-1"></div>
            <div class="bg-circle-chat-2"></div>
        </div>
    </div>
</div>

{{-- STATISTICS WIDGETS (GLASSMORPHISM) --}}
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius:12px;border-left:5px solid #4f46e5 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Percakapan</p>
                        <h2 class="font-weight-bold mb-0 text-indigo">{{ $rooms->total() }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-indigo rounded-circle p-3">
                        <i class="fas fa-comments text-indigo fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar" style="width:100%;background:#4f46e5;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius:12px;border-left:5px solid #ef4444 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Belum Dibaca</p>
                        <h2 class="font-weight-bold mb-0 text-danger">{{ $totalUnread }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-danger rounded-circle p-3">
                        <i class="fas fa-envelope text-danger fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-danger" style="width: {{ $rooms->total() > 0 ? min(100, ($totalUnread / $rooms->total()) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius:12px;border-left:5px solid #16a34a !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Halaman Ini</p>
                        <h2 class="font-weight-bold mb-0 text-success">{{ $rooms->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-list text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-success" style="width:{{ $rooms->total() > 0 ? ($rooms->count()/$rooms->total())*100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CONTENT: SIDEBAR + CHAT AREA --}}
<div class="row">
    {{-- LEFT: Room List --}}
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <span class="step-badge-chat mr-2"><i class="fas fa-inbox" style="font-size:12px;"></i></span>
                    Daftar Percakapan
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="room-list" id="roomList">
                    @forelse($rooms as $room)
                        <div class="room-item" id="room_{{ $room->id }}" data-room-id="{{ $room->id }}"
                             onclick="openRoom({{ $room->id }}, '{{ addslashes($room->registrant->nama_lengkap) }}', '{{ $room->registrant->registration_number }}', '{{ $room->status }}')">
                            <div class="room-avatar">{{ strtoupper(substr($room->registrant->nama_lengkap, 0, 1)) }}</div>
                            <div class="room-info">
                                <div class="room-name">{{ $room->registrant->nama_lengkap }}</div>
                                <div class="room-preview">{{ Str::limit($room->latestMessage?->message ?? 'Belum ada pesan', 38) }}</div>
                                <div class="room-meta mt-1">
                                    <span class="text-xs text-muted">{{ $room->registrant->registration_number }}</span>
                                    @if($room->status === 'closed')
                                        <span class="badge badge-xs ml-1" style="background:#fef3c7;color:#d97706;font-size:0.6rem;padding:2px 6px;border-radius:10px;">Ditutup</span>
                                    @else
                                        <span class="badge badge-xs ml-1" style="background:#dcfce7;color:#16a34a;font-size:0.6rem;padding:2px 6px;border-radius:10px;">Aktif</span>
                                    @endif
                                </div>
                            </div>
                            @if($room->unread_admin > 0)
                                <div class="unread-badge" id="badge_{{ $room->id }}">{{ $room->unread_admin }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-comment-slash fa-2x mb-3 d-block text-indigo" style="opacity:0.4;"></i>
                            <p class="mb-0 font-weight-bold">Belum ada percakapan</p>
                            <small>Peserta belum memulai chat</small>
                        </div>
                    @endforelse
                </div>

                @if($rooms->hasPages())
                    <div class="px-3 py-2 border-top">
                        {{ $rooms->links('vendor.pagination.simple-default') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT: Chat Area --}}
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card" style="height:calc(100vh - 340px);min-height:520px;display:flex;flex-direction:column;">

            {{-- Empty State --}}
            <div id="chatEmptyState" class="d-flex flex-column align-items-center justify-content-center flex-1 text-center p-5" style="flex:1;">
                <div style="width:100px;height:100px;background:rgba(79,70,229,0.05);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:24px;box-shadow:inset 0 2px 10px rgba(0,0,0,0.02);">
                    <i class="fas fa-comments fa-3x" style="color:#4f46e5;opacity:0.6;"></i>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Pilih Percakapan</h4>
                <p class="text-muted mb-0">Klik salah satu pendaftar di sebelah kiri<br>untuk membuka obrolan dan merespon pertanyaan.</p>
            </div>

            {{-- Active Chat --}}
            <div id="chatActiveArea" class="d-none flex-column flex-1" style="flex:1;overflow:hidden;">

                {{-- Chat Header --}}
                <div class="card-header bg-white py-3 border-bottom" id="chatHeader">
                    <div class="d-flex align-items-center">
                        <div id="chatAvatarLetter" style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:1.1rem;flex-shrink:0;">A</div>
                        <div class="ml-3 flex-1">
                            <div class="font-weight-bold text-dark mb-0" id="chatName">-</div>
                            <small class="text-muted" id="chatRegNo">-</small>
                        </div>
                        <div class="ml-auto d-flex align-items-center" style="gap:10px;">
                            <span class="status-badge-chat" id="chatStatusBadge">-</span>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 font-weight-bold btn-premium" id="btnToggleStatus" onclick="toggleRoomStatus()">
                                <i class="fas fa-lock-open mr-1" id="toggleStatusIcon"></i>
                                <span id="toggleStatusLabel">Tutup Chat</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="chat-messages-area" id="chatMessages"></div>

                {{-- Input --}}
                <div class="chat-input-wrap" id="chatInputArea">
                    <textarea class="chat-textarea" id="adminMessageInput" placeholder="Ketik balasan... (Ctrl+Enter untuk kirim)" rows="1"
                        onkeydown="if(event.ctrlKey && event.key==='Enter') sendAdminReply()"></textarea>
                    <button class="chat-send-btn-premium" onclick="sendAdminReply()" title="Kirim (Ctrl+Enter)">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* === Header & Circles === */
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-chat-1, .bg-circle-chat-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-chat-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-chat-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* === Stat Cards === */
    .text-indigo { color: #4f46e5 !important; }
    .bg-soft-indigo { background: #ede9fe; }
    .bg-soft-danger  { background: #fee2e2; }
    .bg-soft-success { background: #dcfce7; }
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .progress-xs { height: 4px; border-radius: 10px; }
    .info-card { transition: all 0.3s ease; }
    .info-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.06) !important; }

    /* === Premium Card === */
    .premium-card { border-radius: 15px !important; overflow: hidden; transition: all 0.3s ease; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

    /* === Step Badge === */
    .step-badge-chat {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg,#4f46e5,#7c3aed); color: #fff;
        box-shadow: 0 2px 6px rgba(79,70,229,0.3);
    }

    /* === Room List === */
    .room-list { max-height: calc(100vh - 400px); min-height: 300px; overflow-y: auto; }
    .room-item {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .room-item:hover { background: #f8f9ff; }
    .room-item.active { background: #ede9fe; border-left: 4px solid #4f46e5; }
    .room-avatar {
        width: 44px; height: 44px; border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white; font-weight: 800; font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; box-shadow: 0 4px 10px rgba(79,70,229,0.25);
    }
    .room-info { flex: 1; min-width: 0; }
    .room-name { font-weight: 700; font-size: 0.87rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .room-preview { font-size: 0.75rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
    .room-meta { font-size: 0.7rem; }
    .unread-badge {
        background: linear-gradient(135deg,#ef4444,#dc2626); color: white;
        border-radius: 12px; padding: 2px 8px; font-size: 0.65rem; font-weight: 800;
        flex-shrink: 0; box-shadow: 0 2px 6px rgba(239,68,68,0.4);
    }

    /* === Chat Area === */
    .flex-1 { flex: 1; }
    .chat-messages-area {
        flex: 1; overflow-y: auto; padding: 20px 24px;
        display: flex; flex-direction: column; gap: 10px;
        background: #fafbff;
    }

    /* Bubbles */
    .msg-row { display: flex; align-items: flex-end; gap: 8px; }
    .msg-row.from-admin { flex-direction: row-reverse; }
    .msg-bubble {
        max-width: 68%; padding: 11px 16px;
        border-radius: 18px; font-size: 0.87rem; line-height: 1.55;
    }
    .msg-bubble.student {
        background: white; color: #1e293b;
        border: 1px solid #e2e8f0; border-bottom-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .msg-bubble.admin {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white; border-bottom-right-radius: 4px;
        box-shadow: 0 4px 12px rgba(79,70,229,0.3);
    }
    .msg-bubble.system {
        background: #f8fafc; color: #64748b; font-style: italic;
        font-size: 0.78rem; border: 1px dashed #e2e8f0;
        margin: 0 auto; text-align: center; border-radius: 12px;
    }
    .msg-time { font-size: 0.63rem; margin-top: 4px; opacity: 0.55; }
    .msg-avatar-sm {
        width: 30px; height: 30px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
    }

    /* Input Area */
    .chat-input-wrap {
        padding: 14px 20px; background: white;
        border-top: 1px solid #f1f5f9;
        display: flex; gap: 10px; align-items: flex-end;
    }
    .chat-textarea {
        flex: 1; border: 2px solid #e2e8f0; border-radius: 14px;
        padding: 11px 16px; font-size: 0.87rem; resize: none;
        outline: none; transition: border-color 0.2s; max-height: 110px;
        font-family: inherit; line-height: 1.5;
    }
    .chat-textarea:focus { border-color: #4f46e5; }
    .chat-send-btn-premium {
        width: 46px; height: 46px; border-radius: 13px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none; color: white; font-size: 1rem;
        cursor: pointer; transition: all 0.2s; flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(79,70,229,0.35);
        display: flex; align-items: center; justify-content: center;
    }
    .chat-send-btn-premium:hover { transform: scale(1.08); box-shadow: 0 6px 18px rgba(79,70,229,0.45); }

    /* Status badge */
    .status-badge-chat {
        font-size: 0.7rem; padding: 4px 12px; border-radius: 20px; font-weight: 700;
    }
    .status-open-chat  { background: #dcfce7; color: #16a34a; }
    .status-closed-chat { background: #fef3c7; color: #d97706; }

    @media (max-width: 991px) {
        .room-list { max-height: 300px; }
        .card[style*="calc(100vh"] { height: auto !important; min-height: 450px !important; }
    }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let currentRoomId   = null;
    let currentStatus   = 'open';
    let pollingInterval = null;

    function openRoom(roomId, name, regNo, status) {
        currentRoomId = roomId;
        currentStatus = status;

        // Toggle visibility using classes to override Bootstrap !important
        const emptyState = document.getElementById('chatEmptyState');
        const activeArea = document.getElementById('chatActiveArea');
        
        emptyState.classList.add('d-none');
        emptyState.classList.remove('d-flex');
        
        activeArea.classList.remove('d-none');
        activeArea.classList.add('d-flex');

        // Update header
        document.getElementById('chatAvatarLetter').textContent = name.charAt(0).toUpperCase();
        document.getElementById('chatName').textContent = name;
        document.getElementById('chatRegNo').textContent = regNo;
        updateStatusUI(status);

        // Highlight active room
        document.querySelectorAll('.room-item').forEach(el => el.classList.remove('active'));
        const roomEl = document.getElementById('room_' + roomId);
        if (roomEl) roomEl.classList.add('active');

        // Clear unread badge
        const badge = document.getElementById('badge_' + roomId);
        if (badge) badge.remove();

        loadMessages();
        clearInterval(pollingInterval);
        pollingInterval = setInterval(loadMessages, 5000);
    }

    function updateStatusUI(status) {
        const badge  = document.getElementById('chatStatusBadge');
        const label  = document.getElementById('toggleStatusLabel');
        const icon   = document.getElementById('toggleStatusIcon');
        const input  = document.getElementById('chatInputArea');

        if (status === 'open') {
            badge.className = 'status-badge-chat status-open-chat';
            badge.textContent = 'Aktif';
            label.textContent = 'Tutup Chat';
            icon.className = 'fas fa-lock mr-1';
            input.style.opacity = '1';
            input.style.pointerEvents = 'auto';
        } else {
            badge.className = 'status-badge-chat status-closed-chat';
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
                currentStatus = res.room_status;

                // Update header unread badge
                const hBadge = document.querySelector('.card-body h2 .badge-danger');
                if (hBadge) {
                    hBadge.textContent = res.unread_admin;
                    hBadge.style.display = res.unread_admin > 0 ? '' : 'none';
                }
            });
    }

    function renderMessages(messages) {
        const container = document.getElementById('chatMessages');
        const atBottom  = container.scrollHeight - container.scrollTop <= container.clientHeight + 80;

        container.innerHTML = messages.map(m => {
            if (m.sender_type === 'system') {
                return `<div class="msg-bubble system">${m.message}<div class="msg-time">${m.time}</div></div>`;
            }
            const isAdmin    = m.sender_type === 'admin';
            const rowClass   = isAdmin ? 'from-admin' : '';
            const bubClass   = isAdmin ? 'admin' : 'student';
            const avatarStyle= isAdmin
                ? 'background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;'
                : 'background:#e2e8f0;color:#64748b;';
            const letter     = isAdmin ? m.sender_name.charAt(0) : '👤';
            const timeAlign  = isAdmin ? 'text-align:right;' : '';

            return `
                <div class="msg-row ${rowClass}">
                    <div class="msg-avatar-sm" style="${avatarStyle}">${letter}</div>
                    <div>
                        <div class="msg-bubble ${bubClass}">${m.message}</div>
                        <div class="msg-time text-muted" style="font-size:0.63rem;${timeAlign}">${m.time}</div>
                    </div>
                </div>`;
        }).join('');

        if (atBottom) container.scrollTop = container.scrollHeight;
    }

    function sendAdminReply() {
        const input = document.getElementById('adminMessageInput');
        const msg   = input.value.trim();
        if (!msg || !currentRoomId || currentStatus === 'closed') return;

        input.value = '';
        input.style.height = 'auto';

        $.ajax({
            url: `{{ url('/admission/ppdb/chat') }}/${currentRoomId}/send`,
            method: 'POST',
            data: { message: msg, _token: '{{ csrf_token() }}' },
            success: res => { if (res.success) loadMessages(); },
            error: () => Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pesan gagal terkirim.' })
        });
    }

    function toggleRoomStatus() {
        if (!currentRoomId) return;
        $.post(`{{ url('/admission/ppdb/chat') }}/${currentRoomId}/toggle`, { _token: '{{ csrf_token() }}' })
            .done(res => {
                currentStatus = res.status;
                updateStatusUI(res.status);
                // Update room item badge
                const roomEl = document.getElementById('room_' + currentRoomId);
                if (roomEl) {
                    const badge = roomEl.querySelector('.badge-xs');
                    if (badge) {
                        if (res.status === 'closed') {
                            badge.style.background = '#fef3c7'; badge.style.color = '#d97706'; badge.textContent = 'Ditutup';
                        } else {
                            badge.style.background = '#dcfce7'; badge.style.color = '#16a34a'; badge.textContent = 'Aktif';
                        }
                    }
                }
            });
    }

    // Auto-resize textarea
    document.addEventListener('DOMContentLoaded', function() {
        const ta = document.getElementById('adminMessageInput');
        if (ta) {
            ta.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 110) + 'px';
            });
        }
    });
</script>
@endpush
