@extends('frontend.layouts.app')

@section('title', 'Magic Pay')

@section('content')
<div class="account">
    <div class="profile">
        <img src="https://ui-avatars.com/api/?name=wunna" alt="">
    </div>

    <div class="card mt-3">
        <div class="card-body pr-0">
            <div class="d-flex justify-content-between">
                <span class="mr-3">Name</span>
                <span class="mr-3">{{ $user->name}}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <span class="mr-3">Email</span>
                <span class="mr-3">{{ $user->email }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <span class="mr-3">Phone</span>
                <span class="mr-3">{{ $user->phone }}</span>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body pr-0">
            <div class="d-flex justify-content-between">
                <span class="mr-3">Update Password</span>
                <span class="mr-3"><i class="fas fa-angle-right"></i></span>
            </div>
            <hr>
            <div class="d-flex justify-content-between logout">
                <span class="mr-3">Logout</span>
                <span class="mr-3"><i class="fas fa-angle-right"></i></span>
            </div>
        </div>
    </div>
</div>
   
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.logout', function(e) {
            e.preventDefault();

            Swal.fire({
              title: 'Are you sure to logout?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Confirm'
            }).then((result) => {
              if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('logout') }}",
                    type: 'POST',
                    success: function() {
                        window.location.replace("{{ route('profile') }}")
                    }
                });
              }
            });
        });
    </script>
@endsection