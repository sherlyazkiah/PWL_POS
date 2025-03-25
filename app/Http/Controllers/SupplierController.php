<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                /*$btn = '<a href="'.url('/supplier/' . $supplier->supplier_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/supplier/' . $supplier->supplier_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/supplier/'.$supplier->supplier_id) .'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Did you delete this data?\');" >Delete</button>
                </form>';*/
                $btn = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Delete</button> ';
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

    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_supplier' => 'required|string|max:100',
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|string|max:20|unique:m_supplier,telepon',
                'email' => 'required|email|unique:m_supplier,email'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            SupplierModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data supplier berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_supplier' => 'required|string|max:100',
                'alamat' => 'required|string|max:255',
                'telepon' => 'required|string|max:20|unique:m_supplier,telepon,'.$id.',supplier_id',
                'email' => 'required|email|unique:m_supplier,email,'.$id.',supplier_id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil diperbarui'
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
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil dihapus'
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

    public function show_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.show_ajax', ['supplier' => $supplier]);
    }
}
