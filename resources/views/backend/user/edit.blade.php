@extends('backend.layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Edit User</div>
        </div>
    </div>
</div>


<div class="contnet">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                {{-- @include('backend.layouts.flash') --}}
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="" value="{{$user->name}}">
                    @error('name')
                    <div class="text-danger" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="" value="{{$user->email}}">
                    @error('email')
                    <div class="text-danger" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Phone</label>
                    <input type="number" name="phone" class="form-control" id="" value="{{$user->phone}}">
                    @error('phone')
                    <div class="text-danger" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="" value="">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success mr-2">Update</button>
                    <a href="{{ route('admin.user.index') }}" type="button" class="btn btn-warning">Back</a>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection