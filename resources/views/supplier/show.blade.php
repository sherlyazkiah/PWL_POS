@extends('layouts.template')  

@section('content')   
<div class="card card-outline card-primary">       
    <div class="card-header">         
        <h3 class="card-title">{{ $page->title }}</h3>         
        <div class="card-tools"></div>       
    </div>       
    <div class="card-body">         
        @empty ($supplier)             
            <div class="alert alert-danger alert-dismissible">                 
                <h5><i class="icon fas fa-ban"></i> Error!</h5> 
                The data you are looking for is not found.             
            </div>         
        @else             
            <table class="table table-bordered table-striped table-hover table-sm">                 
                <tr>                     
                    <th>ID</th>                     
                    <td>{{ $supplier->supplier_id }}</td>                 
                </tr>                 
                <tr>                     
                    <th>Nama Supplier</th>                     
                    <td>{{ $supplier->nama_supplier }}</td>                 
                </tr>                 
                <tr>                     
                    <th>Alamat</th>                     
                    <td>{{ $supplier->alamat }}</td>                 
                </tr>                 
                <tr>                     
                    <th>Telepon</th>                     
                    <td>{{ $supplier->telepon }}</td>                 
                </tr>                 
                <tr>                     
                    <th>Email</th>                     
                    <td>{{ $supplier->email }}</td>                 
                </tr>             
            </table>         
        @endempty         
        <a href="{{ url('supplier') }}" class="btn btn-sm btn-default mt-2">Return</a>     
    </div>   
</div> 
@endsection  

@push('css') 
@endpush  

@push('js')  
@endpush  
