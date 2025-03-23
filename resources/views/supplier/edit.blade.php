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
            <h5><i class="icon fas fa-ban"></i> Error!</h5> The data you are looking for is not found.         
        </div>         
        <a href="{{ url('supplier') }}" class="btn btn-sm btn-default mt-2">Return</a>       
        @else         
        <form method="POST" action="{{ url('/supplier/'.$supplier->supplier_id) }}" class="form-horizontal">           
            @csrf  
            {!! method_field('PUT') !!}  <!-- Required for updating data -->           

            <!-- Nama Supplier -->
            <div class="form-group row">             
                <label class="col-1 control-label col-form-label">Nama Supplier</label>             
                <div class="col-11">               
                    <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>               
                    @error('nama_supplier')                 
                    <small class="form-text text-danger">{{ $message }}</small>               
                    @enderror             
                </div>           
            </div>           

            <!-- Alamat -->
            <div class="form-group row">             
                <label class="col-1 control-label col-form-label">Alamat</label>             
                <div class="col-11">               
                    <textarea class="form-control" id="alamat" name="alamat" required>{{ old('alamat', $supplier->alamat) }}</textarea>               
                    @error('alamat')                 
                    <small class="form-text text-danger">{{ $message }}</small>               
                    @enderror             
                </div>           
            </div>           

            <!-- Telepon -->
            <div class="form-group row">             
                <label class="col-1 control-label col-form-label">Telepon</label>             
                <div class="col-11">               
                    <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon', $supplier->telepon) }}" required>               
                    @error('telepon')                 
                    <small class="form-text text-danger">{{ $message }}</small>               
                    @enderror             
                </div>           
            </div>           

            <!-- Email -->
            <div class="form-group row">             
                <label class="col-1 control-label col-form-label">Email</label>             
                <div class="col-11">               
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $supplier->email) }}" required>               
                    @error('email')                 
                    <small class="form-text text-danger">{{ $message }}</small>               
                    @enderror             
                </div>           
            </div>           

            <!-- Buttons -->
            <div class="form-group row">             
                <label class="col-1 control-label col-form-label"></label>             
                <div class="col-11">               
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>   
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('supplier') }}">Return</a>             
                </div>           
            </div>         
        </form>       
        @endempty     
    </div>   
</div> 
@endsection  

@push('css') 
@endpush 

@push('js') 
@endpush  
