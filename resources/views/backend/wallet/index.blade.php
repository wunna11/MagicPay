@extends('backend.layouts.app')
@section('title', 'Wallets')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Wallet</div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('admin.wallet.addAmount') }}" type="button" class="btn btn-primary">Add Amount</a>
    <a href="{{ route('admin.wallet.reduceAmount') }}" type="button" class="btn btn-danger">Reduce Amount</a>
    <a href="{{ route('admin.walletpdf.download')}} " type="button" class="btn btn-success">PDF Download</a>
</div>

<div class="contnet py-3">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover example">
                <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Account Number</th>
                      <th scope="col">Account Person</th>
                      <th scope="col">Amount(MMK)</th>
                      <th scope="col">Created At</th>
                      <th scope="col">Updated At</th>
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
            ajax: "admin/wallet/datatable/ssd",
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'account_number',
                    name: 'account_number'
                },
                {
                    data: 'account_person',
                    name: 'account_person'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
            ]
        });
    } );
</script>
@endsection