@extends('frontend.layouts.app')

@section('title', 'Wallet')

@section('content')
<div class="wallet">
    <div class="card my-card">
        <div class="card-body">
            <div>
                <span>Balance</span>
                <h4>{{ number_format($authUser->wallet ? $authUser->wallet->amount : '0') }}<span>MMK</span></h4>
            </div>
            
            <div class="mt-3">
                <span>Account Number</span>
                <h5>{{ $authUser->wallet ? $authUser->wallet->account_number : '-' }}</h5>
            </div>
            
            <div class="mt-3">
                <p>{{ $authUser->name }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
