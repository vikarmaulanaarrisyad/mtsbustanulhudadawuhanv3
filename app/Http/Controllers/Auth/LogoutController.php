<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $isAdmin = false;

        if ($user) {
            // Cek apakah user memiliki role admin atau super admin
            if ($user->hasRole(['Super Admin', 'Admin'])) {
                $isAdmin = true;
            }
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Jika admin, arahkan ke halaman utama (front)
        // Jika guru/siswa/lainnya, arahkan ke halaman login
        if ($isAdmin) {
            return redirect('/');
        }

        return redirect('/login');
    }
}
