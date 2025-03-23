<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    // Menampilkan halaman awal barang
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];
    
        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];
    
        $activeMenu = 'barang'; // Set menu yang sedang aktif

        $barangs = BarangModel::all();
    
        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barangs, 'activeMenu' => $activeMenu]);
    }
    
    // Fetch barang data in json form for datatables 
    public function list(Request $request)
    {
        $barangs = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');

        // Filter data barang berdasarkan barang_kode
        if ($request->barang_kode) {
            $barangs->where('barang_kode', $request->barang_kode);
        }

        return DataTables::of($barangs)
            ->addIndexColumn()
            ->addColumn('action', function ($barang) {
                $btn = '<a href="'.url('/barang/' . $barang->barang_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/barang/' . $barang->barang_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/barang/'.$barang->barang_id) .'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Did you delete this data?\');" >Hapus</button>
                </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Menampilkan halaman form tambah barang
    public function create() 
    { 
        $breadcrumb = (object) [ 
            'title' => 'Tambah Barang', 
            'list' => ['Home', 'Barang', 'Tambah']
        ]; 
        
        $page = (object) [ 
            'title' => 'Tambah barang baru' 
        ]; 
        
        $barang = BarangModel::all();
        $activeMenu = 'barang'; 
        
        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data barang baru 
    public function store(Request $request) 
    {
        $request->validate([ 
            'barang_kode' => 'required|string|max:50|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0'
        ]); 

        BarangModel::create($request->all()); 

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    // Menampilkan detail barang
    public function show(string $id) 
    {
        $barang = BarangModel::find($id);

        $breadcrumb = (object) [ 
            'title' => 'Detail Barang', 
            'list' => ['Home', 'Barang', 'Detail'] 
        ]; 

        $page = (object) [ 
            'title' => 'Detail barang' 
        ]; 

        $activeMenu = 'barang';

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit barang 
    public function edit(string $id) 
    { 
        $barang = BarangModel::find($id); 
        $barangs = BarangModel::all(); 
        
        $breadcrumb = (object) [ 
            'title' => 'Edit Barang', 
            'list' => ['Home', 'Barang', 'Edit'] 
        ]; 
        
        $page = (object) [ 
            'title' => 'Edit barang' 
        ]; 
        
        $activeMenu = 'barang'; 
        
        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu, 'barangs' => $barangs]); 
    } 

    // Menyimpan perubahan data barang
    public function update(Request $request, string $id) 
    { 
        $request->validate([ 
            'barang_kode' => 'required|string|max:50|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0'
        ]); 
        
        BarangModel::find($id)->update([ 
            'barang_kode' => $request->barang_kode, 
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]); 
        
        return redirect('/barang')->with('success', 'Data barang berhasil diubah'); 
    }

    // Menghapus data barang
    public function destroy(string $id) 
    {
        $check = BarangModel::find($id); 
        if (!$check) {
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan'); 
        } 
        
        try { 
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus'); 
        } 
        
        catch (\Illuminate\Database\QueryException $e) { 
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'); 
        } 
    }
}
