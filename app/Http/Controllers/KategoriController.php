<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
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
                $btn = '<a href="'.url('/kategori/' . $kategori->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/kategori/' . $kategori->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/kategori/'.$kategori->kategori_id) .'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Did you delete this data?\');" >Hapus</button>
                </form>';
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
}
