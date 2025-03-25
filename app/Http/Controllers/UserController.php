<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
    
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
    
        $activeMenu = 'user'; // Set menu yang sedang aktif

        $level = LevelModel::all(); // Ambil data level untuk filter level
    
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    
    // Fetch user data in json form for datatables 
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        // Filter data user berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            // Add index/no sort column (default column name: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('action', function ($user) { // Add action column
                /*$btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id) .'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Did you delete this data?\');" >Delete</button>
                </form>';*/
                $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Delete</button> ';
                return $btn;
            })
            ->rawColumns(['action']) // Tells that the action column is HTML
            ->make(true);
    }

    // Menampilkan halaman form tambah user
    public function create() 
    { 
        $breadcrumb = (object) [ 
            'title' => 'Tambah User', 
            'list' => ['Home', 'User', 'Tambah']
        ]; 
        
        $page = (object) [ 
            'title' => 'Tambah user baru' 
        ]; 
        
        $level = LevelModel::all(); // Ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // Set menu yang sedang aktif 
        
        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data user baru 
    public function store(Request $request) 
    {
        $request->validate([ 
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username 
            'username' => 'required|string|min:3|unique:m_user,username', 
            'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter 
            'password' => 'required|min:5',      // password harus diisi dan minimal 5 karakter 
            'level_id' => 'required|integer'     // level_id harus diisi dan berupa angka 
        ]); 

        UserModel::create([ 
            'username' => $request->username, 
            'nama' => $request->nama, 
            'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan 
            'level_id' => $request->level_id 
        ]); 

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    // Menampilkan detail user 
    public function show(string $id) 
    {
        $user = UserModel::with('level')->find($id); 

        $breadcrumb = (object) [ 
            'title' => 'Detail User', 
            'list' => ['Home', 'User', 'Detail'] 
        ]; 

        $page = (object) [ 
            'title' => 'Detail user' 
        ]; 

        $activeMenu = 'user'; // set menu yang sedang aktif 

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit user 
    public function edit(string $id) 
    { 
        $user = UserModel::find($id); 
        $level = LevelModel::all(); 
        
        $breadcrumb = (object) [ 
            'title' => 'Edit User', 
            'list' => ['Home', 'User', 'Edit'] 
        ]; 
        
        $page = (object) [ 
            'title' => 'Edit user' 
        ]; 
        
        $activeMenu = 'user'; // set menu yang sedang aktif 
        
        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]); 
    } 

    // Menyimpan perubahan data user 
    public function update(Request $request, string $id) 
    { 
        $request->validate([ 
            // username harus diisi, berupa string, minimal 3 karakter, 
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit 
            'username' => 'required|string|min:3|unique:m_user,username,'.$id.',user_id', 
            'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter 
            'password' => 'nullable|min:5',      // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi 
            'level_id' => 'required|integer'     // level_id harus diisi dan berupa angka 
        ]); 
        
        UserModel::find($id)->update([ 
            'username' => $request->username, 
            'nama' => $request->nama, 
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password, 
            'level_id' => $request->level_id 
        ]); 
        
        return redirect('/user')->with('success', 'Data user berhasil diubah'); 
    }

    // Menghapus data user 
    public function destroy(string $id) 
    {
        $check = UserModel::find($id); 
        if (!$check) {      // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak 
            return redirect('/user')->with('error', 'Data user tidak ditemukan'); 
        } 
        
        try { 
            UserModel::destroy($id);    // Hapus data user 
            return redirect('/user')->with('success', 'Data user berhasil dihapus'); 
        } 
        
        catch (\Illuminate\Database\QueryException $e) { 
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error 
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'); 
        } 
    }

    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.create_ajax')
                    ->with('level', $level);
    }

    public function store_ajax(Request $request){
        // cek apakah request berupa AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];

            // gunakan validator (use Illuminate\Support\Facades\Validator)
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            // simpan data user
            UserModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id)
    {
        // Fetch the level data based on level_id
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', ['user'=>$user,'level'=> $level]);
    }

    public function update_ajax(Request $request, $id) {
        // Check if the request is from ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id',
                'nama' => 'required|max:100',
                'password' => 'nullable|min:6|max:20'
            ];
    
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'msgField' => $validator->errors() // Indicates which field has an error
                ]);
            }
    
            $check = UserModel::find($id);
            if ($check) {
                if (!$request->filled('password')) {
                    // If the password is not filled, then remove it from the request
                    $request->request->remove('password');
                }
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data updated successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $user = UserModel::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }
    
    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show_ajax(string $id){
        $user = UserModel::find($id);
        return view('user.show_ajax', ['user' => $user]);
    }
}