@extends ('layouts.template') 

@section('content') 
<div class="card card-outline card-primary"> 
    <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('supplier/create') }}">Add</a> 
            <button onclick="modalAction('{{ url('supplier/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Add Ajax</button>
        </div> 
    </div> 
    <div class="card-body"> 
        @if (session('success')) 
            <div class="alert alert-success">{{ session('success') }}</div> 
        @endif 
        @if (session('error')) 
            <div class="alert alert-danger">{{ session('error') }}</div> 
        @endif
        <div class="row"> 
            <div class="col-md-12"> 
                <div class="form-group row"> 
                    <label class="col-1 control-label col-form-label">Filter: </label> 
                    <div class="col-3"> 
                        <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" placeholder="Cari Nama Supplier">
                        <small class="form-text text-muted">Nama Supplier</small> 
                    </div> 
                </div> 
            </div> 
        </div>        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier"> 
            <thead> 
                <tr>
                    <th>ID</th>
                    <th>Supplier Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr> 
            </thead> 
        </table> 
    </div> 
</div> 
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-
backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection 

@push('css') 
@endpush 

@push('js') 
<script> 
function modalAction(url= ''){
    $('#myModal').load(url,function(){
        $('#myModal').modal('show');
    });
}

var dataSupplier;
$(document).ready(function() { 
        dataSupplier = $('#table_supplier').DataTable({ 
        serverSide: true, 
        ajax: { 
            "url": "{{ url('supplier/list') }}", 
            "dataType": "json", 
            "type": "POST", 
            "data": function (d) {
                d.nama_supplier = $('#nama_supplier').val();
            }
        },
        columns: [
            { 
                data: "DT_RowIndex", 
                className: "text-center", 
                orderable: false, 
                searchable: false 
            }, 
            { 
                data: "nama_supplier", 
                className: "", 
                orderable: true, 
                searchable: true 
            }, 
            { 
                data: "alamat",
                className: "", 
                orderable: true, 
                searchable: true 
            },
            { 
                data: "telepon",
                className: "", 
                orderable: true, 
                searchable: true 
            },
            { 
                data: "email",
                className: "", 
                orderable: true, 
                searchable: true 
            },
            { 
                data: "action", 
                className: "", 
                orderable: false, 
                searchable: false 
            } 
        ] 
    }); 

    $('#nama_supplier').on('keyup change', function() {
        dataSupplier.ajax.reload();
    });

}); 
</script> 
@endpush
