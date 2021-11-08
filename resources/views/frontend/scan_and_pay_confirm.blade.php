@extends('frontend.layouts.app')

@section('title', 'Scan & Pay Confirmation')

@section('content')
<div class="transfer">
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('scanAndPayComplete') }}" method="POST" id="form">
                @csrf
                @error('transfer_message')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <input type="hidden" name="to_phone" value="{{ $to_account->name }}">
                <input type="hidden" name="to_phone" value="{{ $to_account->phone }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="hidden" name="description" value="{{ $description }}">

                <div class="form-group">
                    <label for=""><strong>From</strong></label>
                    <p class="text-muted">{{ $from_account->name }}</p>
                    <p class="text-muted">{{ $from_account->phone }}</p>
                </div>

                <div class="form-group">
                    <label for="">To</label>
                    <p class="text-muted mb-1">{{ $to_account->name }}</p> 
                    <p class="text-muted mb-1">{{ $to_account->phone }}</p>    
                </div>

                <div class="form-group">
                    <label for="">Amount(MMK)</label>
                    <p class="text-muted mb-1"><b>{{ number_format($amount) }}</b></p>    
                </div>

                <div class="form-group">
                    <label for="">Description</label>
                    <p class="text-muted mb-1">{{ $description }}</p>    
                </div>

                <button type="submit" class="btn btn-primary btn-block confirm-btn">Transfer</button>
            </form>
        </div>
    </div>
</div>
   
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.confirm-btn').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
              title: '<strong>Enter Your Password!</strong>',
              icon: 'info',
              html: '<input type="password" class="form-control text-center password" />',
              showCloseButton: true,
              showCancelButton: true,
              confirmButtonText: 'Submit',
              reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    var password = $('.password').val();

                    $.ajax({
                        url: '/password-check?password=' + password,
                        type: 'GET',
                        success: function(res) {
                            if(res.status == 'success') {
                                $('#form').submit();
                            } else {
                                Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: res.message,
                                })
                            }
                        } 
                    });
                }
            });
        })
    })
    
</script>
@endsection

