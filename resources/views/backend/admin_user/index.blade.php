@extends('backend.layouts.app')
@section('title', 'Admin Users')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Admin User</div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('admin.admin-user.create') }}" type="button" class="btn btn-primary">Create Admin User</a>
    <a href="{{ route('admin.pdf.download') }}" target="_blank" type="button" class="btn btn-success">PDF Download</a>
</div>

<div class="content py-3">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover example">
                <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Phone</th>
                      <th scope="col">IP</th>
                      <th scope="col">User Agent</th>
                      <th scope="col">Created At</th>
                      <th scope="col">Updated At</th>
                      <th scope="col" class="no-sort">Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('.example').DataTable({
            processing: true,
            serverSide: true,
            ajax: "admin/admin-user/datatable/ssd",
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'ip',
                    name: 'ip'
                },
                {
                    data: 'user_agent',
                    name: 'user_agent'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            order: [[ 7, "desc" ]],
            columnDefs: [ {
              targets: "no-sort",
              sortable: false
            } ]
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            
            Swal.fire({
              title: 'Are you sure to delete?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/admin-user/' + id,
                    type: 'DELETE',
                    success: function() {
                        table.ajax.reload();
                    }
                });
              }
            });
        });
    } );
</script>
@endsection