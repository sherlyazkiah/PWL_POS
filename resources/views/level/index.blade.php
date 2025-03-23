@extends ('layouts.template') 

@section('content') 
<div class="card card-outline card-primary"> 
    <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('level/create') }}">Add</a> 
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
                        <select class="form-control" id="level_kode" name="level_kode" required> 
                            <option value="">- Semua -</option> 
                            @foreach($level as $item) 
                                <option value="{{ $item->level_kode }}">{{ $item->level_kode }}</option> 
                            @endforeach 
                        </select> 
                        <small class="form-text text-muted">Pilih Level</small> 
                    </div> 
                </div> 
            </div> 
        </div>        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_level"> 
            <thead> 
                <tr>
                    <th>ID</th>
                    <th>Level Code</th>
                    <th>Level Name</th>
                    <th>Action</th></tr> 
            </thead> 
        </table> 
    </div> 
</div> 
@endsection 

@push('css') 
@endpush 

@push('js') 
<script> 
$(document).ready(function() { 
    var dataUser = $('#table_level').DataTable({ 
        serverSide: true, // if you want to use server-side processing 
        ajax: { 
            "url": "{{ url('level/list') }}", 
            "dataType": "json", 
            "type": "POST", 
            "data": function (d) {
                d.level_kode = $('#level_kode').val();
            }
        },
        columns: [
            { 
                // Sequence number of Laravel DataTable addIndexColumn() 
                data: "DT_RowIndex", 
                className: "text-center", 
                orderable: false, 
                searchable: false 
            }, 
            { 
                data: "level_kode", 
                className: "", 
                orderable: true, // if you want this column to be sortable. 
                searchable: true // if you want this field to be searchable 
            }, 
            { 
                data: "level_nama",
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

    $('#level_kode').on('change', function() {
        dataUser.ajax.reload();
    });

}); 
</script> 
@endpush
