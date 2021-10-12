@extends('frontend.layouts.app')

@section('title', 'Magic Pay')

@section('content')
<div class="home">
    <div class="row">
        <div class="col-md-12">
            <div class="profile">
                <img src="https://ui-avatars.com/api/?name=wunna" alt="">
                <h5 class="mt-3">{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->wallet ? number_format($user->wallet->amount) : '-'}} <span>MMK</span></p>
            </div>
        </div>

        <div class="col-6">
            <div class="card shortcut-box">
                <div class="card-body p-3">
                    <img src="{{ asset('images/qr-code-scan.png') }}" alt="">
                    <span>Scan & Pay</span>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card shortcut-box">
                <div class="card-body p-3">
                    <img src="{{ asset('images/qr-code.png') }}" alt="">
                    <span>Receive QR</span>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mt-3">
                <div class="card-body pr-0">
                    <div class="function-box">
                        <a href="{{ route('transfer') }}" class="d-flex justify-content-between">
                            <span class="mr-3"><img src="{{asset('images/transfer-money.png')}}" alt="">Transfer</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                    </div>
                    <hr>
                    <div class="function-box">
                        <a href="" class="d-flex justify-content-between">
                            <span class="mr-3"><img src="{{asset('images/wallet.png')}}" alt="">Wallet</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                    </div>
                    <hr>
                    <div class="function-box">
                        <a href="" class="d-flex justify-content-between">
                            <span class="mr-3"><img src="{{asset('images/transaction.png')}}" alt="">Transaction</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



        
    
@endsection
    