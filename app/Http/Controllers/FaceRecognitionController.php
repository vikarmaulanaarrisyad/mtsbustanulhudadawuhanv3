<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherFaceDescriptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FaceRecognitionController extends Controller
{
    public function registerView()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        // If not a teacher and not an admin, then block
        if (!$teacher && !$user->hasRole(['Admin', 'Super Admin'])) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $faceDescriptor = $teacher ? TeacherFaceDescriptor::where('teacher_id', $teacher->id)->first() : null;
        
        // If Admin is simulating, they might not have a teacher record, 
        // we'll pass a dummy or null and handle it in the view.
        return view('teacher.face.register', compact('teacher', 'faceDescriptor'));
    }

    public function saveDescriptor(Request $request)
    {
        $request->validate([
            'descriptors' => 'required',
            'image' => 'required'
        ]);

        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return response()->json(['message' => 'Profil Guru tidak ditemukan.'], 422);
        }

        // Save Descriptor
        TeacherFaceDescriptor::updateOrCreate(
            ['teacher_id' => $teacher->id],
            ['descriptors' => json_decode($request->descriptors)]
        );

        // Save Example Image and Update Profile Photo
        if ($request->image) {
            $imageData = $request->image;
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'profile_photos/face_' . $teacher->id . '_' . time() . '.jpg';
            Storage::disk('public')->put($imageName, base64_decode($imageData));
            
            // Update user profile photo
            $user = Auth::user();
            $user->profile_photo_path = $imageName;
            $user->save();
        }

        return response()->json([
            'message' => 'Wajah berhasil didaftarkan!',
            'redirect' => route('dashboard')
        ]);
    }
}
