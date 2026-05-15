<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbChatRoom;
use App\Models\PpdbChatMessage;
use App\Models\PpdbRegistrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PpdbChatController extends Controller
{
    // ========================================================
    // ADMIN SIDE
    // ========================================================

    /**
     * Inbox admin: list semua ruang chat.
     */
    public function adminInbox()
    {
        $rooms = PpdbChatRoom::with(['registrant', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        $totalUnread = PpdbChatRoom::sum('unread_admin');

        return view('admin.admission.ppdb.chat', compact('rooms', 'totalUnread'));
    }

    /**
     * Ambil pesan dalam satu room (AJAX polling — admin & siswa).
     */
    public function getMessages(Request $request, $roomId)
    {
        $room = PpdbChatRoom::findOrFail($roomId);

        // Otorisasi: admin atau pemilik registrant
        $user = Auth::user();
        $isAdmin = $user->can('ppdb.view');
        $isOwner = $room->registrant->user_id === $user->id;

        if (!$isAdmin && !$isOwner) {
            abort(403);
        }

        $messages = $room->messages()
            ->with('user:id,name')
            ->get()
            ->map(fn($m) => [
                'id'          => $m->id,
                'sender_type' => $m->sender_type,
                'sender_name' => $m->sender_type === 'admin' ? ($m->user?->name ?? 'Panitia') : 'Anda',
                'message'     => $m->message,
                'is_read'     => $m->is_read,
                'time'        => $m->created_at,
            ]);

        // Tandai semua pesan sebagai sudah dibaca
        if ($isAdmin) {
            $room->messages()->where('sender_type', 'student')->where('is_read', false)->update(['is_read' => true]);
            $room->update(['unread_admin' => 0]);
        } else {
            $room->messages()->where('sender_type', 'admin')->where('is_read', false)->update(['is_read' => true]);
            $room->update(['unread_student' => 0]);
        }

        return response()->json([
            'messages'      => $messages,
            'room_status'   => $room->status,
            'unread_admin'  => PpdbChatRoom::sum('unread_admin'),
        ]);
    }

    /**
     * Admin kirim pesan.
     */
    public function sendAdminMessage(Request $request, $roomId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $room = PpdbChatRoom::findOrFail($roomId);

        $message = PpdbChatMessage::create([
            'ppdb_chat_room_id' => $room->id,
            'user_id'           => Auth::id(),
            'sender_type'       => 'admin',
            'message'           => $request->message,
            'is_read'           => false,
        ]);

        $room->update([
            'unread_student' => $room->unread_student + 1,
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id'          => $message->id,
                'sender_type' => 'admin',
                'sender_name' => Auth::user()->name,
                'message'     => $message->message,
                'time'        => $message->created_at->format('d M Y, H:i'),
            ],
        ]);
    }

    /**
     * Admin tutup/buka kembali percakapan.
     */
    public function toggleStatus(Request $request, $roomId)
    {
        $room = PpdbChatRoom::findOrFail($roomId);
        $room->update(['status' => $room->status === 'open' ? 'closed' : 'open']);

        return response()->json(['success' => true, 'status' => $room->status]);
    }

    // ========================================================
    // STUDENT SIDE
    // ========================================================

    /**
     * Ambil atau buat room untuk siswa yang login.
     */
    public function studentGetRoom()
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant;

        if (!$registrant) {
            return response()->json(['error' => 'Pendaftaran tidak ditemukan.'], 404);
        }

        $room = PpdbChatRoom::firstOrCreate(
            ['ppdb_registrant_id' => $registrant->id],
            ['status' => 'open', 'last_message_at' => now()]
        );

        // Auto-greeting jika room baru (belum ada pesan)
        if ($room->wasRecentlyCreated) {
            PpdbChatMessage::create([
                'ppdb_chat_room_id' => $room->id,
                'user_id'           => null,
                'sender_type'       => 'system',
                'message'           => '👋 Halo! Selamat datang di Helpdesk PPDB. Silakan ketik pertanyaan Anda, dan panitia akan membalas secepatnya. Terima kasih!',
                'is_read'           => true,
            ]);
        }

        return response()->json(['room_id' => $room->id, 'status' => $room->status]);
    }

    /**
     * Siswa kirim pesan.
     */
    public function sendStudentMessage(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000', 'room_id' => 'required|exists:ppdb_chat_rooms,id']);

        $user = Auth::user();
        $room = PpdbChatRoom::findOrFail($request->room_id);

        // Pastikan room ini milik user yang login
        if ($room->registrant->user_id !== $user->id) {
            abort(403);
        }

        if ($room->status === 'closed') {
            return response()->json(['error' => 'Percakapan ini telah ditutup oleh panitia.'], 403);
        }

        $message = PpdbChatMessage::create([
            'ppdb_chat_room_id' => $room->id,
            'user_id'           => $user->id,
            'sender_type'       => 'student',
            'message'           => $request->message,
            'is_read'           => false,
        ]);

        $room->update([
            'unread_admin'    => $room->unread_admin + 1,
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id'          => $message->id,
                'sender_type' => 'student',
                'message'     => $message->message,
                'time'        => $message->created_at->format('d M Y, H:i'),
            ],
        ]);
    }
}
