<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_active', true)
            ->whereIn('type', ['Umum', 'Guru'])
            ->latest()
            ->paginate(10);
            
        return view('admin.announcement.admin_index', compact('announcements'));
    }

    public function teacherIndex()
    {
        $announcements = Announcement::where('is_active', true)
            ->whereIn('type', ['Umum', 'Guru'])
            ->latest()
            ->get();
            
        return view('admin.announcement.teacher_index', compact('announcements'));
    }

    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Mark as read
        AnnouncementRead::firstOrCreate([
            'announcement_id' => $announcement->id,
            'user_id' => Auth::id()
        ]);

        if (request()->ajax()) {
            return response()->json(['data' => $announcement]);
        }

        return view('admin.announcement.show', compact('announcement'));
    }

    // Admin Methods
    public function adminIndex()
    {
        return view('admin.announcement.admin_index');
    }

    public function data()
    {
        $query = Announcement::latest()->get();
        return datatables($query)
            ->addIndexColumn()
            ->editColumn('is_active', fn($q) => $q->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>')
            ->addColumn('action', function($q) {
                return '<button onclick="editForm(`' . route('announcements.show', $q->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-pencil-alt"></i></button>
                        <button onclick="deleteData(`' . route('announcements.destroy', $q->id) . '`)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'type' => 'required'
        ]);

        Announcement::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . rand(100, 999),
            'content' => $request->content,
            'type' => $request->type,
            'is_active' => $request->is_active ?? true,
            'user_id' => Auth::id()
        ]);

        return response()->json(['message' => 'Pengumuman berhasil diterbitkan.']);
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update($request->all());
        return response()->json(['message' => 'Pengumuman berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return response()->json(['message' => 'Pengumuman berhasil dihapus.']);
    }
}
