@extends('backend.layouts.app')
@section('title', 'Create Admin User')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Create Admin User</div>
        </div>
    </div>
</div>


<div class="contnet">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.admin-user.store') }}" method="POST">
                {{-- @include('backend.layouts.flash') --}}
                @csrf
                <div class="mb-3">
                    <label for="" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="" value="{{ old('name') }}">
                    @error('name')
                    <div class="text-danger" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="" value="{{ old('email') }}">
                    @error('email')
                    <div class="text-danger" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Phone</label>
                    <input type="number" name="phone" class="form-control" id="" value="{{ old('phone') }}">
                    @error('phone')
                    <div class="text-danger" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="" value="{{ old('password') }}">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success mr-2">Create</button>
                    <a href="{{ route('admin.admin-user.index') }}" type="button" class="btn btn-warning">Back</a>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })

    Toast.fire({
      icon: 'success',
      title: 'Signed in successfully'
    })
</script>
@endsection