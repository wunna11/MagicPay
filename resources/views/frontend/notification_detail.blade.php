@extends('frontend.layouts.app')

@section('title', 'Notification Detail')

@section('content')
<div class="transaction-detail">
    <div class="card text-center">
        <div class="card-body pr-0">
            <div class="text-center mb-3">
                <img src="{{ asset('images/notification.png')}}" alt="" style="width: 250px">
            </div>

            <h6 class="mb-3"><strong>{{ $notification->data['title']}}</strong></h6>
            <p class="mb-3">{{ $notification->data['message'] }}</p>
            <p>{{ date('d-m-y / H:i:s', strtotime($notification->created_at)) }}</p>
            <a type="button" class="btn btn-primary btn-sm" href="{{ $notification->data['web_link'] }}">Continue</a>
        </div>
    </div>
</div>

@endsection
