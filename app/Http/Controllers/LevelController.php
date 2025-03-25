<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    // Menampilkan halaman awal level
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];
    
        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];
    
        $activeMenu = 'level'; // Set menu yang sedang aktif

        $levels = LevelModel::all();
    
        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $levels, 'activeMenu' => $activeMenu]);
    }
    
    // Fetch user data in json form for datatables 
    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

        // Filter data user berdasarkan level_kode
        if ($request->level_kode) {
            $levels->where('level_kode', $request->level_kode);
        }

        return DataTables::of($levels)
            // Add index/no sort column (default column name: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('action', function ($level) { // Add action column
                /*$btn = '<a href="'.url('/level/' . $level->level_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/level/' . $level->level_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/level/'.$level->level_id) .'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Did you delete this data?\');" >Delete</button>
                </form>';*/
                $btn = '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Delete</button> ';
                return $btn;
            })
            ->rawColumns(['action']) // Tells that the action column is HTML
            ->make(true);
    }

    // Menampilkan halaman form tambah level
    public function create() 
    { 
        $breadcrumb = (object) [ 
            'title' => 'Tambah Level', 
            'list' => ['Home', 'Level', 'Tambah']
        ]; 
        
        $page = (object) [ 
            'title' => 'Tambah level baru' 
        ]; 
        
        $level = LevelModel::all(); // Ambil data level untuk ditampilkan di form
        $activeMenu = 'level'; // Set menu yang sedang aktif 
        
        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data level baru 
    public function store(Request $request) 
    {
        $request->validate([ 
            'level_kode' => 'required|string|min:2|unique:m_level,level_kode', 
            'level_nama' => 'required|string|max:100'
        ]); 

        LevelModel::create([ 
            'level_kode' => $request->level_kode, 
            'level_nama' => $request->level_nama
        ]); 

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    // Menampilkan detail level
    public function show(string $id) 
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object) [ 
            'title' => 'Detail Level', 
            'list' => ['Home', 'Level', 'Detail'] 
        ]; 

        $page = (object) [ 
            'title' => 'Detail level' 
        ]; 

        $activeMenu = 'level'; // set menu yang sedang aktif 

        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit level 
    public function edit(string $id) 
    { 
        $level = LevelModel::find($id); 
        $levelOptions = LevelModel::all(); 
        
        $breadcrumb = (object) [ 
            'title' => 'Edit Level', 
            'list' => ['Home', 'Level', 'Edit'] 
        ]; 
        
        $page = (object) [ 
            'title' => 'Edit level' 
        ]; 
        
        $activeMenu = 'level'; // set menu yang sedang aktif 
        
        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'levelOptions' => $levelOptions]); 
    } 

    // Menyimpan perubahan data level
    public function update(Request $request, string $id) 
    { 
        $request->validate([ 
            'level_kode' => 'required|string|min:2|unique:m_level,level_kode,'.$id.',level_id', 
            'level_nama' => 'required|string|max:100'
        ]); 
        
        LevelModel::find($id)->update([ 
            'level_kode' => $request->level_kode, 
            'level_nama' => $request->level_nama
        ]); 
        
        return redirect('/level')->with('success', 'Data level berhasil diubah'); 
    }

    // Menghapus data user 
    public function destroy(string $id) 
    {
        $check = LevelModel::find($id); 
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan'); 
        } 
        
        try { 
            LevelModel::destroy($id);    // Hapus data user 
            return redirect('/level')->with('success', 'Data level berhasil dihapus'); 
        } 
        
        catch (\Illuminate\Database\QueryException $e) { 
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error 
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'); 
        } 
    }

    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:2|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            LevelModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:2|unique:m_level,level_kode,'.$id.',level_id',
                'level_nama' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $check = LevelModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil diperbarui'
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

    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
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
}
