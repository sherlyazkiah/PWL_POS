@extends ('layouts.template') 

@section('content') 
<div class="card card-outline card-primary"> 
    <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Add</a> 
        </div> 
    </div> 
    <div class="card-body"> 
        @if (session('success')) 
            <div class="alert alert-success">{{ session('success') }}</div> 
        @endif 
        @if (session('error')) 
            <div class="alert alert-danger">{{ session('error') }}</div> 
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_user"> 
            <thead> 
                <tr><th>ID</th><th>Username</th><th>Name</th><th>User Level</th><th>Action</th></tr> 
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
    var dataUser = $('#table_user').DataTable({ 
        serverSide: true, // if you want to use server-side processing 
        ajax: { 
            "url": "{{ url('user/list') }}", 
            "dataType": "json", 
            "type": "POST" 
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
                data: "username", 
                className: "", 
                orderable: true, // if you want this column to be sortable. 
                searchable: true // if you want this field to be searchable 
            }, 
            { 
                data: "nama",
                className: "", 
                orderable: true, 
                searchable: true 
            }, 
            { 
                // Retrieve result-level data from correlated ORMs 
                data: "level.level_nama", 
                className: "", 
                orderable: false, 
                searchable: false 
            }, 
            { 
                data: "action", 
                className: "", 
                orderable: false, 
                searchable: false 
            } 
        ] 
    }); 
}); 
</script> 
@endpush
