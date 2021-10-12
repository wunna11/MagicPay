@extends('frontend.layouts.app')

@section('title', 'Transfer Confirmation')

@section('content')
<div class="transfer">
    <div class="card mt-3">
        <div class="card-body">
            <form action="" method="">
                <div class="form-group">
                    <label for=""><strong>From</strong></label>
                    <p class="text-muted">{{ $authUser->name }}</p>
                    <p class="text-muted">{{ $authUser->phone }}</p>
                </div>

                <div class="form-group">
                    <label for="">To</label>
                    <p class="text-muted mb-1">{{ $to_phone }}</p>    
                </div>

                <div class="form-group">
                    <label for="">Amount(MMK)</label>
                    <p class="text-muted mb-1"><b>{{ number_format($amount) }}</b></p>    
                </div>

                <div class="form-group">
                    <label for="">Description</label>
                    <p class="text-muted mb-1">{{ $description }}</p>    
                </div>

                <button type="submit" class="btn btn-primary btn-block">Transfer</button>
            </form>
        </div>
    </div>
</div>
   
@endsection

