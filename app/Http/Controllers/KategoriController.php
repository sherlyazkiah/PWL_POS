<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    // Menampilkan halaman awal kategori
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];
    
        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem'
        ];
    
        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        $kategoris = KategoriModel::all();
    
        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategoris, 'activeMenu' => $activeMenu]);
    }
    
    // Fetch kategori data in json form for datatables 
    public function list(Request $request)
    {
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        // Filter data kategori berdasarkan kategori_kode
        if ($request->kategori_kode) {
            $kategoris->where('kategori_kode', $request->kategori_kode);
        }

        return DataTables::of($kategoris)
            ->addIndexColumn()
            ->addColumn('action', function ($kategori) {
                /*$btn = '<a href="'.url('/kategori/' . $kategori->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/kategori/' . $kategori->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/kategori/'.$kategori->kategori_id) .'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Did you delete this data?\');" >Hapus</button>
                </form>';*/
                $btn = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Menampilkan halaman form tambah kategori
    public function create() 
    { 
        $breadcrumb = (object) [ 
            'title' => 'Tambah Kategori', 
            'list' => ['Home', 'Kategori', 'Tambah']
        ]; 
        
        $page = (object) [ 
            'title' => 'Tambah kategori baru' 
        ]; 
        
        $kategori = KategoriModel::all();
        $activeMenu = 'kategori'; 
        
        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data kategori baru 
    public function store(Request $request) 
    {
        $request->validate([ 
            'kategori_kode' => 'required|string|min:2|unique:m_kategori,kategori_kode', 
            'kategori_nama' => 'required|string|max:100'
        ]); 

        KategoriModel::create([ 
            'kategori_kode' => $request->kategori_kode, 
            'kategori_nama' => $request->kategori_nama
        ]); 

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    // Menampilkan detail kategori
    public function show(string $id) 
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object) [ 
            'title' => 'Detail Kategori', 
            'list' => ['Home', 'Kategori', 'Detail'] 
        ]; 

        $page = (object) [ 
            'title' => 'Detail kategori' 
        ]; 

        $activeMenu = 'kategori';

        return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit kategori 
    public function edit(string $id) 
    { 
        $kategori = KategoriModel::find($id); 
        $kategoris = KategoriModel::all(); 
        
        $breadcrumb = (object) [ 
            'title' => 'Edit Kategori', 
            'list' => ['Home', 'Kategori', 'Edit'] 
        ]; 
        
        $page = (object) [ 
            'title' => 'Edit kategori' 
        ]; 
        
        $activeMenu = 'kategori'; 
        
        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu, 'kategoris' => $kategoris]); 
    } 

    // Menyimpan perubahan data kategori
    public function update(Request $request, string $id) 
    { 
        $request->validate([ 
            'kategori_kode' => 'required|string|min:2|unique:m_kategori,kategori_kode,'.$id.',kategori_id', 
            'kategori_nama' => 'required|string|max:100'
        ]); 
        
        KategoriModel::find($id)->update([ 
            'kategori_kode' => $request->kategori_kode, 
            'kategori_nama' => $request->kategori_nama
        ]); 
        
        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah'); 
    }

    // Menghapus data kategori
    public function destroy(string $id) 
    {
        $check = KategoriModel::find($id); 
        if (!$check) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan'); 
        } 
        
        try { 
            KategoriModel::destroy($id);
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus'); 
        } 
        
        catch (\Illuminate\Database\QueryException $e) { 
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'); 
        } 
    }

    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|unique:m_kategori,kategori_kode,'.$id.',kategori_id',
                'kategori_nama' => 'required|string|max:100'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->update($request->all());
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
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }
    
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->delete();
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
        $kategori = KategoriModel::find($id);
        return view('kategori.show_ajax', ['kategori' => $kategori]);
    }
}
