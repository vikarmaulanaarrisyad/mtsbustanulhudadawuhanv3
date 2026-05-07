<?php

namespace App\Http\Controllers;

use App\Models\DocumentVerification;
use App\Models\Setting;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Publicly verify a document code.
     */
    public function verify($code)
    {
        $verification = DocumentVerification::with('student')->where('verification_code', $code)->first();
        $setting = Setting::first();

        if (!$verification) {
            return view('public.verification.invalid');
        }

        return view('public.verification.show', compact('verification', 'setting'));
    }
}
