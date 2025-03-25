@extends ('layouts.template') 

@section('content') 
<div class="card card-outline card-primary"> 
    <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Add</a> 
            <button onclick="modalAction('{{ url('barang/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Add Ajax</button>
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
                        <select class="form-control" id="barang_kode" name="barang_kode" required> 
                            <option value="">- Semua -</option> 
                            @foreach($barang as $item) 
                                <option value="{{ $item->barang_kode }}">{{ $item->barang_kode }}</option> 
                            @endforeach 
                        </select> 
                        <small class="form-text text-muted">Pilih Kode Barang</small> 
                    </div> 
                </div> 
            </div> 
        </div>        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang"> 
            <thead> 
                <tr>
                    <th>ID</th>
                    <th>Goods Code</th>
                    <th>Goods Name</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Action</th></tr> 
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

var dataUser;
$(document).ready(function() { 
    var dataUser = $('#table_barang').DataTable({ 
        serverSide: true, 
        ajax: { 
            "url": "{{ url('barang/list') }}", 
            "dataType": "json", 
            "type": "POST", 
            "data": function (d) {
                d.barang_kode = $('#barang_kode').val();
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
                data: "barang_kode", 
                className: "", 
                orderable: true, 
                searchable: true 
            }, 
            { 
                data: "barang_nama",
                className: "", 
                orderable: true, 
                searchable: true 
            },
            { 
                data: "harga_beli",
                className: "", 
                orderable: true, 
                searchable: true,
                render: function(data, type, row) {
                    return new Intl.NumberFormat('id-ID').format(data);
                }

            },
            { 
                data: "harga_jual",
                className: "", 
                orderable: true, 
                searchable: true,
                render: function(data, type, row) {
                    return new Intl.NumberFormat('id-ID').format(data);
                }
            },
            { 
                data: "action", 
                className: "", 
                orderable: false, 
                searchable: false 
            } 
        ] 
    }); 

    $('#barang_kode').on('change', function() {
        dataUser.ajax.reload();
    });

}); 
</script> 
@endpush
