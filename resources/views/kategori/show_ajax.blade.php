@empty($kategori)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Error</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="fa fa-ban icon"></i> Error!!</h5>
                The data you are looking for is not found
            </div>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Level Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>                     
                    <th>ID</th>                     
                    <td>{{ $kategori->kategori_id }}</td>                 
                </tr>                 
                <tr>                     
                    <th>Category Code</th>                     
                    <td>{{ $kategori->kategori_kode }}</td>                 
                </tr>                 
                <tr>                     
                    <th>Category Name</th>                     
                    <td>{{ $kategori->kategori_nama }}</td>                 
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default mt-2">Return</button>
        </div>
    </div>
</div>
@endempty