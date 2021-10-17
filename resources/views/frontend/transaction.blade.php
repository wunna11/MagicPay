@extends('frontend.layouts.app')

@section('title', 'Transaction')

@section('content')
<div class="transaction">
    @foreach ($transactions as $transaction)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h6>ID: {{ $transaction->trx_id }}</h6>
                    @if ($transaction->type == 1)
                        <p class="mb-1 text-success">+{{ $transaction->amount }}<small>MMK</small></p>
                    @elseif ($transaction->type == 2)
                    <p class="mb-1 text-danger">-{{ $transaction->amount }}<small>MMK</small></p>
                    @endif
                </div>
               
                <p class="text-muted mb-1">
                   @if ($transaction->type == 1)
                       From
                   @elseif ($transaction->type == 2)
                       To
                   @endif
                   {{ $transaction->source ? $transaction->source->name : '-' }}
                   <br>
                   {{ $transaction->created_at }}
                </p>
            </div>
        </div>
    @endforeach
</div>

@endsection
