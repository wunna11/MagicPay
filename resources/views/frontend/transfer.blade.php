@extends('frontend.layouts.app')

@section('title', 'Transfer')

@section('content')
<div class="transfer">
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('transfer_confirm') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label for=""><strong>From</strong></label>
                    <p class="text-muted">{{ $authUser->name }}</p>
                    <p class="text-muted">{{ $authUser->phone }}</p>
                </div>

                <div class="form-group">
                    <label for="">To <span class="text-success to_account_info"></span></label>
                    <div class="input-group mb-3">
                        <input type="text" name="to_phone" vlaue="{{ old('to_phone') }}" class="form-control to_phone">
                        <span class="input-group-text btn btn-primary verify-btn" id="basic-addon2"><i class="fas fa-check-circle"></i></span>
                    </div>
                    @error('to_phone')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
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

