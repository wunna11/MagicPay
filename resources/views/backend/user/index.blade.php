@extends('backend.layouts.app')
@section('title', 'Users')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>User</div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('admin.user.create') }}" type="button" class="btn btn-primary">Create User</a>
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
                      <th scope="col">Login At</th>
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
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
                {
                    text: "<span>Refresh Tab</span>",
                    action: function (e, dt, node, config) {
                        dt.ajax.reload(null, false);
                    }
                },
                {
                  extend: 'pdfHtml5',
                  text: 'PDF',
                  orientation: 'portrait',
                  pageSize: 'A4',
                  title: 'User List',
                  exportOptions: {
                      columns: [0, 1, 2, 3, 4, 5]
                  },
                  customize: function (doc) {
                                          //Remove the title created by datatTables
                                          doc.content.splice(0,1);
                                          var now = new Date();
                                          var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();
                                          var datetime = "Last Sync: " + now.getDate() + "/"
                                                        + (now.getMonth()+1)  + "/" 
                                                        + now.getFullYear() + " @ "  
                                                        + now.getHours() + ":"  
                                                        + now.getMinutes() + ":" 
                                                        + now.getSeconds();
                                          doc.pageMargins = [20,60,20,30];
                                          doc.defaultStyle.fontSize = 8;
                                          doc.styles.tableHeader.fontSize = 8;
                                          doc.styles.tableBodyEven.alignment = 'center',
                                          doc.styles.tableBodyOdd.alignment = 'center',
 
                                          doc['header']=(function() {
                                              return {
                                                  columns: [
 
                                                      {
                                                          alignment: 'left',
                                                          italics: true,
                                                          text: 'User List Table',
                                                          fontSize: 18,
                                                          margin: [10,0]
                                                      },
                                                      {
                                                          alignment: 'right',
                                                          fontSize: 10,
                                                          text: 'Report Time ' + datetime
                                                      }
                                                  ],
                                                  margin: [20, 20, 20, 0]
                                              }
                                          });
 
                                          doc['footer']=(function(page, pages) {
                                              return {
                                                  columns: [
                                                      {
                                                          alignment: 'left',
                                                          text: ''
                                                      },
                                                      {
                                                          alignment: 'right',
                                                          text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
                                                      }
                                                  ],
                                                  margin: [20, 0, 20, 10]
                                              }
                                          });
 
                                          var objLayout = {};
                                          objLayout['hLineWidth'] = function(i) { return .5; };
                                          objLayout['vLineWidth'] = function(i) { return .5; };
                                          objLayout['hLineColor'] = function(i) { return '#aaa'; };
                                          objLayout['vLineColor'] = function(i) { return '#aaa'; };
                                          objLayout['paddingLeft'] = function(i) { return 4; };
                                          objLayout['paddingRight'] = function(i) { return 4; };
                                          doc.content[0].layout = objLayout;
                                          doc.content[0].table.widths = '16.66667%';
                                  }
                }
            ],  
            processing: true,
            serverSide: true,
            ajax: "admin/user/datatable/ssd",
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
                    data: 'login_at',
                    name: 'login_at'
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
              showCancelButton: true,
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/user/' + id,
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