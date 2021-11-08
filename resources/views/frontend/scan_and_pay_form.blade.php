@extends('frontend.layouts.app')

@section('title', 'Scan & Pay Form')

@section('content')
<div class="transfer">
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('scanAndPayConfirm') }}" method="POST" autocomplete="off">
                @csrf

                <input type="hidden" name="to_phone" value="{{ $to_account->phone }}">
                <div class="form-group">
                    <label for=""><strong>From</strong></label>
                    <p class="text-muted">{{ $from_account->name }}</p>
                    <p class="text-muted">{{ $from_account->phone }}</p>
                </div>

                <div class="form-group">
                    <label for=""><strong>To</strong></label>
                    <p class="text-muted">{{ $to_account->name }}</p>
                    <p class="text-muted">{{ $to_account->phone }}</p>
                </div>

                <div class="form-group">
                    <label for="">Amount</label>
                    <input type="number" name="amount" class="form-control" value="{{old('amount')}}">
                    @error('amount')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Description</label>
                    <textarea name="description" id="" class="form-control">{{old('description')}}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Continue</button>
            </form>
        </div>
    </div>
</div>
   
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.verify-btn').on('click', function () {
            var phone = $('.to_phone').val();
            $.ajax({
                url: '/to-account-verify?phone=' + phone,
                type: 'GET',
                success: function(res) {
                    console.log(res);
                    if(res.status == 'success') {
                        $('.to_account_info').text('('+res.data['name']+')');
                    } else {
                        $('.to_account_info').text('('+res.message+')');
                    }
                }
            });
        })
    })
</script>
@endsection

