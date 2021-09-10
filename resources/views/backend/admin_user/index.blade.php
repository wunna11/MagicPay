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
</div>

<div class="contnet">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover example">
                <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Phone</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.example').DataTable({
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
                    data: 'action',
                    name: 'action'
                },
            ]
        });
    } );
</script>
@endsection