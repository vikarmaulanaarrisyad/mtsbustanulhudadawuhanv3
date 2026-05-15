<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PpdbCommitteeController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user.permissions')->get();
        return view('admin.admission.ppdb.committee', compact('teachers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|in:ppdb.verify.berkas,ppdb.verify.daftar_ulang',
            'action' => 'required|in:assign,revoke'
        ]);

        $user = User::findOrFail($request->user_id);
        
        if ($request->action === 'assign') {
            $user->givePermissionTo($request->permission);
            // Also give base ppdb.verify if not already
            if (!$user->hasPermissionTo('ppdb.verify')) {
                $user->givePermissionTo('ppdb.verify');
            }
            $msg = "Izin berhasil diberikan.";
        } else {
            $user->revokePermissionTo($request->permission);
            // If has neither, revoke base ppdb.verify
            if (!$user->hasPermissionTo('ppdb.verify.berkas') && !$user->hasPermissionTo('ppdb.verify.daftar_ulang')) {
                $user->revokePermissionTo('ppdb.verify');
            }
            $msg = "Izin berhasil ditarik.";
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }
}
