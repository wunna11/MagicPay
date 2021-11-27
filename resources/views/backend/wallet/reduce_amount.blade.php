@extends('backend.layouts.app')
@section('title', 'Wallets')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Reduce Amount</div>
        </div>
    </div>
</div>

<div class="content py-3">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.wallet.reduceAmountStore') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="" class="form-label">Username</label>
                    <select name="user_id" id="" class="form-control user_id">
                        <option value=""></option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Amount</label>
                    <input type="number" name="amount" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Description</label>
                    <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{ route('admin.wallet.index') }}" type="button" class="btn btn-warning">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.user_id').select2({
            theme: 'bootstrap4',
            placeholder: "Select a user",
        });
    });
</script>
@endsection