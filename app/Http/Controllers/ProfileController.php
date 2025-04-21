<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function prosesFileUpload(Request $request){
        $request->validate([
            'profile_photo' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $namaFile = $request->profile_photo->getClientOriginalName();
        $path = $request->profile_photo->storeAs('profile_photos', $namaFile, 'public');
        session(['photo' => $path]); // disimpan di session sampai logout atau manual dihapus
        return back();
        // Kirim path ke view via session flash hanya untuk 1 request setelah upload
        // return back()->with('photo', $path);
    }
}
