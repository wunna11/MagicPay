@extends('frontend.layouts.app')

@section('title', 'Update Password')

@section('content')
<div class="update-password">
    <div class="card mt-3">
        <div class="card-body">
            <div class="text-center">
                <img src="{{ asset('images/password.png') }}" alt="">
            </div>
            <form action="{{ route('update-password.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="">Old Password</label>
                    <input type="password" name="old_password" class="form-control">
                    @error('old_password')
                        <div class="text-danger" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="">New Password</label>
                    <input type="password" name="new_password" class="form-control">
                    @error('new_password')
                        <div class="text-danger" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                {{-- <div class="mt-3"> --}}
                    {{-- <a href="{{ route('profile') }}" type="button" class="btn btn-success">Back</a> --}}
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
   
@endsection

@section('scripts')
    <script>
        
    </script>
@endsection