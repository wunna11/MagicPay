@extends('frontend.layouts.app')

@section('title', 'Transaction Detail')

@section('content')
<div class="transaction-detail">
    <div class="card">
        <div class="card-body pr-0">
            <div class="text-center mb-3">
                <img src="{{ asset('images/checkmark.png')}}" alt="">
            </div>

            @if (Session('transfer_success'))
            <div class="alert alert-success text-center" role="alert">
                {{ Session('transfer_success') }}
            </div>
            @endif

            @if ($transaction->type == 1) 
                <h5 class="text-center text-success mb-4">+{{ number_format($transaction->amount) }}</h5>
            @elseif ($transaction->type == 2)
                <h5 class="text-center text-danger mb-4">-{{ number_format($transaction->amount) }}<small>MMK</small></h5>
            @endif
        </div>
        <div class="d-flex justify-content-between">
            <span class="ml-3 mb-0">Transaction ID</span>
            <span class="mr-3 mb-0">{{ $transaction->trx_id }}</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <span class="ml-3 mb-0">Refrence Number</span>
            <span class="mr-3 mb-0">{{ $transaction->ref_no }}</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <span class="ml-3 mb-0">Send</span>
            <span class="mr-3 mb-0">{{ $transaction->source->phone }}({{ $transaction->source->name}})</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <span class="ml-3 mb-0">Amount</span>
            <span class="mr-3 mb-0">{{ number_format($transaction->amount) }}<small>MMK</small></span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <span class="ml-3 mb-0">Type</span>
            @if ($transaction->type == 1)
                <span class="mr-3 mb-0 badge badge-pill badge-success">Income</span>
            @elseif ($transaction->type == 2)
                <span class="mr-3 mb-0 badge badge-pill badge-danger mb-3">Expense</span>
            @endif
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <span class="ml-3 mb-0">Description</span>
            <span class="mr-3 mb-0">{{ $transaction->description }}</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-3">
            <span class="ml-3 mb-0">Date</span>
            <span class="mr-3 mb-0">{{ date('d-m-Y / H:i:s', strtotime($transaction->created_at)) }}</span>
        </div>
    </div>
</div>

@endsection
