@extends('layouts.template')  

@section('content')   
<div class="card card-outline card-primary">     
    <div class="card-header">       
        <h3 class="card-title">{{ $page->title }}</h3>       
        <div class="card-tools"></div>     
    </div>     
    <div class="card-body">       
        <form method="POST" action="{{ url('supplier') }}" class="form-horizontal">         
            @csrf         
            
            <!-- Nama Supplier -->
            <div class="form-group row">           
                <label class="col-1 control-label col-form-label">Nama Supplier</label>           
                <div class="col-11">             
                    <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier') }}" required>
                    @error('nama_supplier')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>         
            </div>

            <!-- Alamat -->
            <div class="form-group row">           
                <label class="col-1 control-label col-form-label">Alamat</label>           
                <div class="col-11">             
                    <textarea class="form-control" id="alamat" name="alamat" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>         
            </div>

            <!-- Telepon -->
            <div class="form-group row">           
                <label class="col-1 control-label col-form-label">Telepon</label>           
                <div class="col-11">             
                    <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon') }}" required>
                    @error('telepon')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>         
            </div>  

            <!-- Email -->
            <div class="form-group row">           
                <label class="col-1 control-label col-form-label">Email</label>           
                <div class="col-11">             
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
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
    </div>   
</div> 
@endsection 

@push('css') 
@endpush 

@push('js') 
@endpush
