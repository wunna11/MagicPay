@extends('frontend.layouts.app')

@section('title', 'QR Code')

@section('content')
<div class="receive-qr">
    <div class="card my-card">
        <div class="card-body">
            <div class="text-center">
                <p class="text-center mb-0">QR scan to pay me</p>
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(250)->generate($authUser->phone)) !!} ">
                <p class="text-center mb-1"><strong>{{ $authUser->name }}</strong></p>
                <p class="text-center mb-1">{{ $authUser->phone }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
