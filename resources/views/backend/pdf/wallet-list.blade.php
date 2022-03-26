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
                <th>UserId</th>
                <th>Account Number</th>
                <th>Amount</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($wallets as $wallet)
                <tr>
                    <th>{{ $wallet->id }}</th>
                    <th>{{ $wallet->user_id }}</th>
                    <th>{{ $wallet->account_number }}</th>
                    <th>{{ $wallet->amount }}</th>
                    <th>{{ $wallet->created_at }}</th>
                    <th>{{ $wallet->updated_at }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>    





        
</body>
</html>