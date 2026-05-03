<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistrant;
use App\Models\MailSetting;
use App\Models\StudentAdmission;
use Illuminate\Http\Request;

class PpdbVerificationController extends Controller
{
    /**
     * Halaman verifikasi publik (hasil scan QR)
     */
    public function check($regNumber)
    {
        $registrant = PpdbRegistrant::with(['admissionPhase', 'admissionType', 'studentAdmission'])
            ->where('registration_number', $regNumber)
            ->firstOrFail();
            
        $source = MailSetting::first();
        $admission = StudentAdmission::find($registrant->student_admission_id);

        return view('ppdb.public_verify', compact('registrant', 'source', 'admission'));
    }
}
