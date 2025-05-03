<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function prosesFileUpload(Request $request){
        $request->validate([
            'profile_photo' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $namaFile = $request->profile_photo->getClientOriginalName();
        $path = $request->profile_photo->storeAs('profile_photos', $namaFile, 'public');

        // Simpan ke session
        session(['photo' => $path]);

        // Simpan ke database
        $userId = Auth::id(); // Ambil ID user yang sedang login
        UserModel::where('user_id', $userId)->update(['photo' => $path]);

        return back();
    }
}
