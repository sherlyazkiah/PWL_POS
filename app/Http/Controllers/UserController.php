<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        // pastikan m_level memiliki level_id = 4, karena level_id adalah foreign key
        // di tabel m_user, maka kalau tidak ada akan error
        DB::table('m_level')->insertOrIgnore([
            'level_id' => 4,
            'level_kode' => 'CUST',
            'level_nama' => 'Customer'
        ]);

        // tambah data user dengan Eloquent Model
        $data = [
            'nama' => 'Pelanggan Pertama'
        ];
        UserModel::where('username', 'customer-1')->update($data); // update data user

        //coba akses model UserModel
        $user = UserModel::all(); // ambil semua data dari tabel m_user
        return view('user', ['data' => $user]);
    }
}
