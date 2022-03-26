<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        table {
            border-collapse: collapse;
        }

        *, ::after, ::before {
            box-sizing: border-box;
        }

        table {
            display: table;
            border-collapse: separate;
            box-sizing: border-box;
            text-indent: initial;
            border-spacing: 2px;
            border-color: grey;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .table td, .table th {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>IP</th>
                <th>User Agent</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($admin_users as $admin_user)
                <tr>
                    <th>{{ $admin_user->id }}</th>
                    <th>{{ $admin_user->name }}</th>
                    <th>{{ $admin_user->email }}</th>
                    <th>{{ $admin_user->phone }}</th>
                    <th>{{ $admin_user->ip }}</th>
                    <th>{{ $admin_user->user_agent }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>    





        
</body>
</html>