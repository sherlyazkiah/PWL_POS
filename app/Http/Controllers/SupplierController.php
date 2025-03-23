<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    // Menampilkan halaman awal supplier
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];
    
        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];
    
        $activeMenu = 'supplier'; // Set menu yang sedang aktif

        $suppliers = SupplierModel::all();
    
        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $suppliers, 'activeMenu' => $activeMenu]);
    }
    
    // Fetch supplier data in json form for datatables 
    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'nama_supplier', 'alamat', 'telepon', 'email');

        // Filter data supplier berdasarkan nama_supplier
        if ($request->nama_supplier) {
            $suppliers->where('nama_supplier', 'like', "%{$request->nama_supplier}%");
        }

        return DataTables::of($suppliers)
            ->addIndexColumn()
            ->addColumn('action', function ($supplier) {
                $btn = '<a href="'.url('/supplier/' . $supplier->supplier_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/supplier/' . $supplier->supplier_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/supplier/'.$supplier->supplier_id) .'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Did you delete this data?\');" >Delete</button>
                </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Menampilkan halaman form tambah supplier
    public function create() 
    { 
        $breadcrumb = (object) [ 
            'title' => 'Tambah Supplier', 
            'list' => ['Home', 'Supplier', 'Tambah']
        ]; 
        
        $page = (object) [ 
            'title' => 'Tambah supplier baru' 
        ]; 
        
        $supplier = SupplierModel::all(); 
        $activeMenu = 'supplier'; 
        
        return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data supplier baru 
    public function store(Request $request) 
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:20|unique:m_supplier,telepon',
            'email' => 'required|email|unique:m_supplier,email'
        ]);

        SupplierModel::create($request->all()); 

        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }

    // Menampilkan detail supplier
    public function show(string $id) 
    {
        $supplier = SupplierModel::find($id);

        $breadcrumb = (object) [ 
            'title' => 'Detail Supplier', 
            'list' => ['Home', 'Supplier', 'Detail'] 
        ]; 

        $page = (object) [ 
            'title' => 'Detail supplier' 
        ]; 

        $activeMenu = 'supplier'; 

        return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit supplier 
    public function edit(string $id) 
    { 
        $supplier = SupplierModel::find($id); 
        $suppliers = SupplierModel::all(); 
        
        $breadcrumb = (object) [ 
            'title' => 'Edit Supplier', 
            'list' => ['Home', 'Supplier', 'Edit'] 
        ]; 
        
        $page = (object) [ 
            'title' => 'Edit supplier' 
        ]; 
        
        $activeMenu = 'supplier'; 
        
        return view('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu, 'suppliers' => $suppliers]); 
    } 

    // Menyimpan perubahan data supplier
    public function update(Request $request, string $id) 
    { 
        $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:20|unique:m_supplier,telepon,' . $id . ',supplier_id',
            'email' => 'required|email|unique:m_supplier,email,' . $id . ',supplier_id'
        ]);        
        
        SupplierModel::find($id)->update([ 
            'nama_supplier' => $request->nama_supplier, 
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email
        ]); 
        
        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah'); 
    }

    // Menghapus data supplier 
    public function destroy(string $id) 
    {
        $check = SupplierModel::find($id); 
        if (!$check) {
            return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan'); 
        } 
        
        try { 
            SupplierModel::destroy($id);    
            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus'); 
        } 
        
        catch (\Illuminate\Database\QueryException $e) { 
            return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'); 
        } 
    }
}
